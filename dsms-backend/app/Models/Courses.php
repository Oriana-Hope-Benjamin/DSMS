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
    ];

}
