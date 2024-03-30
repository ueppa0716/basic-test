<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

class AuthenticatedSessionController extends Controller
{
    public function store()
    {
        return view('auth.login');
    }
}
