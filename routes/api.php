<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('/images', [ImageController::class, 'index']);
Route::get('/images/{id}', [ImageController::class, 'show']);
Route::post('/images', [ImageController::class, 'upload']);
Route::delete('/images/{id}', [ImageController::class, 'destroy']);
