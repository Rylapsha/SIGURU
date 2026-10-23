<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Guru;
use App\Models\Supervisi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $guru = $request->user();
        abort_unless($guru instanceof Guru, 403, 'Akun guru tidak ditemukan.');

        $validated = $request->validate([
            'semester' => ['nullable', Rule::in(['1', '2'])],
            'tahun_ajaran' => [
                'nullable',
                'string',
                'regex:/^\d{4}\/\d{4}$/',
                function ($attribute, $value, $fail) {
                    [$tahunMulai, $tahunSelesai] = explode('/', $value);

                    if ((int) $tahunSelesai !== (int) $tahunMulai + 1) {
                        $fail('Tahun ajaran harus terdiri dari dua tahun berurutan.');
                    }
                },
            ],
        ]);

        $tahunAjaranAktif = $this->tahunAjaranAktif();
        $semesterTerpilih = $validated['semester']
            ?? (now()->month >= 7 ? '1' : '2');
        $tahunAjaranTerpilih = $validated['tahun_ajaran'] ?? $tahunAjaranAktif;

        $jenisDokumen = Dokumen::where('guru_id', $guru->id)
            ->where('semester', $semesterTerpilih)
            ->where('tahun_ajaran', $tahunAjaranTerpilih)
            ->pluck('jenis_dokumen')
            ->map(fn (string $jenis) => mb_strtoupper(trim($jenis)));

        $kategoriDokumen = [
            'Modul Ajar' => $jenisDokumen->contains('MODUL AJAR'),
            'TP/ATP' => $jenisDokumen->contains(
                fn (string $jenis) => in_array($jenis, [
                    'ATP',
                    'AP',
                    'TP',
                    'TP/ATP',
                    'ALUR PEMBELAJARAN',
                    'TUJUAN PEMBELAJARAN',
                ], true)
            ),
            'Prota/Prosem' => (
                $jenisDokumen->contains('PROTA')
                && $jenisDokumen->contains('PROSEM')
            ) || $jenisDokumen->contains('PROTA & PROSEM'),
            'Bank Soal' => collect([
                'KISI-KISI',
                'NASKAH SOAL',
                'ANALISIS BUTIR SOAL',
            ])->every(fn (string $jenis) => $jenisDokumen->contains($jenis)),
        ];

        $jumlahKategoriLengkap = collect($kategoriDokumen)
            ->filter()
            ->count();
        $persentaseKelengkapan = $jumlahKategoriLengkap * 25;

        $tahunAjaranOptions = Dokumen::where('guru_id', $guru->id)
            ->whereNotNull('tahun_ajaran')
            ->distinct()
            ->orderByDesc('tahun_ajaran')
            ->pluck('tahun_ajaran')
            ->push($tahunAjaranAktif, $tahunAjaranTerpilih)
            ->unique()
            ->sortDesc()
            ->values();

        $agendaMendatang = Supervisi::where('guru_id', $guru->id)
            ->where('status', 'terjadwal')
            ->whereDate('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'kategoriDokumen',
            'jumlahKategoriLengkap',
            'persentaseKelengkapan',
            'semesterTerpilih',
            'tahunAjaranTerpilih',
            'tahunAjaranOptions',
            'agendaMendatang'
        ));
    }

    private function tahunAjaranAktif(): string
    {
        $tahun = now()->year;
        $tahunMulai = now()->month >= 7 ? $tahun : $tahun - 1;

        return $tahunMulai . '/' . ($tahunMulai + 1);
    }
}
