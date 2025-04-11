<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group( function () {
    Route::apiResource('/products',ProductController::class);
    Route::apiResource('/brands',BrandController::class);

    Route::get('/logout', [RegisterController::class, 'logout'])->name('logout');
});

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', action: 'register');
    Route::post('login', 'login');
});