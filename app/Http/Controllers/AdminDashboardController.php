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

        /*
         * Grafik kelengkapan dokumen.
         *
         * PROTA dan PROSEM tidak menggunakan kelas.
         * Enam jenis dokumen lainnya harus tersedia
         * untuk tahun ajaran, mapel, semester, dan kelas yang sama.
         */
        $jenisWajib = [
            'Modul Ajar',
            'ATP',
            'AP',
            'PROTA',
            'PROSEM',
            'Kisi-Kisi',
            'Naskah Soal',
            'Analisis Butir Soal',
        ];

        $jenisTanpaKelas = ['PROTA', 'PROSEM'];

        $guruIds = DB::table('gurus')->pluck('id');

        $dokumen = DB::table('dokumens')
            ->select(
                'guru_id',
                'jenis_dokumen',
                'tahun_ajaran',
                'mata_pelajaran',
                'semester',
                'kelas'
            )
            ->whereIn('guru_id', $guruIds)
            ->get();

        // Menyimpan jenis dokumen PROTA/PROSEM per kombinasi
        // guru, tahun ajaran, mapel, dan semester.
        $dokumenTanpaKelas = [];

        foreach ($dokumen as $item) {
            $jenis = trim($item->jenis_dokumen);

            if (!in_array($jenis, $jenisTanpaKelas, true)) {
                continue;
            }

            $kunci = json_encode([
                $item->guru_id,
                $item->tahun_ajaran,
                $item->mata_pelajaran,
                $item->semester,
            ]);

            $dokumenTanpaKelas[$kunci][$jenis] = true;
        }

        // Mengelompokkan enam jenis dokumen berdasarkan
        // guru, tahun ajaran, mapel, semester, dan kelas.
        $kombinasi = [];

        foreach ($dokumen as $item) {
            $jenis = trim($item->jenis_dokumen);

            if (in_array($jenis, $jenisTanpaKelas, true)) {
                continue;
            }

            // Jenis dokumen lain harus mempunyai kelas.
            if ($item->kelas === null || $item->kelas === '') {
                continue;
            }

            $kunci = json_encode([
                $item->guru_id,
                $item->tahun_ajaran,
                $item->mata_pelajaran,
                $item->semester,
                $item->kelas,
            ]);

            $kunciTahunan = json_encode([
                $item->guru_id,
                $item->tahun_ajaran,
                $item->mata_pelajaran,
                $item->semester,
            ]);

            if (!isset($kombinasi[$kunci])) {
                $kombinasi[$kunci] = [
                    'guru_id' => $item->guru_id,
                    'jenis' => [],
                    'jenis_tahunan' => $kunciTahunan,
                ];
            }

            $kombinasi[$kunci]['jenis'][$jenis] = true;
        }

        // Satu guru dihitung lengkap apabila setidaknya ada
        // satu kombinasi yang memenuhi seluruh 8 jenis dokumen.
        $guruLengkap = [];

        foreach ($kombinasi as $data) {
            $jenisTersedia = $data['jenis'];

            $jenisTahunan = $dokumenTanpaKelas[$data['jenis_tahunan']] ?? [];

            foreach ($jenisTanpaKelas as $jenis) {
                if (isset($jenisTahunan[$jenis])) {
                    $jenisTersedia[$jenis] = true;
                }
            }

            $lengkap = true;

            foreach ($jenisWajib as $jenis) {
                if (!isset($jenisTersedia[$jenis])) {
                    $lengkap = false;
                    break;
                }
            }

            if ($lengkap) {
                $guruLengkap[$data['guru_id']] = true;
            }
        }

        $dokumenLengkap = count($guruLengkap);

        $dokumenBelumLengkap = max(
            0,
            $totalGuru - $dokumenLengkap
        );

        $persentaseLengkap = $totalGuru > 0
            ? round(($dokumenLengkap / $totalGuru) * 100)
            : 0;

        $persentaseBelumLengkap = $totalGuru > 0
            ? round(($dokumenBelumLengkap / $totalGuru) * 100)
            : 0;

        // Maksimal lima jadwal mendatang berstatus terjadwal.
        $agendaKegiatan = DB::table('agenda_kegiatans')
            ->select(
                'id',
                'judul',
                'tanggal',
                'jam',
                'lokasi',
                'keterangan',
                'status'
            )
            ->where('status', '!=', 'dibatalkan')
            ->whereDate('tanggal', '>=', Carbon::today())
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->get()
            ->map(function ($item) {
                $item->jenis = 'kegiatan';
                $item->label = $item->judul;
                return $item;
            });

        $jadwalAgenda = DB::table('supervisis')
            ->leftJoin('gurus', 'supervisis.guru_id', '=', 'gurus.id')
            ->select(
                'supervisis.id',
                'supervisis.tanggal',
                'supervisis.jam',
                'supervisis.mata_pelajaran',
                'gurus.nama_lengkap',
                'supervisis.status'
            )
            ->where('supervisis.status', 'terjadwal')
            ->whereDate('supervisis.tanggal', '>=', Carbon::today())
            ->orderBy('supervisis.tanggal')
            ->orderBy('supervisis.jam')
            ->get()
            ->map(function ($item) {
                $item->jenis = 'supervisi';
                $item->label = 'Supervisi Guru';
                $item->judul = $item->label;
                $item->lokasi = $item->mata_pelajaran ? 'Mata Pelajaran: ' . $item->mata_pelajaran : null;
                return $item;
            });

        $agendaGabungan = $agendaKegiatan
            ->merge($jadwalAgenda)
            ->sortBy(function ($item) {
                return Carbon::parse($item->tanggal)->format('Ymd') . ($item->jam ?? '23:59');
            })
            ->values();

        $jadwalMendatang = $agendaGabungan->take(5);

        $jumlahJadwalMendatang = $agendaGabungan->count();

        $supervisiSelesai = DB::table('supervisis')
            ->where('status', 'selesai')
            ->whereNotNull('guru_id')
            ->select('guru_id', 'tanggal')
            ->orderBy('tanggal')
            ->get();

        $jumlahGuruSupervisiSelesai = $supervisiSelesai
            ->filter(function ($item) {
                if (empty($item->tanggal)) {
                    return false;
                }

                return Carbon::parse($item->tanggal)->between(
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                );
            })
            ->unique('guru_id')
            ->count();

        return view('admin.index', compact(
            'totalGuru',
            'statistikMapel',
            'jadwalMendatang',
            'dokumenLengkap',
            'dokumenBelumLengkap',
            'persentaseLengkap',
            'persentaseBelumLengkap',
            'supervisiSelesai',
            'jumlahGuruSupervisiSelesai'
        ));
    }
}
