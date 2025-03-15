<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\EnrolledStudent;
use App\Http\Requests\StoreEnrolledStudentRequest;
use App\Http\Requests\UpdateEnrolledStudentRequest;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
class EnrolledStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = EnrolledStudent::all();
        return response()->json($students);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(StoreEnrolledStudentRequest $request)
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
    public function show(EnrolledStudent $enrolledStudent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EnrolledStudent $enrolledStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnrolledStudentRequest $request, EnrolledStudent $enrolledStudent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EnrolledStudent $enrolledStudent)
    {
        //
    }
}
