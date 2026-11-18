<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Guru;
use App\Models\GuruActivation;
use App\Services\GuruActivationCodeService;
use App\Services\GuruActivationExcelExporter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class AdminGuruActivationController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $adminRoleByNip = $this->adminRoleByNip();

        $guruQuery = Guru::with('activation');

        if ($search !== '') {
            $guruQuery->where(function ($query) use ($search) {
                $query->where('nama_lengkap', 'like', '%'.$search.'%')
                    ->orWhere('nip', 'like', '%'.$search.'%');
            });
        }

        $gurus = $guruQuery
            ->orderBy('nama_lengkap')
            ->paginate(5)
            ->withQueryString();

        $pendingQuery = Guru::where('is_active', false);

        if ($adminRoleByNip !== []) {
            $pendingQuery->whereNotIn('nip', array_keys($adminRoleByNip));
        }

        $jumlahBelumAktif = $pendingQuery->count();

        return view('admin.guru-aktivasi.index', compact(
            'gurus',
            'jumlahBelumAktif',
            'search',
            'adminRoleByNip'
        ));
    }

    public function exportAll(
        GuruActivationCodeService $codes,
        GuruActivationExcelExporter $excel
    ) {
        $adminRoleByNip = $this->adminRoleByNip();
        $pendingQuery = Guru::where('is_active', false);

        if ($adminRoleByNip !== []) {
            $pendingQuery->whereNotIn('nip', array_keys($adminRoleByNip));
        }

        return $this->export(
            $pendingQuery->orderBy('nama_lengkap')->get(),
            $codes,
            $excel
        );
    }

    public function exportOne(
        Guru $guru,
        GuruActivationCodeService $codes,
        GuruActivationExcelExporter $excel
    ) {
        abort_if($guru->is_active || $this->adminRoleForGuru($guru), 404);

        return $this->export(collect([$guru]), $codes, $excel);
    }

    public function deactivate(Guru $guru)
    {
        abort_if($this->adminRoleForGuru($guru), 404);

        if (! $guru->is_active) {
            return back()->with('error', 'Akun guru tersebut sudah nonaktif.');
        }

        DB::transaction(function () use ($guru) {
            $lockedGuru = Guru::whereKey($guru->id)->lockForUpdate()->firstOrFail();

            if ($this->adminRoleForGuru($lockedGuru)) {
                abort(404);
            }

            $lockedGuru->forceFill([
                'is_active' => false,
                'remember_token' => Str::random(60),
            ])->save();

            GuruActivation::where('guru_id', $lockedGuru->id)->delete();
        });

        return back()->with('success', 'Akun guru berhasil dinonaktifkan. Data guru tetap tersimpan.');
    }

    private function adminRoleByNip(): array
    {
        $roles = [];

        foreach (Admin::query()->get(['nip', 'username', 'role']) as $admin) {
            foreach ([$admin->nip, $admin->username] as $identity) {
                $identity = trim((string) $identity);

                if ($identity !== '' && ctype_digit($identity)) {
                    $roles[$identity] = $admin->role;
                }
            }
        }

        return $roles;
    }

    private function adminRoleForGuru(Guru $guru): ?string
    {
        return Admin::query()
            ->where('nip', $guru->nip)
            ->orWhere('username', $guru->nip)
            ->value('role');
    }

    private function export(
        Collection $gurus,
        GuruActivationCodeService $codes,
        GuruActivationExcelExporter $excel
    ) {
        if ($gurus->isEmpty()) {
            return back()->with('error', 'Tidak ada akun guru yang menunggu aktivasi.');
        }

        try {
            $workbook = DB::transaction(function () use ($gurus, $codes, $excel) {
                $rows = [];

                foreach ($gurus as $candidate) {
                    $guru = Guru::whereKey($candidate->id)->lockForUpdate()->first();

                    if (! $guru || $guru->is_active) {
                        continue;
                    }

                    $rows[] = [
                        'nip' => $guru->nip,
                        'nama_lengkap' => $guru->nama_lengkap,
                        'kode_aktivasi' => $codes->issue($guru, (int) session('admin_id')),
                    ];
                }

                return $rows === [] ? null : $excel->make($rows);
            });
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', 'Kode aktivasi gagal dibuat. Silakan coba kembali.');
        }

        if ($workbook === null) {
            return back()->with('error', 'Tidak ada akun guru yang menunggu aktivasi.');
        }

        $filename = $gurus->count() === 1
            ? 'kode-aktivasi-'.$gurus->first()->nip.'.xlsx'
            : 'kode-aktivasi-guru-'.now()->format('Ymd-His').'.xlsx';

        return response($workbook, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
