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
    'type',
    'description',
];

public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bmiRecord()
    {
        return $this->belongsTo(BmiRecord::class);
    }
}