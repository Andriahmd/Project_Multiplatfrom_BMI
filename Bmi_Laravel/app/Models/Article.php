<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    // Tambahkan 'category' ke fillable
    protected $fillable = ['user_id', 'title', 'content', 'image', 'category']; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tambahkan method ini
    protected static function booted()
    {
        static::created(function ($article) {
            // Buat notifikasi umum untuk semua user (user_id = null)
            Notification::create([
                'user_id' => null, // Notifikasi untuk semua pengguna
                'type' => 'new_article',
                'title' => 'Artikel Baru: ' . $article->title,
                'subtitle' => substr(strip_tags($article->content), 0, 100) . '...', // Ambil sebagian konten
                'related_id' => $article->id,
                'related_type' => 'article',
                'is_read' => false,
            ]);
        });
    }
}