<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class GuruProfileController extends Controller
{
    public function index()
    {
        $guru = Auth::user();

        return view('guru.profileguru', compact('guru'));
    }
}
