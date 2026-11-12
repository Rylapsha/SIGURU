<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Supervisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupervisiController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user();

        abort_unless($guru instanceof Guru, 403, 'Akun guru tidak ditemukan.');

        $filters = $request->validate([
            'cari' => ['nullable', 'string', 'max:150'],
            'mata_pelajaran' => ['nullable', 'string', 'max:150'],
            'semester' => ['nullable', 'string', 'max:50'],
            'tanggal_mulai' => ['nullable', 'date_format:Y-m-d'],
            'tanggal_akhir' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $query = Supervisi::where('guru_id', $guru->id);

        if (!empty($filters['cari'])) {
            $kataKunci = trim($filters['cari']);
            $query->where(function ($subQuery) use ($kataKunci) {
                $subQuery->where('mata_pelajaran', 'like', '%' . $kataKunci . '%')
                    ->orWhere('nama_supervisor', 'like', '%' . $kataKunci . '%');
            });
        }

        if (!empty($filters['mata_pelajaran'])) {
            $query->where('mata_pelajaran', $filters['mata_pelajaran']);
        }

        if (!empty($filters['semester'])) {
            $query->where('semester', $filters['semester']);
        }

        if (!empty($filters['tanggal_mulai'])) {
            $query->whereDate('tanggal', '>=', $filters['tanggal_mulai']);
        }

        if (!empty($filters['tanggal_akhir'])) {
            $query->whereDate('tanggal', '<=', $filters['tanggal_akhir']);
        }

        $supervisis = (clone $query)
            ->orderByDesc('tanggal')
            ->orderByDesc('jam')
            ->paginate(5)
            ->withQueryString();

        $today = now()->startOfDay();

        $jadwalBerikutnya = Supervisi::where('guru_id', $guru->id)
            ->whereDate('tanggal', '>=', $today)
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->first();

        $totalSupervisi = Supervisi::where('guru_id', $guru->id)->count();

        $riwayatSupervisi = Supervisi::where('guru_id', $guru->id)
            ->where('status', 'selesai')
            ->count();

        $jadwalMendatang = Supervisi::where('guru_id', $guru->id)
            ->whereDate('tanggal', '>=', $today)
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->count();

        $semesters = Supervisi::where('guru_id', $guru->id)
            ->whereNotNull('semester')
            ->where('semester', '!=', '')
            ->distinct()
            ->orderBy('semester')
            ->pluck('semester');

        $daftarMataPelajaran = Supervisi::where('guru_id', $guru->id)
            ->whereNotNull('mata_pelajaran')
            ->where('mata_pelajaran', '!=', '')
            ->distinct()
            ->orderBy('mata_pelajaran')
            ->pluck('mata_pelajaran');

        $statusLabels = [
            'terjadwal' => 'Terjadwal',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];

        return view('guru.supervisi', compact(
            'guru',
            'supervisis',
            'jadwalBerikutnya',
            'totalSupervisi',
            'riwayatSupervisi',
            'jadwalMendatang',
            'semesters',
            'daftarMataPelajaran',
            'statusLabels'
        ));
    }

    public function show($id)
    {
        $guru = auth()->user();

        abort_unless($guru instanceof Guru, 403, 'Akun guru tidak ditemukan.');

        $supervisi = Supervisi::where('guru_id', $guru->id)
            ->findOrFail($id);

        return view('guru.detailsupervisi', compact('guru', 'supervisi'));
    }

    public function lihatSurat($id)
    {
        $guru = auth()->user();

        abort_unless($guru instanceof Guru, 403, 'Akun guru tidak ditemukan.');

        $supervisi = Supervisi::where('guru_id', $guru->id)
            ->findOrFail($id);

        if (!$supervisi->surat_pemberitahuan_path || !Storage::disk('public')->exists($supervisi->surat_pemberitahuan_path)) {
            abort(404, 'Surat pemberitahuan tidak ditemukan.');
        }

        return response()->file(
            Storage::disk('public')->path($supervisi->surat_pemberitahuan_path),
            [
                'Content-Disposition' => 'inline; filename="' . ($supervisi->nama_surat_pemberitahuan ?: 'surat-pemberitahuan.pdf') . '"',
            ]
        );
    }

    public function feedback($id)
    {
        $guru = auth()->user();

        abort_unless($guru instanceof Guru, 403, 'Akun guru tidak ditemukan.');

        $supervisi = Supervisi::where('guru_id', $guru->id)
            ->findOrFail($id);

        abort_unless(
            strtolower((string) $supervisi->status) === 'selesai',
            403,
            'Feedback hanya tersedia untuk supervisi yang telah selesai.'
        );

        return view('guru.feedbacksupervisi', compact('guru', 'supervisi'));
    }
}
