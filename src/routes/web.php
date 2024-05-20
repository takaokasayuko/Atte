<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RestController;

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

Route::middleware('auth')->group(function () {
    Route::get('/', [AttendanceController::class, 'index']);
    Route::post('/work_start', [AttendanceController::class, 'store']);
    Route::post('/work_end', [AttendanceController::class, 'update']);

    Route::post('/rest_start', [RestController::class, 'store']);
    Route::post('/rest_end', [RestController::class, 'update']);
});