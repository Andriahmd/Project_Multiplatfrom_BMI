<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/bmi-records', [BmiRecordController::class, 'index']);
//     Route::post('/bmi-records', [BmiRecordController::class, 'store']);
//     Route::get('/articles', [ArticleController::class, 'index']);
//     Route::post('/articles', [ArticleController::class, 'store']);
//     Route::get('/recommendations', [RecommendationController::class, 'index']);
//     Route::post('/recommendations', [RecommendationController::class, 'store']);
//     Route::get('/user-profiles', [UserProfileController::class, 'index']);
//     Route::post('/user-profiles', [UserProfileController::class, 'store']);
// });

