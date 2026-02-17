<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/courses', [CourseController::class, 'index']);

    Route::post('/discussions', [DiscussionController::class, 'discussion']);

    Route::post('/discussions/{id}/replies', [ReplyController::class, 'reply']);

    Route::get('/reports/courses', [ReportController::class, 'course']);
    Route::get('/reports/assignments', [ReportController::class, 'assignment']);
    Route::get('/reports/students/{id}', [ReportController::class, 'student']);

    Route::middleware('role:lecturer')->group(function () {
        Route::post('/courses', [CourseController::class, 'store']);
        Route::put('/courses/{id}', [CourseController::class, 'update']);
        Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

        Route::post('/materials', [MaterialController::class, 'upload']);
        Route::put('/materials/{id}', [MaterialController::class, 'update']);
        Route::delete('/materials/{id}', [MaterialController::class, 'destroy']);

        Route::post('/assignments', [AssignmentController::class, 'assignment']);
        Route::put('/assignments/{id}', [AssignmentController::class, 'update']);
        Route::delete('/assignments/{id}', [AssignmentController::class, 'destroy']);

        Route::post('/submissions/{id}/grade', [SubmissionController::class, 'grade']);
    });

    Route::middleware('role:student')->group(function () {
        Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll']);

        Route::get('/materials/{id}/download', [MaterialController::class, 'download']);

        Route::post('/submissions', [SubmissionController::class, 'submission']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});
