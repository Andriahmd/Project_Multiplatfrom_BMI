<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Ambil semua notifikasi (atau notifikasi untuk user tertentu jika ada user_id)
    public function index(Request $request)
    {
        $user = Auth::user(); // Ambil pengguna yang sedang login
        $query = Notification::query();

        // Selalu tampilkan notifikasi umum (user_id is null)
        $query->whereNull('user_id');

        // Jika ada user yang login, tambahkan notifikasi spesifik untuk user tersebut
        if ($user) {
            $query->orWhere('user_id', $user->id);
        }

        $notifications = $query->orderBy('created_at', 'desc')->get();

        // Format data agar sesuai dengan yang diharapkan Flutter
        $formattedNotifications = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'title' => $notification->title,
                'subtitle' => $notification->subtitle,
                'time' => Carbon::parse($notification->created_at)->diffForHumans(),
                'related_id' => $notification->related_id,
                'related_type' => $notification->related_type,
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
        $user = Auth::user(); // Ambil pengguna yang sedang login
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Autentikasi diperlukan.'
            ], 401); // Unauthorized
        }

        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        if ($notification->user_id !== null && $notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk notifikasi ini'
            ], 403); // Forbidden
        }

        $notification->is_read = true;
        $notification->save();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil ditandai sudah dibaca'
        ]);
    }

    public function unreadCount(Request $request)
    {
        $user = Auth::user();

        $query = Notification::query();

        $query->whereNull('user_id');

        if ($user) {
            $query->orWhere(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->where('is_read', false);
            });
        }

        $unreadCount = Notification::where('is_read', false) // <<< PENTING: Filter is_read=false di sini
            ->where(function ($q) use ($user) {
                $q->whereNull('user_id'); // Notifikasi umum
                if ($user) {
                    $q->orWhere('user_id', $user->id); // Notifikasi spesifik user
                }
            })
            ->count();
        return response()->json([
            'success' => true,
            'count' => $unreadCount
        ]);
    }

    //fungsi untuk menghapus notif 
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id()) // Asumsi notifikasi terhubung ke user_id
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notification not found or unauthorized'], 404);
        }

        try {
            $notification->delete();
            return response()->json(['message' => 'Notification deleted successfully'], 200); // Atau 204 No Content
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete notification', 'error' => $e->getMessage()], 500);
        }
    }
}