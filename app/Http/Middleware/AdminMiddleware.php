<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('admin_id')) {
            if (Auth::guard('web')->check()) {
                abort(403, 'Akses administrator diperlukan.');
            }

            return redirect()->route('login');
        }

        $role = $request->session()->get('admin_role');

        if ($role !== null && ! in_array($role, Admin::ROLES, true)) {
            abort(403, 'Role administrator tidak memiliki akses.');
        }

        if (! $request->session()->has('admin_display_name')) {
            $admin = Admin::find($request->session()->get('admin_id'));

            if ($admin) {
                $request->session()->put('admin_display_name', $admin->nama_tanpa_gelar);
            }
        }

        return $next($request);
    }
}
