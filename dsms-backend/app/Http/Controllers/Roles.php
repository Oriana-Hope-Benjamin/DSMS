<?php

namespace App\Http\Controllers;

use App\Models\Roles as RolesModel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class Roles extends Controller
{
    public function index()
    {
        $roles = RolesModel::all();
        return response()->json($roles);
    }

    //Add role

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique',
            'description' => 'nullable|string',
        ]);

        $roles = RolesModel::create($validated);

        return response()->json([
            'message' => 'Role created successfully.',
            'data' => $roles
        ], 201);
    }

    //Show role details
    public function show($id)
    {
        $roles = RolesModel::find($id);

        if (!$roles) {
            return response()->json(['message' => 'Role not found.'], 404);
        }

        return response()->json($roles);
    }

    //Update role details
    public function update(Request $request, $id)
    {
        $roles = RolesModel::find($id);

        if (!$roles) {
            return response()->json(['message' => 'Role not found.'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:roles,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $roles->update($validated);

        return response()->json([
            'message' => 'Role updated successfully.',
            'data' => $roles
        ]);
    }

    //Delete role
    public function destroy($id)
    {
        $roles = RolesModel::find($id);

        if (!$roles) {
            return response()->json(['message' => 'Role not found.'], 404);
        }

        $roles->delete();

        return response()->json(['message' => 'Role deleted successfully.']);
    }
}
