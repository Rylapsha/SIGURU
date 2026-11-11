<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
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

    public function create()
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        $guru = DB::table('gurus')
            ->select('id', 'nama_lengkap', 'mata_pelajaran')
            ->orderBy('nama_lengkap')
            ->get();

        $mataPelajaran = MataPelajaran::query()
            ->orderBy('nama')
            ->pluck('nama')
            ->all();

        return view('admin.supervisi.create', compact('guru', 'mataPelajaran'));
    }

    public function index()
    {
        if ($redirect = $this->pastikanAdmin()) {
            return $redirect;
        }

        $statistik = [
            'terjadwal' => DB::table('supervisis')
                ->where('status', 'terjadwal')->count(),
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
            'surat_pemberitahuan' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
        ], [
            'surat_pemberitahuan.required' => 'Wajib unggah surat pemberitahuan dalam format PDF.',
            'surat_pemberitahuan.file' => 'File surat pemberitahuan tidak valid.',
            'surat_pemberitahuan.mimes' => 'Format surat pemberitahuan harus PDF.',
            'surat_pemberitahuan.max' => 'Ukuran surat pemberitahuan maksimal 10 MB.',
        ]);

        unset($data['surat_pemberitahuan']);

        $guru = DB::table('gurus')->where('id', $request->guru_id)->first();
        $allowedMapels = [];

        if ($guru && !empty($guru->mata_pelajaran)) {
            $allowedMapels = array_values(array_filter(array_map('trim', preg_split('/[;,]/', (string) $guru->mata_pelajaran))));
        }

        if ($guru && !empty($allowedMapels) && !in_array($data['mata_pelajaran'], $allowedMapels, true)) {
            return back()
                ->withInput()
                ->withErrors([
                    'mata_pelajaran' => 'Mata pelajaran yang dipilih tidak sesuai dengan mata pelajaran yang diampu guru tersebut.',
                ]);
        }

        if ($guru && empty($allowedMapels)) {
            return back()
                ->withInput()
                ->withErrors([
                    'guru_id' => 'Guru yang dipilih belum memiliki mata pelajaran yang valid.',
                ]);
        }

        $suratFile = $request->file('surat_pemberitahuan');
        $suratPath = $suratFile->store('supervisi', 'public');

        if (!$suratPath) {
            return back()
                ->withInput()
                ->withErrors([
                    'surat_pemberitahuan' => 'Surat pemberitahuan gagal disimpan. Silakan coba kembali.',
                ]);
        }

        $data['surat_pemberitahuan_path'] = $suratPath;
        $data['nama_surat_pemberitahuan'] = $suratFile->getClientOriginalName();
        $data['status'] = 'terjadwal';
        $data['created_at'] = now();
        $data['updated_at'] = now();

        try {
            DB::table('supervisis')->insert($data);
        } catch (\Throwable $e) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($suratPath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($suratPath);
            }

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'surat_pemberitahuan' => 'Jadwal supervisi gagal disimpan. Silakan coba kembali.',
                ]);
        }

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
            'tindak_lanjut' => ['nullable', 'string', 'max:10000'],
        ]);

        $exists = DB::table('supervisis')->where('id', $id)->exists();

        if (!$exists) {
            abort(404);
        }

        DB::table('supervisis')
            ->where('id', $id)
            ->update([
                'tindak_lanjut' => $data['tindak_lanjut'],
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.supervisi.index')
            ->with('success', 'Catatan tindak lanjut berhasil disimpan.');
    }

    public function updatePenilaian(Request $request, $id)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'predikat' => ['nullable', 'string', Rule::in(['Kurang', 'Cukup', 'Baik', 'Sangat Baik'])],
            'feedback_admin' => ['nullable', 'string', 'max:10000'],
        ]);

        $exists = DB::table('supervisis')->where('id', $id)->exists();

        if (!$exists) {
            abort(404);
        }

        $skorMap = [
            'Kurang' => 25,
            'Cukup' => 50,
            'Baik' => 75,
            'Sangat Baik' => 100,
        ];

        DB::table('supervisis')
            ->where('id', $id)
            ->update([
                'predikat' => $data['predikat'] ?? null,
                'skor' => $data['predikat'] ? ($skorMap[$data['predikat']] ?? null) : null,
                'feedback_admin' => $data['feedback_admin'] ?? null,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.supervisi.index')
            ->with('success', 'Feedback dan penilaian supervisi berhasil disimpan.');
    }

    public function destroy($id)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        $exists = DB::table('supervisis')->where('id', $id)->exists();

        if (!$exists) {
            abort(404);
        }

        DB::table('supervisis')->where('id', $id)->delete();

        return redirect()
            ->route('admin.supervisi.index')
            ->with('success', 'Jadwal supervisi berhasil dihapus.');
    }

    public function show($id)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        $item = DB::table('supervisis')
            ->leftJoin('gurus', 'supervisis.guru_id', '=', 'gurus.id')
            ->select(
                'supervisis.*',
                'gurus.nama_lengkap'
            )
            ->where('supervisis.id', $id)
            ->first();

        if (!$item) {
            abort(404);
        }

        return view('admin.supervisi.show', compact('item'));
    }

    public function feedback($id)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('login');
        }

        $item = DB::table('supervisis')
            ->leftJoin('gurus', 'supervisis.guru_id', '=', 'gurus.id')
            ->select(
                'supervisis.*',
                'gurus.nama_lengkap'
            )
            ->where('supervisis.id', $id)
            ->first();

        if (!$item) {
            abort(404);
        }

        return view('admin.supervisi.feedback', compact('item'));
    }
}
