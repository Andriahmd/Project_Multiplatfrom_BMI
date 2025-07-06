<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BmiRecord extends Model
{
      protected $table = 'bmi_records';
    protected $fillable = [
        'user_id',
        'height',
        'weight',
        'bmi',
        'category',
        'recorded_at',
        'age',
        'gender',
        'bmr',
        'activity_level', 
        'tdee' 
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}