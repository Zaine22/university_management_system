<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->group(function () {
    Route::get('auth/google', [GoogleAuthController::class, 'redirect']);
    Route::get('auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google-auth');
    Route::post('/google-register', [GoogleAuthController::class, 'frontendGoogle']);

});

// public frontend routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::resource('/events', EventController::class);
Route::resource('/courses', CourseController::class);
Route::resource('/batches', BatchController::class);
Route::get('/certificates', [AchievementController::class, 'index']);
Route::resource('/timetables', TimetableController::class);
Route::resource('/results', ResultController::class);

// password
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword'])
    ->middleware('guest')->name('password.email');
Route::post('/token-checking', [PasswordResetController::class, 'tokenCheck'])
    ->middleware('guest')->name('token.check');
Route::post('/password-reset', [PasswordResetController::class, 'passwordReset'])
    ->middleware('guest')->name('passwordReset');
Route::post('/reset-password/{token}', [PasswordResetController::class, 'passwordResetLaravel'])->middleware('guest')->name('password.reset');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/reset-password', [PasswordResetController::class, 'resetPasswordFrontend']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [StudentController::class, 'index']);
    Route::get('/user/register', [StudentController::class, 'create']);
    Route::patch('/user/profile/photo', [StudentController::class, 'updatePhoto']);
    Route::post('/user/register', [StudentController::class, 'store']);
    Route::patch('/user/update', [StudentController::class, 'update']);
    Route::patch('/user/password/update', [AuthController::class, 'update']);
    Route::patch('/user/{transaction:transactionID}/update', [TransactionController::class, 'update']);
    Route::post('enrollment/{batch:batch_slug}', [EnrollmentController::class, 'enroll']);

});

// Route::get('/broadcast', function () {

//     event(new EnrollmentCreated(User::first(), Enrollment::find(1)));

//     event(new PublicNoti(new EventApiResource(Event::find(1))));

//     event(new TransactionApproved(User::first(), Transaction::find(1)));
// });