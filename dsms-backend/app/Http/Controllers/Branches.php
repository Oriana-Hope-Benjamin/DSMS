<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branches as BranchModel;
use Illuminate\Support\Facades\Validator;
use Exception;

class Branches extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $branches = BranchModel::all();
            return response()->json($branches, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error retrieving branches: ' . $e->getMessage()], 500);
        }
    }


    public function create(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'branch_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:branches', 
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $branch = BranchModel::create($request->all());
            return response()->json($branch, 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error creating branch: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
   public function show($id)
    {
        try {
            $branch = BranchModel::findOrFail($id);
            return response()->json($branch, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Branch not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error retrieving branch: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function update(Request $request, $id)
{
    // Validate incoming data (only validate fields that are present)
    $validator = Validator::make($request->all(), [
        'branch_name'  => 'sometimes|required|string|max:255',
        'address'      => 'sometimes|required|string',
        'phone_number' => 'sometimes|required|string|max:50',
        'email'        => 'sometimes|required|string|email|max:255|unique:branches,email,' . $id,
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    try {
        $branch = BranchModel::findOrFail($id);

        // Only update the provided fields
        $data = $request->only(['branch_name', 'address', 'phone_number', 'email']);
        $branch->fill($data);
        $branch->save();

        return response()->json($branch, 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['message' => 'Branch not found'], 404);
    } catch (Exception $e) {
        return response()->json(['message' => 'Error updating branch: ' . $e->getMessage()], 500);
    }
}
// ...existing code...

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $branch = BranchModel::findOrFail($id);
            $branch->delete();
            return response()->json(['message' => 'Branch deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Branch not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting branch: ' . $e->getMessage()], 500);
        }
    }
}
