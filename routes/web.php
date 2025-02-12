<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthControllers;
use App\Http\Controllers\Api\User\UserControllers;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/register', [AuthControllers::class, 'index'])->name('register');
Route::post('/register', [AuthControllers::class, 'register']);

Route::get('/user', [UserControllers::class, 'index']);
Route::get('/presensi-masuk', [UserControllers::class, 'presensimasuk']);
Route::get('/presensi-keluar', [UserControllers::class, 'presensikeluar']);
