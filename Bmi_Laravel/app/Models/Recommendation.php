<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Recommendation extends Model
{
    protected $fillable = [
        'user_id',
        'bmi_record_id',
        'recommendation_text',
        'image',
        'type', // Pastikan ini ada di $fillable
        'description', // Pastikan ini ada di $fillable
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bmiRecord()
    {
        return $this->belongsTo(BmiRecord::class);
    }

    // Tambahkan method ini
    protected static function booted()
    {
        static::created(function ($recommendation) {
            // Buat notifikasi umum untuk semua user (user_id = null)
            Notification::create([
                'user_id' => null, // Notifikasi untuk semua pengguna
                'type' => 'new_recommendation',
                'title' => 'Rekomendasi Baru: ' . $recommendation->type,
                'subtitle' => substr(strip_tags($recommendation->description ?? $recommendation->recommendation_text), 0, 100) . '...',
                'related_id' => $recommendation->id,
                'related_type' => 'recommendation',
                'is_read' => false,
            ]);
        });
    }
}