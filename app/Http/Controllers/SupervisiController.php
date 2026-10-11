<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Supervisi;
use Illuminate\Http\Request;

class SupervisiController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user();

        abort_unless($guru instanceof Guru, 403, 'Akun guru tidak ditemukan.');

        $query = Supervisi::where('guru_id', $guru->id);

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        $supervisis = (clone $query)
            ->orderByDesc('tanggal')
            ->orderByDesc('jam')
            ->get();

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

        $statusLabels = [
            'terjadwal' => 'Terjadwal',
            'dalam_proses' => 'Dalam Proses',
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
            'statusLabels'
        ));
    }

    public function show($id)
    {
        $guru = auth()->user();

        abort_unless($guru instanceof Guru, 403, 'Akun guru tidak ditemukan.');

        $supervisi = Supervisi::where('guru_id', $guru->id)
            ->findOrFail($id);

        return view('guru.supervisi-detail', compact('guru', 'supervisi'));
    }
}