<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BmiRecord extends Model
{
      protected $table = 'bmi_records';
    protected $fillable = ['user_id', 'height', 'weight', 'bmi', 'category', 'recorded_at', 'age', 'gender', 'bmr']; // Tambahkan age, gender, bmr

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Hapus atau modifikasi mutator setBmiAttribute jika Anda ingin perhitungan hanya di controller
    // public function setBmiAttribute($value)
    // {
    //     if ($this->height && $this->weight) {
    //         $this->attributes['bmi'] = $this->weight / ($this->height * $this->height);
    //     } else {
    //         $this->attributes['bmi'] = $value;
    //     }
    // }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}