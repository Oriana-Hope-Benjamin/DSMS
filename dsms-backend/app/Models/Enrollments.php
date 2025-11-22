<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollments extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'branch_id',
        'enrollment_date',
        'total_price',
        'payment_status',
        'enrollment_status',
    ];
}
