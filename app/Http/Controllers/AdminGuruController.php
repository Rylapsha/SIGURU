<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Guru;
use App\Models\KomentarDokumen;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

            'ATP & AP' => [
                'ATP',
                'AP',
                'Alur Pembelajaran',
                'Tujuan Pembelajaran',
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
        $daftarMataPelajaran = MataPelajaran::query()
            ->orderBy('nama')
            ->pluck('nama')
            ->values();

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

        // Pagination 5 guru per halaman.
        $gurus = $query
            ->orderBy('nama_lengkap')
            ->paginate(5)
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
                    fn($jenis) => mb_strtolower(trim($jenis)),
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

    public function store(Request $request)
    {
        $this->pastikanAdmin();

        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:30', 'unique:gurus,nip'],
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'golongan' => ['required', 'string', 'max:50'],
            'mata_pelajaran' => ['required', 'string', 'max:100', Rule::exists('mata_pelajarans', 'nama')],
        ], [
            'nip.required' => 'NIP guru wajib diisi.',
            'nip.unique' => 'NIP guru sudah terdaftar.',
            'nama_lengkap.required' => 'Nama lengkap guru wajib diisi.',
            'golongan.required' => 'Golongan guru wajib diisi.',
            'mata_pelajaran.required' => 'Mata pelajaran wajib dipilih.',
            'mata_pelajaran.exists' => 'Mata pelajaran tidak valid.',
        ]);

        Guru::create([
            'nip' => trim($validated['nip']),
            'nama_lengkap' => trim($validated['nama_lengkap']),
            'golongan' => trim($validated['golongan']),
            'mata_pelajaran' => trim($validated['mata_pelajaran']),
            'password' => Hash::make('123456'),
            'foto' => null,
        ]);

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function update(Request $request, Guru $guru)
    {
        $this->pastikanAdmin();

        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:30', Rule::unique('gurus', 'nip')->ignore($guru->id)],
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'golongan' => ['required', 'string', 'max:50'],
            'mata_pelajaran' => ['required', 'string', 'max:100', Rule::exists('mata_pelajarans', 'nama')],
        ], [
            'nip.required' => 'NIP guru wajib diisi.',
            'nip.unique' => 'NIP guru sudah terdaftar.',
            'nama_lengkap.required' => 'Nama lengkap guru wajib diisi.',
            'golongan.required' => 'Golongan guru wajib diisi.',
            'mata_pelajaran.required' => 'Mata pelajaran wajib dipilih.',
            'mata_pelajaran.exists' => 'Mata pelajaran tidak valid.',
        ]);

        $guru->fill([
            'nip' => trim($validated['nip']),
            'nama_lengkap' => trim($validated['nama_lengkap']),
            'golongan' => trim($validated['golongan']),
            'mata_pelajaran' => trim($validated['mata_pelajaran']),
        ]);

        $guru->save();

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        $this->pastikanAdmin();

        $guru->delete();

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
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
                fn($jenis) => mb_strtolower(trim($jenis)),
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
