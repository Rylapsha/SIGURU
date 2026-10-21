<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AdminSupervisiController extends Controller
{
    private function pastikanAdmin()
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        return null;
    }

    public function index()
    {
        if ($redirect = $this->pastikanAdmin()) {
            return $redirect;
        }

        $statistik = [
            'terjadwal' => DB::table('supervisis')
                ->where('status', 'terjadwal')->count(),
            'dalam_proses' => DB::table('supervisis')
                ->where('status', 'dalam_proses')->count(),
            'selesai' => DB::table('supervisis')
                ->where('status', 'selesai')->count(),
            'dibatalkan' => DB::table('supervisis')
                ->where('status', 'dibatalkan')->count(),
        ];

        $guru = DB::table('gurus')
            ->select('id', 'nama_lengkap', 'mata_pelajaran')
            ->orderBy('nama_lengkap')
            ->get();

        $jadwal = DB::table('supervisis')
            ->leftJoin('gurus', 'supervisis.guru_id', '=', 'gurus.id')
            ->select(
                'supervisis.*',
                'gurus.nama_lengkap'
            )
            ->orderByDesc('supervisis.tanggal')
            ->orderByDesc('supervisis.jam')
            ->get();

        return view('admin.supervisi.index', compact(
            'statistik',
            'guru',
            'jadwal'
        ));
    }

    public function store(Request $request)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'guru_id' => ['required', 'exists:gurus,id'],
            'nama_supervisor' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i'],
            'mata_pelajaran' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $data['status'] = 'terjadwal';
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('supervisis')->insert($data);

        return redirect()
            ->route('admin.supervisi.index')
            ->with('success', 'Jadwal supervisi berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, $id)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'terjadwal',
                    'dalam_proses',
                    'selesai',
                    'dibatalkan',
                ]),
            ],
        ]);

        $updated = DB::table('supervisis')
            ->where('id', $id)
            ->update([
                'status' => $data['status'],
                'updated_at' => now(),
            ]);

        if (!$updated && !DB::table('supervisis')->where('id', $id)->exists()) {
            abort(404);
        }

        return redirect()
            ->route('admin.supervisi.index')
            ->with('success', 'Status supervisi berhasil diperbarui.');
    }

    public function updateKomentar(Request $request, $id)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'komentar_admin' => ['nullable', 'string', 'max:10000'],
        ]);

        $exists = DB::table('supervisis')->where('id', $id)->exists();

        if (!$exists) {
            abort(404);
        }

        DB::table('supervisis')
            ->where('id', $id)
            ->update([
                'komentar_admin' => $data['komentar_admin'],
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.supervisi.index')
            ->with('success', 'Komentar admin berhasil disimpan.');
    }

    public function updateHasil(Request $request, $id)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'hasil_observasi' => ['nullable', 'string', 'max:10000'],
            'tindak_lanjut' => ['nullable', 'string', 'max:10000'],
        ]);

        $exists = DB::table('supervisis')->where('id', $id)->exists();

        if (!$exists) {
            abort(404);
        }

        DB::table('supervisis')
            ->where('id', $id)
            ->update([
                'hasil_observasi' => $data['hasil_observasi'],
                'tindak_lanjut' => $data['tindak_lanjut'],
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.supervisi.index')
            ->with('success', 'Hasil supervisi berhasil disimpan.');
    }
}
