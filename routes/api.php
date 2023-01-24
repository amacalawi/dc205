<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::controller(LikeController::class)->group(function () {
    Route::get('get-likes/{breed}', 'get_likes');
    Route::post('toggle-like', 'toggle_like');
}); 

Route::controller(UserController::class)->group(function () {
    Route::get('get-info/{id}', 'find');
    Route::put('update-info/{id}', 'update');
});