<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Batch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function index(){
        $users = User::count();
        $batch = Batch::count();
        return response()->json([
            "user-count"=>$users,
            "batch-count"=>$batch
        ]);
   }
}
