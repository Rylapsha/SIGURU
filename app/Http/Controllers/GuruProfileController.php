<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GuruProfileController extends Controller
{
    public function index()
    {
        $guru = Auth::user();

        return view('guru.profileguru', compact('guru'));
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'foto.required' => 'Silakan pilih foto terlebih dahulu.',
            'foto.image' => 'File yang dipilih harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $guru = Auth::user();

        // Hapus foto lama
        if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
            Storage::disk('public')->delete($guru->foto);
        }

        // Simpan foto baru
        $path = $request->file('foto')->store('profil', 'public');

        // Simpan path foto ke database
        $guru->foto = $path;
        $guru->save();

        return redirect()
            ->route('profil')
            ->with('success', 'Foto profil berhasil diperbarui.');
    }
}