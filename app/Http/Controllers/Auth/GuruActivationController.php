<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\GuruActivation;
use App\Services\GuruActivationCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruActivationController extends Controller
{
    public function create()
    {
        return view('auth.aktivasi-guru');
    }

    public function store(Request $request, GuruActivationCodeService $codes)
    {
        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:30'],
            'kode_aktivasi' => ['required', 'string', 'size:5', 'regex:/^[A-Za-z0-9]{5}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'kode_aktivasi.required' => 'Kode aktivasi wajib diisi.',
            'kode_aktivasi.size' => 'Kode aktivasi harus terdiri dari tepat 5 huruf atau angka.',
            'kode_aktivasi.regex' => 'Kode aktivasi hanya boleh berisi huruf dan angka.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $nip = trim($validated['nip']);
        $providedCode = $codes->normalize($validated['kode_aktivasi']);

        $result = DB::transaction(function () use ($nip, $providedCode, $validated, $codes) {
            $guru = Guru::where('nip', $nip)->lockForUpdate()->first();

            if (! $guru || $guru->is_active) {
                return 'invalid';
            }

            $activation = GuruActivation::where('guru_id', $guru->id)
                ->lockForUpdate()
                ->first();

            if (! $activation) {
                return 'invalid';
            }

            if ($activation->expires_at->isPast()) {
                $activation->delete();

                return 'expired';
            }

            if ($activation->attempts >= GuruActivationCodeService::MAX_ATTEMPTS) {
                $activation->delete();

                return 'locked';
            }

            $fingerprintMatches = hash_equals(
                $activation->code_fingerprint,
                $codes->fingerprint($providedCode)
            );
            $hashMatches = $fingerprintMatches && Hash::check(
                $providedCode,
                $activation->code_hash
            );

            if (! $hashMatches) {
                $activation->attempts++;

                if ($activation->attempts >= GuruActivationCodeService::MAX_ATTEMPTS) {
                    $activation->delete();

                    return 'locked';
                }

                $activation->save();

                return 'invalid';
            }

            $guru->forceFill([
                'password' => Hash::make($validated['password']),
                'is_active' => true,
            ])->save();

            $activation->delete();

            return 'activated';
        });

        if ($result !== 'activated') {
            $message = match ($result) {
                'expired' => 'Kode aktivasi sudah kedaluwarsa. Silakan minta admin membuat kode baru.',
                'locked' => 'Batas percobaan tercapai. Silakan minta admin membuat kode aktivasi baru.',
                default => 'NIP atau kode aktivasi tidak valid, atau akun sudah aktif.',
            };

            return back()
                ->withErrors(['kode_aktivasi' => $message])
                ->withInput(['nip' => $nip]);
        }

        return redirect()
            ->route('login')
            ->with('activation_success', 'Akun berhasil diaktifkan. Silakan masuk dengan NIP dan password baru.');
    }
}
