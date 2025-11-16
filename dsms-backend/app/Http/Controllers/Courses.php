<?php

namespace App\Http\Controllers;

use App\Models\Courses as CoursesModel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class Courses extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = CoursesModel::all();
        return response()->json($courses);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:courses,name',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'duration_value' => 'required|integer|min:1',
        ]);

        $course = CoursesModel::create($validated);

        return response()->json([
            'message' => 'Course created successfully.',
            'data' => $course
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $course = CoursesModel::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        return response()->json($course);
    }

    
   //  * Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $course = CoursesModel::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:courses,name,' . $id,
            'description' => 'nullable|string',
            'base_price' => 'sometimes|required|numeric|min:0',
            'duration' => 'sometimes|required|integer|min:1',
            'duration_value' => 'sometimes|required|integer|min:1',
        ]);

        $course->update($validated);

        return response()->json([
            'message' => 'Course updated successfully.',
            'data' => $course
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $course = CoursesModel::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        $course->delete();

        return response()->json(['message' => 'Course deleted successfully.']);
    }
}
