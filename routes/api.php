<?php

use App\Http\Controllers\API\ChangePasswordController;
use App\Http\Controllers\API\IncidenceController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UploadCSV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\UnityController;
use App\Http\Controllers\API\TaughtController;
use App\Http\Controllers\API\ExportCSVController;
use App\Http\Controllers\API\EndCourseController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group( function () {
    Route::get('/clear', [EndCourseController::class, 'endCourse']);
    Route::resource('incidences', IncidenceController::class);
    Route::get('/export/taughts', [ExportCSVController::class, 'ExportTaughtCSV']);
    Route::get('/export/reviews', [ExportCSVController::class, 'ExportReviewsCSV']);
    Route::get('/export/enrolleds', [ExportCSVController::class, 'ExportEnrolledsCSV']);
    Route::get('/taughts', [TaughtController::class, 'GetAll']);
    Route::get('/taughts/{user}', [TaughtController::class, 'GetUserTaughts']);
    Route::get('/{unity}/subjects', [TaughtController::class, 'getUnitySubjects']);
    Route::get('/unities', [UnityController::class, 'GetAllUnities']);
    Route::get('/user/incidences', [IncidenceController::class, 'getUserIncidences']);
    Route::get('/tic/incidences', [IncidenceController::class, 'getTICIncidences']);
    Route::get('/incidences/id/{id}', [IncidenceController::class, 'getIncidenceById']);
    Route::get('/user/roles/{user}', [UserController::class, 'GetUserRoles']);
    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::get('/user/{user}', [UserController::class, 'getUser']);
    Route::get('/user/subject/{user}', [UserController::class, 'GetSubjectsTaught']);
    Route::put('/user/{user}', [UserController::class, 'updateUser']);
    Route::delete('/user/{user}', [UserController::class, 'deleteUser']);
    Route::post('/user/teacher', [UserController::class, 'createTeacher']);
    Route::put('/change-password', [ChangePasswordController::class, 'update']);
    Route::post('/uploadUserCSV', [UploadCSV::class, 'uploadUserCSV']);
    Route::post('/uploadStudentCSV', [UploadCSV::class, 'uploadStudentCSV']);
    Route::post('/uploadSubjectsUnitiesCSV', [UploadCSV::class, 'uploadSubjectUnityCSV']);
    Route::post('/review', [ReviewController::class, 'createReviews']);
    Route::get('/allUsersReviews', [ReviewController::class, 'getStudentReviewsInAStatusAtAStage']);
    Route::get('/review/{user}', [ReviewController::class, 'getUserReviews']);
    Route::get('/reviews', [ReviewController::class, 'getUserReviewsDirective']);
    Route::get('/allReviews', [ReviewController::class, 'getAllReviews']);
    Route::put('/update/reviews', [ReviewController::class, 'updateReview']);
    Route::get('/reviews/students', [ReviewController::class, 'getReviewsOfASubjectAtAStage']);
    Route::get('/{unity}', [UnityController::class, 'GetUnityStudent']);
    Route::get('/student/name/{student}', [StudentController::class, 'getStudentName']);
    Route::post('/create/student', [StudentController::class, 'createStudent']);

});