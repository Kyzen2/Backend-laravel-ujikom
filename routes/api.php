<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Api\AuthController;

Route::get('/', function () {
    return response()->json([
        'message' => 'API is running',
        'version' => '1.0'
    ]);
});

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Attendance routes
    Route::post('/attendance/session', [AttendanceController::class, 'createSesi']);
    Route::post('/attendance/scan', [AttendanceController::class, 'scanQR']);
    Route::get('/attendance/history', [AttendanceController::class, 'historySiswa']);
});

// // Test route (optional, untuk cek API berjalan)
// Route::get('/', function () {
//     return response()->json([
//         'message' => 'API is running',
//         'version' => '1.0'
//     ]);
// });

// // Attendance routes (REQUIRED)
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/attendance/session', [AttendanceController::class, 'createSesi']);
//     Route::post('/attendance/scan', [AttendanceController::class, 'scanQR']);
//     Route::get('/attendance/history', [AttendanceController::class, 'historySiswa']);
// });
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/attendance/scan', [AttendanceController::class, 'scanQR']);
// });

// Route::post('/attendance/session', [AttendanceController::class, 'createSesi']);
// Route::post('/attendance/scan', [AttendanceController::class, 'scanQR']);
// Route::get('/attendance/history', [AttendanceController::class, 'historySiswa']);
