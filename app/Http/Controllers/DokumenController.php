<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    /**
     * Menampilkan halaman dokumen guru.
     */
    public function index()
    {
        $guruId = Auth::id();

        $jenisDokumen = [
            'Modul Ajar',
            'ATP',
            'AP',
            'PROTA',
            'PROSEM',
            'Bank Soal',
        ];

        $dokumens = Dokumen::where('guru_id', $guruId)
            ->get()
            ->keyBy('jenis_dokumen');

        return view('guru.dokumen', compact(
            'jenisDokumen',
            'dokumens'
        ));
    }

    /**
     * Upload dokumen.
     */
    public function store(Request $request, string $jenis)
    {
        $jenisDokumen = [
            'Modul Ajar',
            'ATP',
            'AP',
            'PROTA',
            'PROSEM',
            'Bank Soal',
        ];

        if (!in_array($jenis, $jenisDokumen)) {
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

        $guruId = Auth::id();

        $dokumenLama = Dokumen::where('guru_id', $guruId)
            ->where('jenis_dokumen', $jenis)
            ->first();

        if ($dokumenLama && Storage::disk('public')->exists($dokumenLama->file_path)) {
            Storage::disk('public')->delete($dokumenLama->file_path);
        }

        $file = $request->file('file');

        $path = $file->store('dokumen', 'public');

        Dokumen::updateOrCreate(
            [
                'guru_id' => $guruId,
                'jenis_dokumen' => $jenis,
            ],
            [
                'nama_file' => $file->getClientOriginalName(),
                'file_path' => $path,
            ]
        );

        return redirect()
            ->route('dokumen.index')
            ->with('success', $jenis . ' berhasil di-upload.');
    }

    /**
     * Melihat dokumen PDF.
     */
    public function lihat(Dokumen $dokumen)
    {
        if ($dokumen->guru_id !== Auth::id()) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($dokumen->file_path)) {
            abort(404);
        }

        return response()->file(
            Storage::disk('public')->path($dokumen->file_path)
        );
    }

    /**
     * Menghapus dokumen.
     */
    public function destroy(Dokumen $dokumen)
    {
        if ($dokumen->guru_id !== Auth::id()) {
            abort(403);
        }

        if (Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        return redirect()
            ->route('dokumen.index')
            ->with('success', $dokumen->jenis_dokumen . ' berhasil dihapus.');
    }
}