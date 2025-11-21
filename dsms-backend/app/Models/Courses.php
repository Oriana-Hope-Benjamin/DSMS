<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
     protected $fillable = [
        'name',
        'description',
        'base_price',
        'duration',
        'duration_value',
        'course_addon_id',
        'transmission_type',
        'lesson_count'
    ];

}
