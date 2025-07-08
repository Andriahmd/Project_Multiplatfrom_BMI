<?php

namespace App\Services;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Log; // Import facade Log

class PushNotificationService
{
    protected $messaging;

    public function __construct()
    {
        // Pastikan 'firebase.credentials.file' dikonfigurasi di config/firebase.php
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials.file'));
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification(array $data, array $notification, array $tokens = [])
    {
        if (empty($tokens)) {
            $message = CloudMessage::withTarget('topic', 'all_users')
                ->withNotification($notification)
                ->withData($data);
        } else {
            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData($data);
        }

        try {
            if (empty($tokens)) {
                $this->messaging->send($message);
            } else {
                $this->messaging->sendMulticast($message, $tokens);
            }
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send FCM message: " . $e->getMessage());
            return false;
        }
    }

    // Fungsi: Mengirim notifikasi artikel baru
    public function sendNewArticleNotification($article, string $notificationId)
    {
        $notification = [
            'title' => 'Artikel Baru: ' . $article->title,
            'body' => 'Ada artikel menarik terbaru yang siap Anda baca!',
        ];
        $data = [
            'type' => 'new_article',
            'article_id' => (string)$article->id,
            'notification_id' => $notificationId, // Menggunakan ID yang diteruskan
        ];
        $this->sendNotification($data, $notification);
    }

    // Fungsi: Mengirim notifikasi rekomendasi baru
    public function sendNewRecommendationNotification($recommendation, string $notificationId)
    {
        $notification = [
            'title' => 'Rekomendasi Baru: ' . ($recommendation->type ?? 'Umum'),
            'body' => 'Temukan rekomendasi kesehatan dan kebugaran terbaru untuk Anda!',
        ];
        $data = [
            'type' => 'new_recommendation',
            'recommendation_id' => (string)$recommendation->id,
            'notification_id' => $notificationId, // Menggunakan ID yang diteruskan
        ];
        $this->sendNotification($data, $notification);
    }
}