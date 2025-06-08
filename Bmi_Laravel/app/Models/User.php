<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Laravel\Sanctum\HasApiTokens; // trait untuk API

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable; 

    protected $fillable = ['name', 'email', 'password', 'role']; 
    protected $hidden = ['password', 'remember_token']; 
    protected $casts = [
        'email_verified_at' => 'datetime', 
    ];

    public function bmiRecords()
    {
        return $this->hasMany(BmiRecord::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function userProfiles()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}