<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BmiRecord extends Model
{
    protected $table = 'bmi_records';
    protected $fillable = ['user_id', 'height', 'weight', 'bmi', 'category', 'recorded_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setBmiAttribute($value)
    {
        if ($this->height && $this->weight) {
            $this->attributes['bmi'] = $this->weight / ($this->height * $this->height);
        } else {
            $this->attributes['bmi'] = $value;
        }
    }

    // public function getBmiCategoryAttribute()
    // {
    //     if ($this->bmi < 18.5)
    //         return 'Kurus';
    //     if ($this->bmi <= 24.9)
    //         return 'Ideal';
    //     if ($this->bmi > 30)
    //         return 'Obesitas';
    //     return 'Overweight';
    // }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}