<?php

namespace App\Http\Controllers;

use App\Models\AgendaKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminAgendaKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $this->pastikanAdmin();

        $filters = $request->validate([
            'tanggal_mulai' => [
                'nullable',
                'required_with:tanggal_akhir',
                'date_format:Y-m-d',
            ],
            'tanggal_akhir' => [
                'nullable',
                'required_with:tanggal_mulai',
                'date_format:Y-m-d',
                'after_or_equal:tanggal_mulai',
            ],
        ], [
            'tanggal_mulai.required_with' => 'Tanggal mulai wajib dipilih.',
            'tanggal_mulai.date_format' => 'Format tanggal mulai tidak valid.',
            'tanggal_akhir.required_with' => 'Tanggal akhir wajib dipilih.',
            'tanggal_akhir.date_format' => 'Format tanggal akhir tidak valid.',
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir harus sama dengan atau setelah tanggal mulai.',
        ]);

        $query = AgendaKegiatan::query();

        if (isset($filters['tanggal_mulai'])) {
            $query->whereDate('tanggal', '>=', $filters['tanggal_mulai']);
        }

        if (isset($filters['tanggal_akhir'])) {
            $query->whereDate('tanggal', '<=', $filters['tanggal_akhir']);
        }

        $agenda = $query
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->paginate(5)
            ->withQueryString();

        return view('admin.agenda.index', compact('agenda'));
    }

    public function store(Request $request)
    {
        $this->pastikanAdmin();

        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i'],
            'lokasi' => ['required', 'string', 'max:255'],
            'keterangan' => ['required', 'string', 'max:1000'],
            'status' => ['required', 'string', 'max:50'],
        ], [
            'judul.required' => 'Judul kegiatan wajib diisi.',
            'judul.max' => 'Judul kegiatan maksimal 255 karakter.',
            'tanggal.required' => 'Tanggal kegiatan wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'jam.required' => 'Jam kegiatan wajib diisi.',
            'jam.date_format' => 'Format jam harus HH:MM.',
            'lokasi.required' => 'Lokasi kegiatan wajib diisi.',
            'lokasi.max' => 'Lokasi maksimal 255 karakter.',
            'keterangan.required' => 'Keterangan kegiatan wajib diisi.',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter.',
            'status.required' => 'Status kegiatan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.agenda.index')
                ->withErrors($validator)
                ->withInput();
        }

        AgendaKegiatan::create([
            'judul' => trim($request->judul),
            'tanggal' => $request->tanggal,
            'jam' => $request->jam ?: null,
            'lokasi' => $request->lokasi ? trim($request->lokasi) : null,
            'keterangan' => $request->keterangan ? trim($request->keterangan) : null,
            'status' => trim($request->status ?: 'terjadwal'),
        ]);

        return redirect()->route('admin.agenda.index')
            ->with('success', 'Agenda kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, AgendaKegiatan $agendaKegiatan)
    {
        $this->pastikanAdmin();

        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i'],
            'lokasi' => ['required', 'string', 'max:255'],
            'keterangan' => ['required', 'string', 'max:1000'],
            'status' => ['required', 'string', 'max:50'],
        ], [
            'judul.required' => 'Judul kegiatan wajib diisi.',
            'judul.max' => 'Judul kegiatan maksimal 255 karakter.',
            'tanggal.required' => 'Tanggal kegiatan wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'jam.required' => 'Jam kegiatan wajib diisi.',
            'jam.date_format' => 'Format jam harus HH:MM.',
            'lokasi.required' => 'Lokasi kegiatan wajib diisi.',
            'lokasi.max' => 'Lokasi maksimal 255 karakter.',
            'keterangan.required' => 'Keterangan kegiatan wajib diisi.',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter.',
            'status.required' => 'Status kegiatan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.agenda.index')
                ->withErrors($validator)
                ->withInput();
        }

        $agendaKegiatan->update([
            'judul' => trim($request->judul),
            'tanggal' => $request->tanggal,
            'jam' => $request->jam ?: null,
            'lokasi' => $request->lokasi ? trim($request->lokasi) : null,
            'keterangan' => $request->keterangan ? trim($request->keterangan) : null,
            'status' => trim($request->status ?: 'terjadwal'),
        ]);

        return redirect()->route('admin.agenda.index')
            ->with('success', 'Agenda kegiatan berhasil diperbarui.');
    }

    public function destroy(AgendaKegiatan $agendaKegiatan)
    {
        $this->pastikanAdmin();

        $agendaKegiatan->delete();

        return redirect()->route('admin.agenda.index')
            ->with('success', 'Agenda kegiatan berhasil dihapus.');
    }

    private function pastikanAdmin(): void
    {
        abort_unless(session()->has('admin_id'), 403, 'Akses admin diperlukan.');
    }
}
