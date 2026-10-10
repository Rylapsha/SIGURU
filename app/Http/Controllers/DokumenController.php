<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        if (!in_array($jenis, $this->jenisDokumen(), true)) {
            abort(404);
        }

        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
        ], [
            'file.required' => 'Silakan pilih file PDF.',
            'file.file' => 'File tidak valid.',
            'file.mimes' => 'File harus berformat PDF.',
            'file.max' => 'Ukuran file maksimal 10 MB.',
        ]);

        $file = $request->file('file');

        $path = $file->store('dokumen', 'public');

        Dokumen::create([
            'guru_id' => Auth::id(),
            'jenis_dokumen' => $jenis,
            'nama_file' => $file->getClientOriginalName(),
            'file_path' => $path,
        ]);

        return redirect()
            ->route('dokumen.index')
            ->with('success', $jenis . ' berhasil di-upload.');
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

        $dokumen->delete();

        return redirect()
            ->route('dokumen.index')
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

    public function modulAjar(Request $request)
    {
        $query = Dokumen::where('guru_id', Auth::id())
            ->where('jenis_dokumen', 'Modul Ajar');

        $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],
        ]);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $dokumens = $query->latest()->get();

        return view('guru.dokumenmodulajar', compact('dokumens'));
    }
}
