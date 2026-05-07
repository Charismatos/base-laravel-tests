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

Route::get('/edit', [UserController::class, 'edit'])->name('user-edit-form');
Route::patch('/edit', [UserController::class, 'submit_edited_user'])->name('user-edit-submit');

Route::get('/delete', [UserController::class, 'delete'])->name('user-delete-form');
Route::delete('/delete', [UserController::class, 'submit_to_delete_user'])->name('user-delete-submit');
