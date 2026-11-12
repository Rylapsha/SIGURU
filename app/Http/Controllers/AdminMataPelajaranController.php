<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminMataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $this->pastikanAdmin();

        $mataPelajarans = MataPelajaran::query()
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->where('nama', 'like', '%' . trim($request->cari) . '%');
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.mata-pelajaran.index', compact('mataPelajarans'));
    }

    public function store(Request $request)
    {
        $this->pastikanAdmin();

        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:100', 'unique:mata_pelajarans,nama'],
        ], [
            'nama.required' => 'Nama mata pelajaran wajib diisi.',
            'nama.max' => 'Nama mata pelajaran maksimal 100 karakter.',
            'nama.unique' => 'Mata pelajaran sudah ada.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.mata-pelajaran.index')
                ->withErrors($validator)
                ->withInput();
        }

        MataPelajaran::create([
            'nama' => trim($request->nama),
        ]);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $this->pastikanAdmin();

        $validator = Validator::make($request->all(), [
            'nama' => [
                'required',
                'string',
                'max:100',
                'unique:mata_pelajarans,nama,' . $mataPelajaran->id,
            ],
        ], [
            'nama.required' => 'Nama mata pelajaran wajib diisi.',
            'nama.max' => 'Nama mata pelajaran maksimal 100 karakter.',
            'nama.unique' => 'Mata pelajaran sudah ada.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.mata-pelajaran.index')
                ->withErrors($validator)
                ->withInput();
        }

        $mataPelajaran->update([
            'nama' => trim($request->nama),
        ]);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        $this->pastikanAdmin();

        $mataPelajaran->delete();

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    private function pastikanAdmin(): void
    {
        abort_unless(session()->has('admin_id'), 403, 'Akses admin diperlukan.');
    }
}
