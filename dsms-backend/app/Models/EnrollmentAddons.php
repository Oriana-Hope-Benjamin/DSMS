<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentAddons extends Model
{
    protected $fillable = [
        'enrollment_id',
        'course_addon_id',
        'addon_price',
    ];
}
