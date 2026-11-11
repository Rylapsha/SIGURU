@extends('layouts.admin')

@section('title', 'Master Mata Pelajaran')
@section('page_title', 'Master Mata Pelajaran')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-6">
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

    <div class="card dashboard-panel overflow-hidden">
        <div class="card-body pt-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 px-2">
                <button
                    type="button"
                    class="btn btn-light-primary dashboard-action-btn fw-semibold px-5 py-3 fs-6 mt-3"
                    style="min-height: 43px;"
                    data-bs-toggle="modal"
                    data-bs-target="#tambahMapelModal">
                    <i class="bi bi-plus-lg me-2"></i>
                    Tambah Mata Pelajaran
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-row-bordered table-row-gray-300 align-middle gy-5">
                    <thead>
                        <tr class="fw-bold text-uppercase fs-7">
                            <th class="min-w-100px text-start">No</th>
                            <th class="min-w-250px text-center">Nama Mata Pelajaran</th>
                            <th class="min-w-150px text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mataPelajarans as $index => $mapel)
                        <tr>
                            <td class="text-start">{{ $mataPelajarans->firstItem() + $index }}</td>
                            <td class="text-start">
                                <div class="d-flex justify-content-center">
                                    <span class="fw-semibold text-gray-800">{{ $mapel->nama }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button"
                                        class="btn btn-sm btn-light-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editMapelModal{{ $mapel->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <button type="button"
                                        class="btn btn-sm btn-light-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteMapelModal{{ $mapel->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editMapelModal{{ $mapel->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.mata-pelajaran.update', $mapel->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Mata Pelajaran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label for="edit-nama-{{ $mapel->id }}" class="form-label">Nama Mata Pelajaran</label>
                                            <input type="text"
                                                id="edit-nama-{{ $mapel->id }}"
                                                name="nama"
                                                class="form-control"
                                                value="{{ old('nama', $mapel->nama) }}"
                                                required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="deleteMapelModal{{ $mapel->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.mata-pelajaran.destroy', $mapel->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger">Hapus Mata Pelajaran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin menghapus mata pelajaran <strong>{{ $mapel->nama }}</strong>?
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
                            <td colspan="3" class="text-center py-10 text-muted">
                                <i class="bi bi-journal-x fs-2x d-block mb-3"></i>
                                Belum ada data mata pelajaran.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($mataPelajarans->hasPages())
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-6">
        <div class="text-muted fs-7">
            Menampilkan {{ $mataPelajarans->firstItem() }}–{{ $mataPelajarans->lastItem() }} dari {{ $mataPelajarans->total() }} data
        </div>
        <div>{{ $mataPelajarans->links() }}</div>
    </div>
    @endif
</div>

<div class="modal fade" id="tambahMapelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.mata-pelajaran.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mata Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="nama-mapel" class="form-label">Nama Mata Pelajaran</label>
                    <input type="text" id="nama-mapel" name="nama" class="form-control" placeholder="Contoh: Informatika" value="{{ old('nama') }}" required>
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