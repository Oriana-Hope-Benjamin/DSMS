<?php

namespace App\Http\Controllers;

use App\Models\CourseAddons as CourseAddonsModel;
use Illuminate\Http\Request;

class CourseAddons extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $course_addons = CourseAddonsModel::all();
        return response()->json($course_addons);
    }

    

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
        ]);

        $course_addons = CourseAddonsModel::create($validated);

        return response()->json([
            'message' => 'Course Addon created successfully.',
            'data' => $course_addons
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $course_addon = CourseAddonsModel::find($id);

        if (!$course_addon) {
            return response()->json(['message' => 'Course Addon not found.'], 404);
        }

        return response()->json($course_addon);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $course_addons = CourseAddonsModel::find($id);

        if (!$course_addons) {
            return response()->json(['message' => 'Addon not found.'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:roles,name,' . $id,
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|numeric',
        ]);

        $course_addons->update($validated);

        return response()->json([
            'message' => 'Course Addon updated successfully.',
            'data' => $course_addons
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
     public function destroy($id)
    {
        $course_addon = CourseAddonsModel::find($id);

        if (!$course_addon) {
            return response()->json(['message' => 'course Addon not found.'], 404);
        }

        $course_addon->delete();

        return response()->json(['message' => 'course Addon deleted successfully.']);
    }
}
