<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\EnrolledStudent;
use App\Http\Requests\StoreEnrolledStudentRequest;
use App\Http\Requests\UpdateEnrolledStudentRequest;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;

class EnrolledStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $students = EnrolledStudent::all();
        return response()->json($students);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnrolledStudentRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $student = EnrolledStudent::create($validated);
        return response()->json([
            'message' => 'Student created successfully',
            'student' => $student
        ], 201);
    }

    /**
     * Store a newly created resource in storage via file upload.
     */
    public function upload(StoreEnrolledStudentRequest $request): JsonResponse
    {
        // Validate file type and size
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:2048',
        ]);

        try {
            // Load file and process it
            $import = new StudentsImport();
            Excel::import($import, $request->file('file'));

            // Get the import results
            return response()->json([
                'message' => "Upload complete!",
                'students_uploaded' => $import->getSuccessCount(),
                'duplicates_skipped' => $import->getDuplicateCount(),
                'uploaded_students' => $import->getStudents(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error processing file',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EnrolledStudent $enrolledStudent): JsonResponse
    {
        return response()->json($enrolledStudent);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnrolledStudentRequest $request, EnrolledStudent $enrolledStudent): JsonResponse
    {
        $validated = $request->validated();
        $enrolledStudent->update($validated);
        return response()->json([
            'message' => 'Student updated successfully',
            'student' => $enrolledStudent
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EnrolledStudent $enrolledStudent)
    {
        $enrolledStudent->delete();
        return response()->json([
            'message' => 'Student deleted successfully'
        ]);
    }
}
