@extends('layouts.admin')

@section('title', 'Agenda Kegiatan')
@section('page_title', 'Agenda Kegiatan')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-6">
        <div>
            <h2 class="fw-bold text-gray-900 mb-1">Agenda Kegiatan</h2>
            <div class="text-muted fs-6">Kelola kegiatan tambahan selain jadwal supervisi yang ditampilkan di dashboard.</div>
        </div>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahAgendaModal">
            <i class="bi bi-plus-lg me-2"></i>Tambah Agenda
        </button>
    </div>

    @if (session('success'))
    <div class="alert alert-success d-flex align-items-center mb-5">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger mb-5">
        <div class="fw-semibold mb-2">Periksa kembali input Anda:</div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed table-row-gray-300 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-120px">Tanggal</th>
                            <th class="min-w-220px">Judul Kegiatan</th>
                            <th class="min-w-120px">Jam</th>
                            <th class="min-w-150px">Lokasi</th>
                            <th class="min-w-120px">Status</th>
                            <th class="min-w-120px text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agenda as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                            <td class="fw-semibold text-gray-800">{{ $item->judul }}</td>
                            <td>{{ $item->jam ? \Illuminate\Support\Str::substr($item->jam, 0, 5) . ' WIB' : '-' }}</td>
                            <td>{{ $item->lokasi ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $item->status === 'selesai' ? 'badge-light-success' : ($item->status === 'dibatalkan' ? 'badge-light-danger' : 'badge-light-primary') }}">
                                    {{ ucfirst($item->status ?? 'terjadwal') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#editAgendaModal{{ $item->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-light-danger" data-bs-toggle="modal" data-bs-target="#deleteAgendaModal{{ $item->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editAgendaModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.agenda.update', $item->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Agenda Kegiatan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body row g-3">
                                            <div class="col-md-8">
                                                <label for="edit-judul-{{ $item->id }}" class="form-label">Judul Kegiatan</label>
                                                <input type="text" id="edit-judul-{{ $item->id }}" name="judul" class="form-control" value="{{ old('judul', $item->judul) }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="edit-status-{{ $item->id }}" class="form-label">Status</label>
                                                <select id="edit-status-{{ $item->id }}" name="status" class="form-select">
                                                    <option value="terjadwal" {{ old('status', $item->status) === 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                                                    <option value="selesai" {{ old('status', $item->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                    <option value="dibatalkan" {{ old('status', $item->status) === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="edit-tanggal-{{ $item->id }}" class="form-label">Tanggal</label>
                                                <input type="date" id="edit-tanggal-{{ $item->id }}" name="tanggal" class="form-control" value="{{ old('tanggal', $item->tanggal) }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="edit-jam-{{ $item->id }}" class="form-label">Jam</label>
                                                <input type="time" id="edit-jam-{{ $item->id }}" name="jam" class="form-control" value="{{ old('jam', $item->jam) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="edit-lokasi-{{ $item->id }}" class="form-label">Lokasi</label>
                                                <input type="text" id="edit-lokasi-{{ $item->id }}" name="lokasi" class="form-control" value="{{ old('lokasi', $item->lokasi) }}" placeholder="Ruang 1 / Aula">
                                            </div>
                                            <div class="col-12">
                                                <label for="edit-keterangan-{{ $item->id }}" class="form-label">Keterangan</label>
                                                <textarea id="edit-keterangan-{{ $item->id }}" name="keterangan" class="form-control" rows="3" placeholder="Opsional">{{ old('keterangan', $item->keterangan) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="deleteAgendaModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.agenda.destroy', $item->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger">Hapus Agenda</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin menghapus agenda <strong>{{ $item->judul }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-muted">
                                <i class="bi bi-calendar2-x fs-2x d-block mb-3"></i>
                                Belum ada agenda kegiatan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahAgendaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.agenda.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Agenda Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label for="judul" class="form-label">Judul Kegiatan</label>
                        <input type="text" id="judul" name="judul" class="form-control" placeholder="Contoh: Rapat Koordinasi, Evaluasi Pembelajaran" value="{{ old('judul') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="terjadwal">Terjadwal</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="jam" class="form-label">Jam</label>
                        <input type="time" id="jam" name="jam" class="form-control" value="{{ old('jam') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" class="form-control" placeholder="Aula / ruang guru" value="{{ old('lokasi') }}">
                    </div>
                    <div class="col-12">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" class="form-control" rows="3" placeholder="Opsional">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection