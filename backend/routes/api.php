<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\BatchController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EnrolledStudentController;
use App\Http\Controllers\Api\QuestionController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



// Route::get('/' , function(){
//    return 'hello';
// });

// Route:: POST('/post/add',[PostController::class, 'store']);
// Route::GET('/post',[PostController::class, 'index']);


Route::prefix('/')->group(function () {
    Route::apiResource('post', PostController::class);
});

Route::middleware('guest')->group(function () {
    Route::prefix('/')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('/register', 'register');
            Route::post('/login', 'login')->name('login');
            Route::post('/logout', 'logout');
        });
    });
});


Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/store', [ProductController::class, 'store']);
    Route::get('/count', [ProductController::class, 'count']);
});



route::apiResource('batch', BatchController::class);
route::apiResource('enrolledstudent', EnrolledStudentController::class);
route::apiResource('users', UserController::class);
route::apiResource('courses', CourseController::class);
route::apiResource('departments', DepartmentController::class);
route::apiResource('question', QuestionController::class);


route::get('dashboard', [DashboardController::class, 'index']);
