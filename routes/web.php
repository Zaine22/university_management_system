<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\UserController;
use App\Modules\Achievement\Http\Controller\AchievementController;
use App\Modules\Invoice\Http\Controller\InvoiceController;
use App\Modules\Timetable\Http\Controller\TimetableController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::view('/enrollment', 'mail.enrollmentnoti');
Route::view('/excel', 'exceltest');
Route::post('excel_import', [UserController::class, 'hello']);
Route::view('/payment', 'mail.paymentnoti');
Route::view('/transaction', 'mail.transactionnoti');
Route::view('/event', 'mail.eventnoti');
Route::view('/certificate1', 'certificate.1-year-it-certificate');
Route::view('/certificate3', 'certificate.2-year-it-certificate');
Route::view('/certificate4', 'certificate.foundation-IT-Diploma');
Route::view('/certificate5', 'certificate.Java-certificate');
Route::get('/certificate6', [AchievementController::class, 'testPrintCertificate']);
Route::view('/event', 'mail.eventnoti');
Route::get('timetable', [TimetableController::class, 'timetable']);
Route::get('/invoice', [InvoiceController::class, 'invoice']);

Route::group(['middleware' => ['web']], function () {
    Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
    Route::get('auth/google/callback', [GoogleAuthController::class, 'callbackGoogle'])->name('google-auth-callback');
});
