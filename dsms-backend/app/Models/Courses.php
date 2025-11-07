<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    protected $fillable = [
        'name',
        'description',
        'total_hours',
        'total_price',
        'initial_deposit_amount',
        'status_id'
    ];

}
