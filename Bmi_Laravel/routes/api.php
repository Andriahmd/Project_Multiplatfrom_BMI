<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BmiRecordController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InfogiziController;
use App\Models\BmiRecord;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::put('/user/update', [AuthController::class, 'updateUser'])->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Endpoint untuk hitung BMI dan BMR
Route::post('/bmi', [BmiRecordController::class, 'store'])->middleware('auth:sanctum');
Route::get('/bmi', [BmiRecordController::class, 'indexx'])->middleware('auth:sanctum'); // Tambahkan middleware
Route::get('/bmi/{id}', [BmiRecordController::class, 'show'])->middleware('auth:sanctum'); // Tambahkan middleware

// Route::post('/bmiii', [BmiRecordController::class, 'store'])->middleware('auth:sanctum');

// Route untuk melihat article 
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);
});


// Route untuk melihat Recommendation
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/recommendations', [RecommendationController::class, 'index']);
    Route::get('/recommendations/{id}', [RecommendationController::class, 'show']);
});

// Route untuk melihat Info gizi 
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/infogizi', [InfogiziController::class, 'index']);
    Route::get('/infogizi/{id}', [InfogiziController::class, 'show']);
});

// --- Route untuk Notifikasi Baru ---
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']); // Untuk menandai sudah dibaca
});