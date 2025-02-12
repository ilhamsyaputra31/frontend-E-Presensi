<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserControllers extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('DashboardUser.index');
    }
    public function presensimasuk()
    {
        return view('DashboardUser.Presensi-in');
    }
    public function presensikeluar()
    {
        return view('DashboardUser.Presensi-Out');
    }
}
