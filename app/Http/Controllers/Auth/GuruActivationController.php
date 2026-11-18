<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\GuruActivation;
use App\Services\GuruActivationCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruActivationController extends Controller
{
    private const PENDING_MINUTES = 10;

    private const COMPLETION_NOTICE_MINUTES = 5;

    public function create()
    {
        return view('auth.aktivasi-guru', ['step' => 'verify']);
    }

    public function store(Request $request, GuruActivationCodeService $codes)
    {
        $this->forgetPendingSession($request);
        $this->forgetCompletionNotice($request);

        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:30'],
            'kode_aktivasi' => ['required', 'string', 'size:5', 'regex:/^[A-Za-z0-9]{5}$/'],
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'kode_aktivasi.required' => 'Kode aktivasi wajib diisi.',
            'kode_aktivasi.size' => 'Kode aktivasi harus terdiri dari tepat 5 huruf atau angka.',
            'kode_aktivasi.regex' => 'Kode aktivasi hanya boleh berisi huruf dan angka.',
        ]);

        $nip = trim($validated['nip']);
        $providedCode = $codes->normalize($validated['kode_aktivasi']);

        $result = DB::transaction(function () use ($nip, $providedCode, $codes) {
            $adminAccount = Admin::query()
                ->where('nip', $nip)
                ->orWhere('username', $nip)
                ->exists();

            if ($adminAccount) {
                return ['status' => 'admin'];
            }

            $guru = Guru::where('nip', $nip)->lockForUpdate()->first();

            if (! $guru || $guru->is_active) {
                return ['status' => 'invalid'];
            }

            $activation = GuruActivation::where('guru_id', $guru->id)
                ->lockForUpdate()
                ->first();

            if (! $activation) {
                return ['status' => 'invalid'];
            }

            if ($activation->expires_at->isPast()) {
                $activation->delete();

                return ['status' => 'expired'];
            }

            if ($activation->attempts >= GuruActivationCodeService::MAX_ATTEMPTS) {
                $activation->delete();

                return ['status' => 'locked'];
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

                    return ['status' => 'locked'];
                }

                $activation->save();

                return ['status' => 'invalid'];
            }

            return [
                'status' => 'verified',
                'guru_id' => $guru->id,
                'fingerprint' => $activation->code_fingerprint,
            ];
        });

        if ($result['status'] !== 'verified') {
            $message = match ($result['status']) {
                'expired' => 'Kode aktivasi sudah kedaluwarsa. Silakan minta admin membuat kode baru.',
                'locked' => 'Batas percobaan tercapai. Silakan minta admin membuat kode aktivasi baru.',
                'admin' => 'Akun administrator sudah aktif. Silakan masuk melalui halaman login.',
                default => 'NIP atau kode aktivasi salah. Silakan masukkan kode aktivasi yang benar.',
            };

            return back()
                ->withErrors(['kode_aktivasi' => $message])
                ->withInput(['nip' => $nip]);
        }

        $request->session()->forget('errors');
        $request->session()->regenerate();
        $request->session()->put([
            'guru_activation_pending_id' => $result['guru_id'],
            'guru_activation_pending_fingerprint' => $result['fingerprint'],
            'guru_activation_pending_expires_at' => now()->addMinutes(self::PENDING_MINUTES)->timestamp,
        ]);

        return redirect()->route('guru.activation.password');
    }

    public function password(Request $request)
    {
        $pending = $this->pendingSession($request);

        if ($pending === null) {
            if ($this->hasRecentCompletion($request)) {
                return $this->completionRedirect();
            }

            return $this->returnToVerification($request, 'Sesi verifikasi berakhir. Silakan masukkan NIP dan kode aktivasi kembali.');
        }

        $guru = Guru::with('activation')->find($pending['guru_id']);
        $activation = $guru?->activation;

        if ($guru?->is_active) {
            $this->rememberCompletion($request, $guru->id);

            return $this->completionRedirect();
        }

        if (
            ! $guru || $guru->is_active || ! $activation
            || ! hash_equals($activation->code_fingerprint, $pending['fingerprint'])
        ) {
            return $this->returnToVerification($request, 'Kode aktivasi sudah tidak berlaku. Silakan minta admin membuat kode baru.');
        }

        if ($activation->expires_at->isPast()) {
            $activation->delete();

            return $this->returnToVerification($request, 'Kode aktivasi sudah kedaluwarsa. Silakan minta admin membuat kode baru.');
        }

        return view('auth.aktivasi-guru', [
            'step' => 'password',
            'guru' => $guru,
        ]);
    }

    public function complete(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $pending = $this->pendingSession($request);

        if ($pending === null) {
            if ($this->hasRecentCompletion($request)) {
                return $this->completionRedirect();
            }

            return $this->returnToVerification($request, 'Sesi verifikasi berakhir. Silakan masukkan NIP dan kode aktivasi kembali.');
        }

        $result = DB::transaction(function () use ($pending, $validated) {
            $guru = Guru::whereKey($pending['guru_id'])->lockForUpdate()->first();

            if (! $guru) {
                return 'invalid';
            }

            if ($guru->is_active) {
                return 'already-activated';
            }

            $activation = GuruActivation::where('guru_id', $guru->id)
                ->lockForUpdate()
                ->first();

            if (! $activation || ! hash_equals($activation->code_fingerprint, $pending['fingerprint'])) {
                return 'invalid';
            }

            if ($activation->expires_at->isPast()) {
                $activation->delete();

                return 'expired';
            }

            $guru->forceFill([
                'password' => Hash::make($validated['password']),
                'is_active' => true,
            ])->save();

            $activation->delete();

            return 'activated';
        });

        if ($result === 'already-activated') {
            $this->rememberCompletion($request, $pending['guru_id']);

            return $this->completionRedirect();
        }

        if ($result !== 'activated') {
            $message = $result === 'expired'
                ? 'Kode aktivasi sudah kedaluwarsa. Silakan minta admin membuat kode baru.'
                : 'Kode aktivasi sudah tidak berlaku. Silakan mulai aktivasi kembali.';

            return $this->returnToVerification($request, $message);
        }

        $this->rememberCompletion($request, $pending['guru_id']);

        return $this->completionRedirect();
    }

    private function pendingSession(Request $request): ?array
    {
        $guruId = $request->session()->get('guru_activation_pending_id');
        $fingerprint = $request->session()->get('guru_activation_pending_fingerprint');
        $expiresAt = (int) $request->session()->get('guru_activation_pending_expires_at', 0);

        if (! $guruId || ! is_string($fingerprint) || $fingerprint === '' || $expiresAt <= now()->timestamp) {
            return null;
        }

        return [
            'guru_id' => (int) $guruId,
            'fingerprint' => $fingerprint,
        ];
    }

    private function forgetPendingSession(Request $request): void
    {
        $request->session()->forget([
            'guru_activation_pending_id',
            'guru_activation_pending_fingerprint',
            'guru_activation_pending_expires_at',
        ]);
    }

    private function forgetCompletionNotice(Request $request): void
    {
        $request->session()->forget([
            'guru_activation_completed_id',
            'guru_activation_completed_expires_at',
        ]);
    }

    private function rememberCompletion(Request $request, int $guruId): void
    {
        $this->forgetPendingSession($request);
        $this->forgetCompletionNotice($request);
        $request->session()->forget('errors');
        $request->session()->put([
            'guru_activation_completed_id' => $guruId,
            'guru_activation_completed_expires_at' => now()->addMinutes(self::COMPLETION_NOTICE_MINUTES)->timestamp,
        ]);
    }

    private function hasRecentCompletion(Request $request): bool
    {
        $guruId = $request->session()->get('guru_activation_completed_id');
        $expiresAt = (int) $request->session()->get('guru_activation_completed_expires_at', 0);

        return $guruId !== null
            && $expiresAt > now()->timestamp
            && Guru::whereKey($guruId)->where('is_active', true)->exists();
    }

    private function completionRedirect()
    {
        return redirect()
            ->route('login')
            ->with('activation_success', 'Akun berhasil diaktifkan. Silakan masuk dengan NIP dan password baru.');
    }

    private function returnToVerification(Request $request, string $message)
    {
        $this->forgetPendingSession($request);

        return redirect()
            ->route('guru.activation.create')
            ->withErrors(['kode_aktivasi' => $message]);
    }
}
