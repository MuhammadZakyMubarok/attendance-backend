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

Route::get('/test', function () {
    return response()->json([
        'ok' => true,
        'message' => 'API running and successful'
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/check-token', [AuthController::class, 'checkSessionToken']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/employee/fetch-data', [EmployeeController::class, 'index']);

    Route::post('/attendance/fetch-data', [AttendanceController::class, 'fetchData']);
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkIn']);
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkOut']);
    Route::post('/attendance/check-attendance', [AttendanceController::class, 'checkAttendance']);
    Route::post('/attendance/today-attendance', [AttendanceController::class, 'todayAttendance']);
    Route::post('/attendance/fetch-weekly-data', [AttendanceController::class, 'fetchWeeklyData']);

    Route::post('/leaving/fetch-data', [LeavingController::class, 'fetchData']);
    Route::post('/leaving/store', [LeavingController::class, 'store']);
    Route::post('/leaving/remaining-leave-balance', [LeavingController::class, 'getRemainingLeaveBalance']);
    Route::post('/leaving/form-number', [LeavingController::class, 'getFormNumber']);

    Route::post('/employee-permit/fetch-data', [EmployeePermitController::class, 'fetchData']);
    Route::post('/employee-permit/store', [EmployeePermitController::class, 'store']);
    Route::post('/employee-permit/form-number', [LeavingController::class, 'getFormNumber']);
});




