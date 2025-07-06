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

    protected $fillable = ['name', 'email', 'password', 'role', 'foto'];
    protected $hidden = ['password', 'remember_token']; 
    protected $casts = [
        'email_verified_at' => 'datetime', 
    ];

    public function bmiRecords()
    {
        return $this->hasMany(BmiRecord::class);
    }

   

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    
public function recommendations()
{
    return $this->hasManyThrough(Recommendation::class, BmiRecord::class);
}

public function toArray()
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'email' => $this->email,
        'foto' => $this->foto,
        'updated_at' => $this->updated_at,
    ];
}
    
}