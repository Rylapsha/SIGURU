<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Services\FileCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    public function index()
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        $admin = Admin::findOrFail(session('admin_id'));
        $admin->load('guru');

        return view('admin.profil', compact('admin'));
    }

    public function showFoto()
    {
        abort_unless(session()->has('admin_id'), 403, 'Akses admin diperlukan.');

        $admin = Admin::findOrFail(session('admin_id'));

        abort_unless(
            $admin->foto && Storage::disk('public')->exists($admin->foto),
            404,
            'Foto profil tidak ditemukan.'
        );

        return Storage::disk('public')->response($admin->foto, null, [
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ], 'inline');
    }

    public function updateFoto(Request $request, FileCompressionService $fileCompression)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
        ], [
            'foto.required' => 'Silakan pilih foto terlebih dahulu.',
            'foto.image' => 'File yang dipilih harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 20 MB.',
        ]);

        $admin = Admin::findOrFail(session('admin_id'));

        try {
            $storedFile = $fileCompression->store($request->file('foto'), 'profil/admin');
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'foto' => 'Foto gagal disimpan. Silakan coba kembali.',
            ]);
        }

        $path = $storedFile['path'];

        if (!$path) {
            return back()->withErrors([
                'foto' => 'Foto gagal disimpan. Silakan coba kembali.',
            ]);
        }

        $fotoLama = $admin->foto;
        $admin->foto = $path;

        try {
            $admin->save();
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($path);
            report($exception);

            return back()->withErrors([
                'foto' => 'Foto gagal disimpan ke database. Silakan coba kembali.',
            ]);
        }

        session()->put('admin_foto', $path);

        if ($fotoLama && Storage::disk('public')->exists($fotoLama)) {
            Storage::disk('public')->delete($fotoLama);
        }

        return redirect()
            ->route('admin.profil')
            ->with('success', 'Foto profil berhasil diperbarui.');
    }
}
