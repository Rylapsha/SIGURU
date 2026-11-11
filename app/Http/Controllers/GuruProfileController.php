<?php

namespace App\Http\Controllers;

use App\Services\FileCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GuruProfileController extends Controller
{
    public function __construct(private FileCompressionService $fileCompression)
    {
    }

    public function index()
    {
        $guru = Auth::user();

        return view('guru.profileguru', compact('guru'));
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:20480',
        ], [
            'foto.required' => 'Silakan pilih foto terlebih dahulu.',
            'foto.image' => 'File yang dipilih harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 20 MB.',
        ]);

        $guru = Auth::user();

        $storedFile = $this->fileCompression->store($request->file('foto'), 'profil');
        $path = $storedFile['path'];

        if (!$path) {
            return back()
                ->withInput()
                ->withErrors([
                    'foto' => 'Foto gagal disimpan. Silakan coba kembali.',
                ]);
        }

        $fotoLama = $guru->foto;
        $guru->foto = $path;

        try {
            $guru->save();
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($path);
            report($exception);

            return back()
                ->withInput()
                ->withErrors([
                    'foto' => 'Foto gagal disimpan ke database. Silakan coba kembali.',
                ]);
        }

        if ($fotoLama && Storage::disk('public')->exists($fotoLama)) {
            Storage::disk('public')->delete($fotoLama);
        }

        $message = 'Foto profil berhasil diperbarui.';

        if ($storedFile['notice']) {
            $message .= ' ' . $storedFile['notice'];
        }

        return redirect()
            ->route('profil')
            ->with('success', $message);
    }
}