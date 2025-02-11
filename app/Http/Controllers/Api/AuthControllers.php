<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AuthControllers extends Controller
{
    public function index()
    {
        return view('auth.register'); // Pastikan file ini ada di resources/views/auth/register.blade.php
    }

    // public function register(Request $request)
    // {
    //     $response = Http::post('http://127.0.0.1:8000/api/register', [
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => $request->password,
    //         'password_confirmation' => $request->password_confirmation,
    //     ]);

    //     if ($response->failed()) {
    //         return back()->with('error', 'Registrasi gagal! Periksa kembali data Anda.');
    //     }

    //     return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    // }
}
