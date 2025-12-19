<?php

namespace App\Http\Controllers;
use App\Models\CourseSyllabus as CourseSyllabusModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class CourseSyllabus extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:1',
            'is_mandatory' => 'required|boolean',
        ]);

        try {
            $syllabusItem = CourseSyllabusModel::create($validated);

            return response()->json([
                'message' => 'Course syllabus item created successfully.',
                'data' => $syllabusItem,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create course syllabus item.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'course_id' => 'sometimes|required|integer|exists:courses,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'sometimes|required|integer|min:1',
            'is_mandatory' => 'sometimes|required|boolean',
        ]);

        $syllabusItem = CourseSyllabusModel::find($id);
        if (!$syllabusItem) {
            return response()->json(['message' => 'Course syllabus item not found.'], 404);
        }
        try {
            $syllabusItem->update($validated);

            return response()->json([
                'message' => 'Course syllabus item updated successfully.',
                'data' => $syllabusItem,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update course syllabus item.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $syllabusItem = CourseSyllabusModel::find($id);
        if (!$syllabusItem) {
            return response()->json(['message' => 'Course syllabus item not found.'], 404);
        }
        try {
            $syllabusItem->delete();

            return response()->json([
                'message' => 'Course syllabus item deleted successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to delete course syllabus item.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
