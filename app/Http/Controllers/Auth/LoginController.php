<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Guru;
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
            if (! Hash::check($password, $admin->password)) {
                return back()->withErrors([
                    'nip' => 'Username atau password Admin salah.',
                ])->onlyInput('nip');
            }

            // Akun admin lama yang belum memiliki nilai role dianggap sebagai Kepala Sekolah.
            $adminRole = $admin->role ?? 'kepala_sekolah';

            if (! in_array($adminRole, Admin::ROLES, true)) {
                return back()->withErrors([
                    'nip' => 'Akun administrator tidak memiliki role yang valid.',
                ])->onlyInput('nip');
            }

            // Akhiri autentikasi Guru jika sebelumnya aktif
            if (Auth::check()) {
                Auth::logout();
            }

            $request->session()->forget([
                'admin_id',
                'admin_username',
                'admin_foto',
                'admin_role',
                'admin_display_name',
                'guru_activation_pending_id',
                'guru_activation_pending_fingerprint',
                'guru_activation_pending_expires_at',
                'guru_activation_completed_id',
                'guru_activation_completed_expires_at',
            ]);

            $request->session()->regenerate();

            $request->session()->put([
                'admin_id' => $admin->id,
                'admin_username' => $admin->username,
                'admin_foto' => $admin->foto,
                'admin_role' => $adminRole,
                'admin_display_name' => $admin->nama_tanpa_gelar,
            ]);

            return redirect()->route('admin.dashboard');
        }

        // 2. Login Guru menggunakan NIP dan password.
        $guru = Guru::where('nip', $username)->first();

        if ($guru && ! $guru->is_active) {
            return back()->withErrors([
                'nip' => 'Akun guru belum aktif. Silakan gunakan menu Aktivasi Akun.',
            ])->onlyInput('nip');
        }

        if ($guru && $guru->is_active && Hash::check($password, $guru->password)) {
            if (Auth::check()) {
                Auth::logout();
            }

            $request->session()->forget([
                'admin_id',
                'admin_username',
                'admin_foto',
                'admin_role',
                'admin_display_name',
                'guru_activation_pending_id',
                'guru_activation_pending_fingerprint',
                'guru_activation_pending_expires_at',
                'guru_activation_completed_id',
                'guru_activation_completed_expires_at',
            ]);

            Auth::login($guru, $request->boolean('remember'));
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
            'admin_foto',
            'admin_role',
            'admin_display_name',
            'guru_activation_pending_id',
            'guru_activation_pending_fingerprint',
            'guru_activation_pending_expires_at',
            'guru_activation_completed_id',
            'guru_activation_completed_expires_at',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
