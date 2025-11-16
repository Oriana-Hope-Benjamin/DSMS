<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class StudentController extends Controller
{
      /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Join students with users and branches to return richer data in one request
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('branches', 'students.branch_id', '=', 'branches.id')
            ->select(
                'students.*',
                'users.firstname as user_firstname',
                'users.lastname as user_lastname',
                'users.email as user_email',
                'users.phone_number as user_phone',
                'branches.branch_name as branch_name'
            )
            ->get();

        return response()->json($students);
    }

    /**
     * Store a new student with user + student records.
     */
    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();
        // Use a DB transaction so we don't leave a user without a student record
        DB::beginTransaction();
        try {
            // Create user first
            $user = User::create([
                'firstname'    => $validated['firstname'],
                'lastname'     => $validated['lastname'],
                'gender'       => $validated['gender'],
                'email'        => $validated['email'],
                'password'     => Hash::make($validated['password'] ?? 'defaultpassword'),
                'phone_number' => $validated['phone_number'],
                'branch_id'    => $validated['branch_id'],
                'role_id'      => $validated['role_id'],
            ]);

            // Create student linked to the user
            $student = Student::create([
                'user_id'       => $user->id,
                'date_of_birth' => $validated['date_of_birth'],
                'nin'   => $validated['nin'],
                'branch_id'     => $validated['branch_id'],
                'learner_permit_number'=> $validated['learner_permit_number'] ?? null,
                'enrollment_date'     => $validated['enrollment_date'] ?? now(),
                'student_number' => Student::generateStudentNumber(),
                'address'      => $validated['address'],
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Student registered successfully.',
                'user'    => $user,
                'student' => $student,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            // Best-effort cleanup: if the user exists, try to delete it
            try {
                if (isset($user) && $user instanceof User) {
                    $user->delete();
                }
            } catch (Exception $cleanupEx) {
                // ignore cleanup failures
            }

            return response()->json([
                'message' => 'Failed to register student.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
