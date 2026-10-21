<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nip' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $username = $credentials['nip'];
        $password = $credentials['password'];

        // 1. Periksa akun Admin
        $admin = Admin::where('username', $username)->first();

        if ($admin) {
            if (!Hash::check($password, $admin->password)) {
                return back()->withErrors([
                    'nip' => 'Username atau password Admin salah.',
                ])->onlyInput('nip');
            }

            // Akhiri autentikasi Guru jika sebelumnya aktif
            if (Auth::check()) {
                Auth::logout();
            }

            $request->session()->forget([
                'admin_id',
                'admin_username',
            ]);

            $request->session()->regenerate();

            $request->session()->put([
                'admin_id' => $admin->id,
                'admin_username' => $admin->username,
            ]);

            return redirect()->route('admin.dashboard');
        }

        // 2. Login Guru menggunakan NIP dan password
        if (Auth::attempt([
            'nip' => $username,
            'password' => $password,
        ], $request->boolean('remember'))) {

            $request->session()->forget([
                'admin_id',
                'admin_username',
            ]);

            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'nip' => 'NIP atau password yang dimasukkan salah.',
        ])->onlyInput('nip');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }

        $request->session()->forget([
            'admin_id',
            'admin_username',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
