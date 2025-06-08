<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BmiRecord extends Model
{
    protected $table = 'bmi_records';
    protected $fillable = ['user_id', 'height', 'weight', 'bmi']; // Tambahkan kolom sesuai kebutuhan

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Otomatis hitung BMI berdasarkan height dan weight
    public function setBmiAttribute($value)
    {
        if ($this->height && $this->weight) {
            $this->attributes['bmi'] = $this->weight / ($this->height * $this->height);
        } else {
            $this->attributes['bmi'] = $value;
        }
    }

    // Tentukan kategori BMI
    public function getBmiCategoryAttribute()
    {
        if ($this->bmi < 18.5) return 'Kurus';
        if ($this->bmi <= 24.9) return 'Ideal';
        if ($this->bmi > 30) return 'Obesitas';
        return 'Overweight'; // 25-29.9
    }
}