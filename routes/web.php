<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');
Route::get('/register', [UserController::class, 'register'])->name('user-register-form');
Route::post('/register', [UserController::class, 'store_user'])->name('user-register-submit');

Route::get('/login', [UserController::class, 'login'])->name('user-login-form');
Route::post('/login', [UserController::class, 'submit_user_login'])->name('user-login-submit');
