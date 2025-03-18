<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Batch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnrolledStudent;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::count();
        $batch = Batch::count();
        $enrolled_student = EnrolledStudent::count();
        $students_by_course = EnrolledStudent::select('course', \DB::raw('count(*) as count'))
            ->groupBy('course')
            ->get();

        return response()->json([
            "user-count" => $users,
            "batch-count" => $batch,
            "enrolled-student-count" => $enrolled_student,
            "students-by-course" => $students_by_course
        ]);
    }
}
