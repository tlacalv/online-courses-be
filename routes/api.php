<?php

use App\Http\Controllers\{InstructorController, StudentController, CourseController};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::resource('instructors', InstructorController::class);
Route::resource('students', StudentController::class);
Route::resource('courses', CourseController::class);
Route::post('/courses/chronogram', [CourseController::class, 'chronogram']);
Route::post('/students/enlist/{id}', [StudentController::class, 'enlist']);
Route::delete('/students/remove/{id}', [StudentController::class, 'remove']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
