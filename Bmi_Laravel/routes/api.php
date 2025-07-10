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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user()->toArray(); 
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/profile', [AuthController::class, 'updateProfile']); 
    Route::put('/user/password', [AuthController::class, 'changePassword']); 



// Endpoint untuk hitung BMI dan BMR
Route::post('/bmi', [BmiRecordController::class, 'store'])->middleware('auth:sanctum');
Route::get('/bmi', [BmiRecordController::class, 'indexx'])->middleware('auth:sanctum'); // Tambahkan middleware
Route::get('/bmi/{id}', [BmiRecordController::class, 'show'])->middleware('auth:sanctum'); // Tambahkan middleware
Route::delete('/bmi/{id}', [BmiRecordController::class, 'destroy'])->middleware('auth:sanctum');
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

// Rute Notifikasi (BARU)
Route::get('notifications', [NotificationController::class, 'index']);
Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount']);
Route::post('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
Route::delete('notifications/{id}', [NotificationController::class, 'destroy']); 

});