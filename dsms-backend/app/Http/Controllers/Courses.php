<?php

namespace App\Http\Controllers;

use App\Models\Courses as CoursesModel;
use App\Models\CourseAllowedAddon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Exception;

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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'duration_value' => 'required|integer|exists:durations,id',
            'transmission_type' => 'required|in:manual,automatic',
            'lesson_count' => 'required|integer|min:0',
            'course_addon_id' => 'required|integer|exists:course_addons,id',
        ]);

        DB::beginTransaction();
        try {
            // Create the course
            $course = CoursesModel::create($validated);

            // Attach allowed addon for this course
            CourseAllowedAddon::create([
                'course_id' => $course->id,
                'course_addon_id' => $validated['course_addon_id'],
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Course created successfully.',
                'data' => $course,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create course.',
                'error' => $e->getMessage(),
            ], 500);
        }
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


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $course = CoursesModel::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'sometimes|required|numeric|min:0',
            'duration' => 'sometimes|required|integer|min:1',
            'duration_value' => 'sometimes|required|integer|min:1',
            'course_addon_id' => 'sometimes|required|integer|exists:course_addons,id',
            'transmission_type' => 'sometimes|required|in:manual,automatic',
            'lesson_count' => 'sometimes|required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Update the course
            $course->update($validated);

            // Update or create the allowed addon if provided
            if ($request->has('course_addon_id')) {
                CourseAllowedAddon::updateOrCreate(
                    ['course_id' => $course->id],
                    ['course_addon_id' => $validated['course_addon_id']]
                );
            }

            DB::commit();

            return response()->json([
                'message' => 'Course updated successfully.',
                'data' => $course
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update course.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
