<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAllowedAddon extends Model
{
    protected $fillable = [
        'course_id',
        'course_addon_id'
    ];
}
