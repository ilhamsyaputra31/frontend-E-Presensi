<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AuthControllers extends Controller
{
    public function index()
    {
        return view('auth.register');
    }
}
