<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Guru;
use App\Models\KomentarDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminGuruController extends Controller
{
    /**
     * Memastikan pengguna yang mengakses adalah admin.
     */
    private function pastikanAdmin(): void
    {
        abort_unless(
            session()->has('admin_id'),
            403,
            'Akses admin diperlukan.'
        );
    }

    /**
     * Daftar kategori dokumen.
     */
    private function kategoriDokumen(): array
    {
        return [
            'Modul Ajar' => [
                'Modul Ajar',
            ],

            'ATP' => [
                'ATP',
                'Alur Pembelajaran',
                'Tujuan Pembelajaran',
            ],

            'AP' => [
                'AP',
            ],

            'Prota' => [
                'PROTA',
                'Prota',
            ],

            'Prosem' => [
                'PROSEM',
                'Prosem',
            ],

            'Bank Soal' => [
                'Kisi-Kisi',
                'Kisi-kisi',
                'Naskah Soal',
                'Analisis Butir Soal',
            ],
        ];
    }

    /**
     * Halaman daftar guru, pencarian, filter, dan pagination.
     */
    public function index(Request $request)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        // Daftar mata pelajaran.
        $daftarMataPelajaran = collect([
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Bahasa Sunda',
            'Bimbingan Konseling',
            'Ilmu Pengetahuan Alam',
            'Ilmu Pengetahuan Sosial',
            'Matematika',
            'Pendidikan Agama Islam',
            'Pendidikan Agama Kristen',
            'Pendidikan Jasmani dan Olahraga Kebugaran',
            'Pendidikan Pancasila',
            'Seni Budaya dan Keterampilan',
        ])->sort()->values();

        // Query data guru.
        $query = Guru::query();

        // Pencarian berdasarkan nama guru.
        if ($request->filled('cari')) {
            $query->where(
                'nama_lengkap',
                'like',
                '%' . trim($request->cari) . '%'
            );
        }

        // Filter mata pelajaran.
        if ($request->filled('mata_pelajaran')) {
            $query->where(
                'mata_pelajaran',
                $request->mata_pelajaran
            );
        }

        // Pagination 9 guru per halaman.
        $gurus = $query
            ->orderBy('nama_lengkap')
            ->paginate(9)
            ->withQueryString();

        // Ambil dokumen milik guru pada halaman aktif.
        $guruIds = $gurus->getCollection()->pluck('id');

        $dokumens = Dokumen::whereIn('guru_id', $guruIds)
            ->get()
            ->groupBy('guru_id');

        // Hitung kelengkapan dokumen setiap guru.
        foreach ($gurus as $guru) {
            $dokumenGuru = $dokumens->get(
                $guru->id,
                collect()
            );

            $kelengkapan = [];

            foreach ($this->kategoriDokumen() as $label => $jenisYangDiterima) {
                $jenisNormal = array_map(
                    fn ($jenis) => mb_strtolower(trim($jenis)),
                    $jenisYangDiterima
                );

                $jumlah = $dokumenGuru
                    ->filter(function ($dokumen) use ($jenisNormal) {
                        return in_array(
                            mb_strtolower(trim($dokumen->jenis_dokumen)),
                            $jenisNormal,
                            true
                        );
                    })
                    ->count();

                $kelengkapan[$label] = [
                    'jumlah' => $jumlah,
                    'status' => $jumlah > 0,
                ];
            }

            $guru->kelengkapanDokumen = $kelengkapan;
        }

        return view('admin.guru.index', compact(
            'gurus',
            'daftarMataPelajaran'
        ));
    }

    /**
     * Halaman detail guru, dokumen, dan riwayat komentar.
     */
    public function show($id)
    {
        $this->pastikanAdmin();

        $guru = Guru::findOrFail($id);

        // Ambil semua dokumen milik guru.
        $semuaDokumen = Dokumen::where('guru_id', $guru->id)
            ->latest()
            ->get();

        // Kelompokkan dokumen berdasarkan kategori.
        $dokumenPerKategori = [];

        foreach ($this->kategoriDokumen() as $label => $jenisYangDiterima) {
            $jenisNormal = array_map(
                fn ($jenis) => mb_strtolower(trim($jenis)),
                $jenisYangDiterima
            );

            $dokumenPerKategori[$label] = $semuaDokumen
                ->filter(function ($dokumen) use ($jenisNormal) {
                    return in_array(
                        mb_strtolower(trim($dokumen->jenis_dokumen)),
                        $jenisNormal,
                        true
                    );
                })
                ->values();
        }

        // Ambil riwayat komentar berdasarkan ID dokumen.
        $komentarPerDokumen = KomentarDokumen::whereIn(
            'dokumen_id',
            $semuaDokumen->pluck('id')
        )
            ->latest()
            ->get()
            ->groupBy('dokumen_id');

        return view('admin.guru.detail', compact(
            'guru',
            'dokumenPerKategori',
            'komentarPerDokumen'
        ));
    }

    /**
     * Membuka PDF dokumen untuk dilihat admin.
     */
    public function lihatDokumen($id)
    {
        $this->pastikanAdmin();

        $dokumen = Dokumen::findOrFail($id);

        // Pastikan file tersedia.
        if (!Storage::disk('public')->exists($dokumen->file_path)) {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        // Hanya izinkan file PDF.
        if (
            strtolower(
                pathinfo($dokumen->file_path, PATHINFO_EXTENSION)
            ) !== 'pdf'
        ) {
            abort(404, 'File bukan PDF.');
        }

        return response()->file(
            Storage::disk('public')->path($dokumen->file_path),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' .
                    str_replace(
                        '"',
                        '',
                        basename($dokumen->nama_file)
                    ) . '"',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }
}
