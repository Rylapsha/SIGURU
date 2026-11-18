<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\Guru;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuruAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard('web');
        $user = $guard->user();

        if ($user instanceof Guru) {
            $guru = Guru::find($user->getAuthIdentifier());

            if (! $guru || ! $guru->is_active) {
                $guard->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'nip' => 'Akun guru sedang nonaktif. Silakan hubungi admin sekolah.',
                ]);
            }

            $guard->setUser($guru);

            return $next($request);
        }

        if ($user !== null) {
            abort(403, 'Akses khusus guru diperlukan.');
        }

        $adminId = $request->session()->get('admin_id');

        if (! $adminId) {
            return redirect()->route('login');
        }

        $admin = Admin::find($adminId);

        abort_unless(
            $admin && $admin->role === 'kurikulum',
            403,
            'Fitur mengajar hanya tersedia untuk Wakasek Kurikulum.'
        );

        $guru = $admin->guru()->first();

        abort_unless(
            $guru,
            403,
            'Data guru dengan NIP Wakasek Kurikulum belum tersedia.'
        );

        $request->session()->put([
            'admin_role' => $admin->role,
            'admin_display_name' => $admin->nama_tanpa_gelar,
        ]);
        $guard->setUser($guru);

        try {
            return $next($request);
        } finally {
            $guard->forgetUser();
        }
    }
}
