<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\StudentNumberCounter;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_of_birth',
        'student_number',
        'branch_id',
        'nin',
        'learner_permit_number',
        'enrollment_date',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Generate student number: ST{YY}-{NNN}
     */
    /**
     * Generate student number: ST{YY}-{NNN}
     * Public helper so controllers can reuse the same algorithm.
     */
    public static function generateStudentNumber(): string
    {
        $year = now()->format('y');

        // Find or create counter record for this year
        $counter = StudentNumberCounter::firstOrCreate(
            ['year' => $year],
            ['counter' => 0]
        );

        // Increment counter atomically
        $counter->counter += 1;
        $counter->save();

        return 'ST' . $year . '-' . str_pad($counter->counter, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Ensure a student_number is set before creating the model.
     * Uses the same generator above; if the controller already set a value
     * it will not be overwritten.
     */
    protected static function booted()
    {
        static::creating(function ($student) {
            if (empty($student->student_number)) {
                $student->student_number = self::generateStudentNumber();
            }
        });
    }
}
