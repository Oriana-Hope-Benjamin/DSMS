<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'user_id',
        'license_number',
        'hire_date',
        'transmission_type',
    ];
}
