<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSyllabus extends Model
{
    protected $table = 'course_syllabus';         
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
        'is_mandatory',
    ];
}
