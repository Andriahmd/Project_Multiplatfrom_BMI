<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon; // Untuk format waktu

class NotificationController extends Controller
{
    // Ambil semua notifikasi (atau notifikasi untuk user tertentu jika ada user_id)
    public function index(Request $request)
    {
        
        $notifications = Notification::orderBy('created_at', 'desc')->get();

        // Format data agar sesuai dengan yang diharapkan Flutter
        $formattedNotifications = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'title' => $notification->title,
                'subtitle' => $notification->subtitle,
                'time' => Carbon::parse($notification->created_at)->diffForHumans(), // Contoh: "5 minutes ago"
                'article_id' => $notification->related_type === 'article' ? $notification->related_id : null,
                'recommendation_id' => $notification->related_type === 'recommendation' ? $notification->related_id : null,
                'is_read' => (bool) $notification->is_read,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedNotifications
        ]);
    }

    // Fungsi untuk menandai notifikasi sudah dibaca
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        $notification->is_read = true;
        $notification->save();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil ditandai sudah dibaca'
        ]);
    }
}