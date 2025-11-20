<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
                'users.gender as gender',
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

    /**
     * Update an existing student and its related user record.
     */
    public function update(Request $request, $id)
    {
        // Find the student or return 404
        $student = Student::find($id);
        if (! $student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $user = $student->user;
        if (! $user) {
            return response()->json(['message' => 'Related user not found'], 404);
        }

        // Validation rules
        $rules = [
            'firstname' => 'sometimes|required|string|max:255',
            'lastname'  => 'sometimes|required|string|max:255',
            'gender'    => 'sometimes|required|in:male,female',
            'email'     => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'sometimes|required|string|max:50',
            'branch_id' => 'sometimes|required|integer|exists:branches,id',
            'password'  => 'sometimes|nullable|string|min:8|confirmed',

            // student-specific
            'date_of_birth' => 'sometimes|nullable|date',
            'nin' => 'sometimes|nullable|string|max:100|unique:students,nin,' . $student->id,
            'learner_permit_number' => 'sometimes|nullable|string|max:100',
            'enrollment_date' => 'sometimes|nullable|date',
            'address' => 'sometimes|nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Update user fields if provided
            $userData = [];
            foreach (['firstname','lastname','gender','email','phone_number','branch_id','role_id'] as $field) {
                if ($request->has($field)) {
                    $userData[$field] = $request->input($field);
                }
            }

            if (! empty($userData)) {
                $user->fill($userData);
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            $user->save();

            // Update student fields
            $studentData = [];
            foreach (['date_of_birth','nin','branch_id','learner_permit_number','enrollment_date','address'] as $field) {
                if ($request->has($field)) {
                    $studentData[$field] = $request->input($field);
                }
            }

            if (! empty($studentData)) {
                $student->fill($studentData);
                $student->save();
            }

            DB::commit();

            // Reload relations for response
            $student->load('user');

            return response()->json([
                'message' => 'Student updated successfully.',
                'student' => $student,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update student.', 'error' => $e->getMessage()], 500);
        }
    }
}
