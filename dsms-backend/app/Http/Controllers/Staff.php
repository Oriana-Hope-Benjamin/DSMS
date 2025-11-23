<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequestForm;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\User;
use App\Models\Staff as StaffModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class Staff extends Controller
{
    public function index()
    {
        
        $staff = DB::table('staff')
            ->leftJoin('users', 'staff.user_id', '=', 'users.id')
            ->leftJoin('branches', 'users.branch_id', '=', 'branches.id')
            ->select(
                'staff.*',
                'users.firstname as user_firstname',
                'users.lastname as user_lastname',
                'users.email as user_email',
                'users.phone_number as user_phone',
                'users.gender as gender',
                'users.branch_id as branch_id',
                'branches.branch_name as branch_name',
            )
            ->get();

        return response()->json($staff);
    }
     public function store(StoreStaffRequestForm $request)
    {
        $validated = $request->validated();
        // Use a DB transaction so we don't leave a user without a staff record
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

            // Create staff linked to the user
            $staff = StaffModel::create([
                'user_id'       => $user->id,
                'license_number' => $validated['licencse_number'] ?? null,
                'transmission_type' => $validated['transmission_type'] ?? null,
                'hire_date'     => now(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'staff registered successfully.',
                'user'    => $user,
                'staff' => $staff,
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
                'message' => 'Failed to register staff.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    //Update Staff details
    public function update(UpdateStaffRequest $request, $id)
    {
        // Find the staff record
        $staff = StaffModel::find($id);
        if (! $staff) {
            return response()->json(['message' => 'Staff not found.'], 404);
        }

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Update related user
            $user = User::find($staff->user_id);
            if (! $user) {
                DB::rollBack();
                return response()->json(['message' => 'Related user not found.'], 404);
            }

            $userData = [];
            foreach (['firstname','lastname','gender','email','phone_number','branch_id','role_id'] as $field) {
                if (array_key_exists($field, $validated)) {
                    $userData[$field] = $validated[$field];
                }
            }

            if (! empty($userData)) {
                $user->fill($userData);
            }

            $user->save();

            // Update staff specific fields
            $staffData = [];
            if (array_key_exists('license_number', $validated)) {
                $staffData['license_number'] = $validated['license_number'];
            }
            if (array_key_exists('transmission_type', $validated)) {
                $staffData['transmission_type'] = $validated['transmission_type'];
            }

            if (! empty($staffData)) {
                $staff->fill($staffData);
                $staff->save();
            }

            DB::commit();

            // reload related user
            $staff->load = $user;

            return response()->json([
                'message' => 'Staff updated successfully.',
                'staff' => $staff,
                'user' => $user,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update staff.', 'error' => $e->getMessage()], 500);
        }
    }

    //show staff details
   public function show($id)
    {
         $staff = DB::table('staff')
            ->leftJoin('users', 'staff.user_id', '=', 'users.id')
            ->leftJoin('branches', 'users.branch_id', '=', 'branches.id')
            ->select(
                'staff.*',
                'users.firstname as user_firstname',
                'users.lastname as user_lastname',
                'users.email as user_email',
                'users.phone_number as user_phone',
                'users.gender as gender',
                'users.branch_id as branch_id',
                'branches.branch_name as branch_name',
            )
            ->where('staff.id', $id)
            ->get();

        return response()->json($staff);
    }
}
