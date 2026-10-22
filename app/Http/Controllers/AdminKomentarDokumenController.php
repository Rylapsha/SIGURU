<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\KomentarDokumen;
use Illuminate\Http\Request;

class AdminKomentarDokumenController extends Controller
{
    /**
     * Menyimpan komentar admin pada dokumen guru.
     */
    public function store(Request $request, $dokumenId)
    {
        // Pastikan admin sudah login.
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        // Validasi komentar.
        $request->validate([
            'komentar' => ['required', 'string', 'max:5000'],
        ], [
            'komentar.required' => 'Komentar tidak boleh kosong.',
            'komentar.max' => 'Komentar maksimal 5000 karakter.',
        ]);

        // Pastikan dokumen tersedia.
        $dokumen = Dokumen::findOrFail($dokumenId);

        // Simpan komentar.
        KomentarDokumen::create([
            'dokumen_id' => $dokumen->id,
            'admin_id' => (string) session('admin_id'),
            'komentar' => trim($request->komentar),
        ]);

        return back()->with('success', 'Komentar berhasil dikirim.');
    }

    /**
     * Menghapus komentar admin.
     */
    public function destroy($id)
    {
        // Pastikan admin sudah login.
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        // Cari komentar yang akan dihapus.
        $komentar = KomentarDokumen::findOrFail($id);

        // Hapus komentar.
        $komentar->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
