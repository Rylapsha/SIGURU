<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DokumenController extends Controller
{
    /**
     * Daftar kategori dokumen.
     */
    private function jenisDokumen(): array
    {
        return [
            'Modul Ajar',
            'ATP',
            'AP',
            'PROTA',
            'PROSEM',
            'Kisi-Kisi',
            'Naskah Soal',
            'Analisis Butir Soal',
        ];
    }

    private function daftarKelas(): array
    {
        return ['10', '11', '12'];
    }

    private function daftarMataPelajaran(): array
    {
        return [
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
        ];
    }

    private function slugJenisDokumen(string $jenis): string
    {
        $map = [
            'Modul Ajar' => 'modul-ajar',
            'ATP' => 'atp-ap',
            'AP' => 'atp-ap',
            'PROTA' => 'prota-prosem',
            'PROSEM' => 'prota-prosem',
            'Kisi-Kisi' => 'kisi-kisi',
            'Naskah Soal' => 'naskah-soal',
            'Analisis Butir Soal' => 'analisis-butir-soal',
        ];

        return $map[$jenis] ?? strtolower(str_replace(' ', '-', $jenis));
    }

    private function jenisDariSlug(string $jenis): string
    {
        $map = [
            'modul-ajar' => 'Modul Ajar',
            'atp-ap' => 'ATP',
            'atp' => 'ATP',
            'ap' => 'AP',
            'prota-prosem' => 'PROTA',
            'prota' => 'PROTA',
            'prosem' => 'PROSEM',
            'kisi-kisi' => 'Kisi-Kisi',
            'naskah-soal' => 'Naskah Soal',
            'analisis-butir-soal' => 'Analisis Butir Soal',
        ];

        return $map[$jenis] ?? abort(404, 'Jenis dokumen tidak ditemukan.');
    }

    /**
     * Menampilkan halaman dokumen guru.
     */
    public function index()
    {
        $guruId = Auth::id();

        $jenisDokumen = $this->jenisDokumen();

        // Ambil semua dokumen milik guru,
        // termasuk beberapa dokumen dalam kategori yang sama.
        $dokumens = Dokumen::where('guru_id', $guruId)
            ->latest()
            ->get()
            ->groupBy('jenis_dokumen');

        return view('guru.dokumen', compact(
            'jenisDokumen',
            'dokumens'
        ));
    }


    /**
     * Mengunggah dokumen baru tanpa menimpa dokumen lama.
     */
    public function store(Request $request, string $jenis)
    {
        $jenisLabel = $this->jenisDariSlug($jenis);

        // PROTA dan PROSEM tidak memerlukan pilihan kelas.
        $isKelasRequired = !in_array(
            $jenis,
            ['prota-prosem', 'prota', 'prosem'],
            true
        );

        // Validasi dilakukan kembali di server.
        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
            'kelas' => [
                $isKelasRequired ? 'required' : 'nullable',
                'string',
                Rule::in($this->daftarKelas()),
            ],
            'mata_pelajaran' => [
                'required',
                'string',
                Rule::in($this->daftarMataPelajaran()),
            ],
            'semester' => [
                'required',
                Rule::in(['1', '2']),
            ],
        ], [
            'file.required' => 'Silakan pilih file PDF.',
            'file.file' => 'File tidak valid.',
            'file.mimes' => 'File harus berformat PDF.',
            'file.max' => 'Ukuran file maksimal 10 MB.',
            'kelas.required' => 'Pilih kelas dokumen terlebih dahulu.',
            'kelas.in' => 'Kelas dokumen tidak valid.',
            'mata_pelajaran.required' =>
            'Pilih mata pelajaran dokumen terlebih dahulu.',
            'mata_pelajaran.in' =>
            'Mata pelajaran dokumen tidak valid.',
        ]);

        $file = $request->file('file');

        // Simpan file ke storage/app/public/dokumen.
        $path = $file->store('dokumen', 'public');

        if (!$path) {
            return back()
                ->withInput()
                ->withErrors([
                    'file' => 'File gagal disimpan. Silakan coba kembali.',
                ]);
        }

        try {
            Dokumen::create([
                'guru_id' => Auth::id(),
                'jenis_dokumen' => $jenisLabel,
                'kelas' => $isKelasRequired
                    ? $validated['kelas']
                    : null,
                'mata_pelajaran' => $validated['mata_pelajaran'],
                'semester' => $validated['semester'],
                'nama_file' => $file->getClientOriginalName(),
                'file_path' => $path,
            ]);
        } catch (\Throwable $e) {
            // Hapus file jika pencatatan ke database gagal.
            Storage::disk('public')->delete($path);

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'file' =>
                    'Dokumen gagal disimpan ke database. Silakan coba kembali.',
                ]);
        }

        return redirect()
            ->route('dokumen.kategori', ['jenis' => $jenis])
            ->with('success', $jenisLabel . ' berhasil di-upload.');
    }

    /**
     * Melihat dokumen PDF.
     */
    public function lihat(Dokumen $dokumen)
    {
        $this->otorisasiDokumen($dokumen);

        if (!Storage::disk('public')->exists($dokumen->file_path)) {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        return response()->file(
            Storage::disk('public')->path($dokumen->file_path)
        );
    }

    /**
     * Mengunduh dokumen.
     */
    public function unduh(Dokumen $dokumen)
    {
        $this->otorisasiDokumen($dokumen);

        if (!Storage::disk('public')->exists($dokumen->file_path)) {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        return Storage::disk('public')->download(
            $dokumen->file_path,
            $dokumen->nama_file
        );
    }

    /**
     * Menghapus dokumen.
     */
    public function destroy(Dokumen $dokumen)
    {
        $this->otorisasiDokumen($dokumen);

        if (Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $jenis = $dokumen->jenis_dokumen;
        $redirectType = $this->slugJenisDokumen($jenis);

        $dokumen->delete();

        return redirect()
            ->route('dokumen.kategori', ['jenis' => $redirectType])
            ->with('success', $jenis . ' berhasil dihapus.');
    }

    /**
     * Memastikan dokumen milik guru yang sedang login.
     */
    private function otorisasiDokumen(Dokumen $dokumen): void
    {
        abort_unless(
            (int) $dokumen->guru_id === (int) Auth::id(),
            403
        );
    }

    /**
     * Menampilkan dokumen berdasarkan kategori.
     */
    public function kategori(string $jenis)
    {
        $map = [
            'modul-ajar' => ['Modul Ajar'],
            'atp-ap' => ['ATP', 'AP', 'Alur Pembelajaran', 'Tujuan Pembelajaran'],
            'atp' => ['ATP'],
            'ap' => ['AP'],
            'prota-prosem' => ['PROTA', 'PROSEM'],
            'prota' => ['PROTA'],
            'prosem' => ['PROSEM'],
            'kisi-kisi' => ['Kisi-Kisi'],
            'naskah-soal' => ['Naskah Soal'],
            'analisis-butir-soal' => ['Analisis Butir Soal'],
        ];

        abort_unless(isset($map[$jenis]), 404, 'Kategori dokumen tidak ditemukan.');

        $label = match ($jenis) {
            'modul-ajar' => 'Modul Ajar',
            'atp-ap' => 'ATP & AP',
            'atp' => 'ATP',
            'ap' => 'AP',
            'prota-prosem' => 'PROTA & PROSEM',
            'prota' => 'PROTA',
            'prosem' => 'PROSEM',
            'kisi-kisi' => 'Kisi-Kisi',
            'naskah-soal' => 'Naskah Soal',
            'analisis-butir-soal' => 'Analisis Butir Soal',
        };

        $query = Dokumen::where('guru_id', Auth::id())
            ->whereIn('jenis_dokumen', $map[$jenis]);

        if (request()->filled('mata_pelajaran')) {
            $query->where('mata_pelajaran', request('mata_pelajaran'));
        }
        if (request()->filled('semester')) {
            $query->where('semester', request('semester'));
        }

        $dokumens = $query->latest()->get();

        return view('guru.dokumen-kategori', compact('dokumens', 'label', 'jenis'));
    }
}
