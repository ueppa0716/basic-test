<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceManagementController extends Controller
{
    public function index(){
        return view('index');
    }
}
