<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Pastikan admin sudah login.
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        // Total seluruh guru.
        $totalGuru = DB::table('gurus')->count();

        // Statistik jumlah guru berdasarkan mata pelajaran.
        $statistikMapel = DB::table('gurus')
            ->select(
                'mata_pelajaran',
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereNotNull('mata_pelajaran')
            ->where('mata_pelajaran', '!=', '')
            ->groupBy('mata_pelajaran')
            ->orderByDesc('jumlah')
            ->get();

        $jumlahMaksimum = max(
            1,
            (int) ($statistikMapel->max('jumlah') ?? 0)
        );

        $statistikMapel = $statistikMapel->map(function ($item) use ($jumlahMaksimum) {
            $item->tinggi = max(
                5,
                (int) round(($item->jumlah / $jumlahMaksimum) * 100)
            );

            return $item;
        });

        // Maksimal lima jadwal mendatang yang berstatus terjadwal.
        $jadwalMendatang = DB::table('supervisis')
            ->leftJoin('gurus', 'supervisis.guru_id', '=', 'gurus.id')
            ->select(
                'supervisis.id',
                'supervisis.tanggal',
                'supervisis.jam',
                'supervisis.mata_pelajaran',
                'gurus.nama_lengkap'
            )
            ->where('supervisis.status', 'terjadwal')
            ->whereDate('supervisis.tanggal', '>=', Carbon::today())
            ->orderBy('supervisis.tanggal')
            ->orderBy('supervisis.jam')
            ->limit(5)
            ->get();

        return view('admin.index', compact(
            'totalGuru',
            'statistikMapel',
            'jadwalMendatang'
        ));
    }
}