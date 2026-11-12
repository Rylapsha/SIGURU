<?php

namespace App\Http\Controllers;

use App\Models\AgendaKegiatan;
use App\Models\Dokumen;
use App\Models\Guru;
use App\Models\Supervisi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $guru = $request->user();
        abort_unless($guru instanceof Guru, 403, 'Akun guru tidak ditemukan.');

        $waktuJakarta = Carbon::now('Asia/Jakarta');
        $tahunAjaranAktif = $this->tahunAjaranAktif($waktuJakarta);
        $semesterAktif = $waktuJakarta->month >= 7 ? '1' : '2';

        $dokumenTahunAjaran = Dokumen::where('guru_id', $guru->id)
            ->where('tahun_ajaran', $tahunAjaranAktif)
            ->get(['jenis_dokumen', 'semester']);

        $jenisDokumen = $dokumenTahunAjaran
            ->filter(fn ($dokumen) => (string) $dokumen->semester === $semesterAktif)
            ->pluck('jenis_dokumen')
            ->map(fn (string $jenis) => mb_strtoupper(trim($jenis)));
        $jenisDokumenTahunan = $dokumenTahunAjaran
            ->pluck('jenis_dokumen')
            ->map(fn(string $jenis) => mb_strtoupper(trim($jenis)));

        $kategoriDokumen = [
            'Modul Ajar' => $jenisDokumen->contains('MODUL AJAR'),
            'TP/ATP' => $jenisDokumen->contains(
                fn(string $jenis) => in_array($jenis, [
                    'ATP',
                    'AP',
                    'TP',
                    'TP/ATP',
                    'ALUR PEMBELAJARAN',
                    'TUJUAN PEMBELAJARAN',
                ], true)
            ),
            'Prota/Prosem' => (
                $jenisDokumenTahunan->contains('PROTA')
                && $jenisDokumen->contains('PROSEM')
            ) || $jenisDokumen->contains('PROTA & PROSEM'),
            'Bank Soal' => collect([
                'KISI-KISI',
                'NASKAH SOAL',
                'ANALISIS BUTIR SOAL',
            ])->every(fn(string $jenis) => $jenisDokumen->contains($jenis)),
        ];

        $jumlahKategoriLengkap = collect($kategoriDokumen)
            ->filter()
            ->count();
        $totalKategoriDokumen = count($kategoriDokumen);
        $jumlahKategoriBelumLengkap = $totalKategoriDokumen - $jumlahKategoriLengkap;
        $persentaseKelengkapan = $jumlahKategoriLengkap * 25;

        $agendaSupervisi = Supervisi::where('guru_id', $guru->id)
            ->where('status', 'terjadwal')
            ->whereDate('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->get()
            ->map(function ($item) {
                $item->jenis = 'supervisi';
                return $item;
            });

        $agendaKegiatan = AgendaKegiatan::query()
            ->where('status', '!=', 'dibatalkan')
            ->where('status', '!=', 'selesai')
            ->whereDate('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->get()
            ->map(function ($item) {
                $item->jenis = 'kegiatan';
                return $item;
            });

        $agendaMendatang = $agendaSupervisi
            ->merge($agendaKegiatan)
            ->sortBy(function ($item) {
                return \Carbon\Carbon::parse($item->tanggal)->format('Ymd') . ($item->jam ?? '23:59');
            })
            ->values();

        return view('dashboard', compact(
            'kategoriDokumen',
            'jumlahKategoriLengkap',
            'jumlahKategoriBelumLengkap',
            'totalKategoriDokumen',
            'persentaseKelengkapan',
            'semesterAktif',
            'tahunAjaranAktif',
            'agendaMendatang'
        ));
    }

    private function tahunAjaranAktif(Carbon $waktuJakarta): string
    {
        $tahun = $waktuJakarta->year;
        $tahunMulai = $waktuJakarta->month >= 7 ? $tahun : $tahun - 1;

        return $tahunMulai . '/' . ($tahunMulai + 1);
    }
}
