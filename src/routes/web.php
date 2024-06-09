<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RestController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::middleware('verified')->
//     middleware('auth')->group(function () {
//     Route::get('/', [AttendanceController::class, 'index']);
//     Route::post('/work_start', [AttendanceController::class, 'store']);
//     Route::post('/work_end', [AttendanceController::class, 'update']);

//     Route::post('/rest_start', [RestController::class, 'store']);
//     Route::post('/rest_end', [RestController::class, 'update']);

//     Route::get('/attendance', [AttendanceController::class, 'attendance'])-> name('attendance');
// });

// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();

//     return redirect('/');
// })->middleware(['auth', 'signed'])->name('verification.verify');

Route::middleware(['verified'])->group(function () {
    Route::get('/', [AttendanceController::class, 'index']);
    Route::post('/work_start', [AttendanceController::class, 'store']);
    Route::post('/work_end', [AttendanceController::class, 'update']);

    Route::post('/rest_start', [RestController::class, 'store']);
    Route::post('/rest_end', [RestController::class, 'update']);

    Route::get('/attendance', [AttendanceController::class, 'attendance'])->name('attendance');
});

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');




