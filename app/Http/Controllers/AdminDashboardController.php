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

        // Hitung kelengkapan guru pada tahun ajaran dan semester aktif.
        $waktuJakarta = Carbon::now('Asia/Jakarta');
        $tahun = $waktuJakarta->year;
        $tahunMulaiAjaran = $waktuJakarta->month >= 7 ? $tahun : $tahun - 1;
        $tahunAjaranAktif = $tahunMulaiAjaran . '/' . ($tahunMulaiAjaran + 1);
        $semesterAktif = $waktuJakarta->month >= 7 ? '1' : '2';

        $dokumenAktif = DB::table('dokumens')
            ->select('guru_id', 'jenis_dokumen', 'mata_pelajaran', 'semester', 'kelas')
            ->where('tahun_ajaran', $tahunAjaranAktif)
            ->get();

        $jenisTpAtp = [
            'ATP',
            'AP',
            'TP',
            'TP/ATP',
            'ALUR PEMBELAJARAN',
            'TUJUAN PEMBELAJARAN',
        ];
        $jenisBankSoal = [
            'KISI-KISI',
            'NASKAH SOAL',
            'ANALISIS BUTIR SOAL',
        ];
        $jenisWajibDenganKelas = array_merge(
            ['MODUL AJAR'],
            $jenisBankSoal,
            $jenisTpAtp
        );
        $protaTersedia = [];
        $prosemTersedia = [];
        $kombinasiDokumen = [];

        foreach ($dokumenAktif as $item) {
            $jenis = mb_strtoupper(trim($item->jenis_dokumen));
            $mataPelajaran = mb_strtolower(trim((string) $item->mata_pelajaran));

            if ($mataPelajaran === '') {
                continue;
            }

            $kunciTahunan = json_encode([$item->guru_id, $mataPelajaran]);

            // PROTA berlaku untuk satu tahun ajaran, tanpa semester atau kelas.
            if (in_array($jenis, ['PROTA', 'PROTA & PROSEM'], true)) {
                $protaTersedia[$kunciTahunan] = true;
            }

            if ((string) $item->semester !== $semesterAktif) {
                continue;
            }

            // PROSEM mengikuti semester aktif, tetapi tidak menggunakan kelas.
            if (in_array($jenis, ['PROSEM', 'PROTA & PROSEM'], true)) {
                $kunciSemester = json_encode([
                    $item->guru_id,
                    $mataPelajaran,
                    $semesterAktif,
                ]);
                $prosemTersedia[$kunciSemester] = true;
            }

            if (!in_array($jenis, $jenisWajibDenganKelas, true)) {
                continue;
            }

            if ($item->kelas === null || trim((string) $item->kelas) === '') {
                continue;
            }

            $kunciKombinasi = json_encode([
                $item->guru_id,
                $mataPelajaran,
                $semesterAktif,
                trim((string) $item->kelas),
            ]);

            $kombinasiDokumen[$kunciKombinasi] ??= [
                'guru_id' => $item->guru_id,
                'kunci_tahunan' => $kunciTahunan,
                'kunci_semester' => json_encode([
                    $item->guru_id,
                    $mataPelajaran,
                    $semesterAktif,
                ]),
                'jenis' => [],
            ];

            $kombinasiDokumen[$kunciKombinasi]['jenis'][$jenis] = true;
        }

        $guruLengkap = [];

        foreach ($kombinasiDokumen as $kombinasi) {
            $jenisTersedia = $kombinasi['jenis'];
            $memilikiTpAtp = collect($jenisTpAtp)->contains(
                fn ($jenis) => isset($jenisTersedia[$jenis])
            );
            $memilikiDokumenKelas = collect(array_merge(['MODUL AJAR'], $jenisBankSoal))
                ->every(fn ($jenis) => isset($jenisTersedia[$jenis]));
            $memilikiProta = isset($protaTersedia[$kombinasi['kunci_tahunan']]);
            $memilikiProsem = isset($prosemTersedia[$kombinasi['kunci_semester']]);

            if ($memilikiTpAtp && $memilikiDokumenKelas && $memilikiProta && $memilikiProsem) {
                $guruLengkap[$kombinasi['guru_id']] = true;
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

        // Agenda mendatang yang belum selesai atau dibatalkan.
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
            ->where('status', '!=', 'selesai')
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
