<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function index()
    {
        session()->forget('2fa_passed');
        Auth::logout();
        return redirect()->route("frontend.home");
    }
}
