<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Services\GuruActivationCodeService;
use App\Services\GuruActivationExcelExporter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class AdminGuruActivationController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

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

        $jumlahBelumAktif = Guru::where('is_active', false)->count();

        return view('admin.guru-aktivasi.index', compact('gurus', 'jumlahBelumAktif', 'search'));
    }

    public function exportAll(
        GuruActivationCodeService $codes,
        GuruActivationExcelExporter $excel
    ) {
        return $this->export(
            Guru::where('is_active', false)->orderBy('nama_lengkap')->get(),
            $codes,
            $excel
        );
    }

    public function exportOne(
        Guru $guru,
        GuruActivationCodeService $codes,
        GuruActivationExcelExporter $excel
    ) {
        abort_if($guru->is_active, 404);

        return $this->export(collect([$guru]), $codes, $excel);
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
