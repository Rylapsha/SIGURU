<?php

namespace App\Http\Controllers;

use App\Models\Admin;

class AdminProfileController extends Controller
{
    public function index()
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        $admin = Admin::findOrFail(session('admin_id'));

        return view('admin.profil', compact('admin'));
    }
}
