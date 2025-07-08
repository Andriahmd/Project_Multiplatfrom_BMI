<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infogizi extends Model
{
    use HasFactory;

    protected $table = 'infogizi';

    protected $fillable = [
        'calories',
        'proteins',
        'fat',
        'carbohydrate',
        'name',
        'image',
    ];
}