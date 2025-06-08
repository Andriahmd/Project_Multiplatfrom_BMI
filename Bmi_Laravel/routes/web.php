<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BmiRecordController;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/bmi-records', [BmiRecordController::class, 'index'])->name('bmi_records.index');
    Route::get('/bmi-records/create', [BmiRecordController::class, 'create'])->name('bmi_records.create');
    Route::post('/bmi-records', [BmiRecordController::class, 'store'])->name('bmi_records.store');
    Route::get('/bmi-records/{id}', [BmiRecordController::class, 'show'])->name('bmi_records.show');
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{id}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy');
});

Route::get('/', function () {
    return view('welcome');
});
