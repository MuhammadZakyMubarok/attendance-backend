<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeavingController;
use App\Http\Controllers\EmployeePermitController;
use App\Http\Controllers\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/check-token', [AuthController::class, 'checkSessionToken']);

Route::get('/test', function () {
    return response()->json([
        'ok' => true,
        'message' => 'API running and successful'
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/employee/fetch-data', [EmployeeController::class, 'index']);

    Route::get('/attendance/fetch-data', [AttendanceController::class, 'index']);
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkIn']);
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkOut']);

    Route::get('/leaving/fetch-data', [LeavingController::class, 'index']);
    Route::post('/leaving/store', [LeavingController::class, 'store']);

    Route::get('/employee-permit/fetch-data', [EmployeePermitController::class, 'index']);
    Route::post('/employee-permit/store', [EmployeePermitController::class, 'store']);
});

Route::post('/attendance/check-attendance', [AttendanceController::class, 'checkAttendance']);


