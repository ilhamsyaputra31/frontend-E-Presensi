<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthControllers;
use App\Http\Controllers\Api\SuperAdmin\SuperAdminControllers;
use App\Http\Controllers\Api\User\UserControllers;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/register', [AuthControllers::class, 'index'])->name('register');
Route::post('/register', [AuthControllers::class, 'register']);

Route::get('/user', [UserControllers::class, 'index']);
Route::get('/presensi-masuk', [UserControllers::class, 'presensimasuk']);
Route::get('/presensi-keluar', [UserControllers::class, 'presensikeluar']);
Route::get('/izin-cuti', [UserControllers::class, 'izincuti']);
Route::get('/History-Absen', [UserControllers::class, 'history']);

Route::get('/SuperAdmin', [SuperAdminControllers::class, 'index']);
Route::get('/SuperAdmin/ManajemenCabang', [SuperAdminControllers::class, 'ManajemenCabang']);
