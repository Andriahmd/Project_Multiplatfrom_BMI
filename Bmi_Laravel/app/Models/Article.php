<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    protected $fillable = ['user_id', 'title', 'content']; // Tambahkan kolom sesuai kebutuhan

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}