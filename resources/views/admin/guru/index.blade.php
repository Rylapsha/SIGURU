@extends('layouts.admin')

@section('title', 'Data Guru')
@section('page_title', 'Data Guru')

@section('content')
<div class="container-fluid px-0">

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
        <div class="card-body p-5">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">
                <h3 class="m-5">Daftar Guru</h3>
            </div>

            <form action="{{ route('admin.guru.index') }}" method="GET" class="mb-5">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-5 px-2">

                    {{-- Tambah Guru di Kiri --}}
                    <button
                        type="button"
                        class="btn btn-light-primary dashboard-action-btn fw-semibold px-5 py-3 fs-6"
                        style="min-height: 43px;"
                        data-bs-toggle="modal"
                        data-bs-target="#tambahGuruModal">
                        <i class="bi bi-plus-lg me-2"></i>
                        Tambah Data Guru
                    </button>

                    {{-- Search dan Filter di Kanan --}}
                    <div class="d-flex align-items-center gap-2">

                        <div class="input-group" style="width: 320px;">
                            <input
                                type="search"
                                name="cari"
                                class="form-control form-control-solid"
                                placeholder="Masukkan nama guru..."
                                value="{{ request('cari') }}">

                            <button type="submit" class="btn btn-light-primary">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>

                        <div class="dropdown">
                            <button
                                type="button"
                                class="btn btn-light btn-icon"
                                data-bs-toggle="dropdown"
                                data-bs-auto-close="outside"
                                title="Filter mata pelajaran">
                                <i class="bi bi-funnel fs-4"></i>
                            </button>

                            <div class="dropdown-menu dropdown-menu-end p-4 shadow-sm"
                                style="width: 260px;">

                                <div class="fw-bold text-gray-800 mb-3">
                                    Filter Mata Pelajaran
                                </div>

                                <select
                                    name="mata_pelajaran"
                                    class="form-select form-select-solid mb-3">

                                    <option value="">Semua Mata Pelajaran</option>

                                    @foreach ($daftarMataPelajaran as $mapel)
                                    <option
                                        value="{{ $mapel }}"
                                        @selected(request('mata_pelajaran')===$mapel)>
                                        {{ $mapel }}
                                    </option>
                                    @endforeach
                                </select>

                                <div class="d-flex gap-2">
                                    <a
                                        href="{{ route('admin.guru.index', ['cari' => request('cari')]) }}"
                                        class="btn btn-light btn-sm flex-grow-1">
                                        Reset
                                    </a>

                                    <button type="submit"
                                        class="btn btn-primary btn-sm flex-grow-1">
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-sm table-row-bordered table-row-gray-300 align-middle gy-4">
                    <thead>
                        <tr class="fw-bold text-uppercase fs-7 text-muted">
                            <th class="min-w-60px">No</th>
                            <th class="min-w-250px">Nama Guru</th>
                            <th class="min-w-180px">Mata Pelajaran</th>
                            <th class="min-w-120px">Golongan</th>
                            <th class="min-w-180px">Kelengkapan Dokumen</th>
                            <th class="min-w-150px text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gurus as $index => $guru)
                        @php
                        $jumlahLengkap = collect($guru->kelengkapanDokumen)
                        ->filter(fn ($item) => $item['status'])
                        ->count();
                        $persentase = ($jumlahLengkap / 6) * 100;
                        @endphp
                        <tr>
                            <td>{{ $gurus->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="min-w-0">
                                        <div class="fw-semibold text-gray-900">{{ $guru->nama_lengkap }}</div>
                                        <div class="text-muted fs-7">NIP: {{ $guru->nip ?: '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-gray-800">{{ $guru->mata_pelajaran ?: 'Belum diisi' }}</td>
                            <td class="text-gray-800">{{ $guru->golongan ?: 'Belum diisi' }}</td>
                            <td>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted fs-7">Kategori tersedia</span>
                                    <span class="fw-semibold text-gray-800">{{ $jumlahLengkap }}/6</span>
                                </div>
                                <div
                                    class="progress h-6px"
                                    role="progressbar"
                                    aria-label="Kelengkapan dokumen {{ $guru->nama_lengkap }}"
                                    aria-valuenow="{{ $jumlahLengkap }}"
                                    aria-valuemin="0"
                                    aria-valuemax="6">
                                    <div class="progress-bar bg-primary" style="width: {{ $persentase }}%"></div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a
                                        href="{{ route('admin.guru.detail', $guru->id) }}"
                                        class="btn btn-sm btn-light"
                                        title="Lihat detail"
                                        aria-label="Lihat detail {{ $guru->nama_lengkap }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editGuruModal{{ $guru->id }}"
                                        title="Edit guru"
                                        aria-label="Edit {{ $guru->nama_lengkap }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteGuruModal{{ $guru->id }}"
                                        title="Hapus guru"
                                        aria-label="Hapus {{ $guru->nama_lengkap }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-10">
                                <i class="bi bi-person-x fs-2x text-muted d-block mb-3"></i>
                                <div class="fw-semibold text-gray-800 mb-1">Data guru tidak ditemukan</div>
                                <div class="text-muted">Coba ubah kata pencarian atau filter mata pelajaran.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach ($gurus as $guru)
    <div class="modal fade" id="editGuruModal{{ $guru->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.guru.update', $guru->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Guru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-nip-{{ $guru->id }}" class="form-label">NIP</label>
                            <input type="text" id="edit-nip-{{ $guru->id }}" name="nip" class="form-control" value="{{ old('nip', $guru->nip) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-nama-{{ $guru->id }}" class="form-label">Nama Lengkap</label>
                            <input type="text" id="edit-nama-{{ $guru->id }}" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $guru->nama_lengkap) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-golongan-{{ $guru->id }}" class="form-label">Golongan</label>
                            <input type="text" id="edit-golongan-{{ $guru->id }}" name="golongan" class="form-control" value="{{ old('golongan', $guru->golongan) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-mapel-{{ $guru->id }}" class="form-label">Mata Pelajaran</label>
                            <select id="edit-mapel-{{ $guru->id }}" name="mata_pelajaran" class="form-select" required>
                                <option value="">Pilih mata pelajaran</option>
                                @foreach ($daftarMataPelajaran as $mapel)
                                <option value="{{ $mapel }}" @selected(old('mata_pelajaran', $guru->mata_pelajaran) === $mapel)>
                                    {{ $mapel }}
                                </option>
                                @endforeach
                            </select>
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

    <div class="modal fade" id="deleteGuruModal{{ $guru->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.guru.destroy', $guru->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">Hapus Guru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus guru <strong>{{ $guru->nama_lengkap }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    @if ($gurus->hasPages())
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-6">

        <div class="text-muted fs-7">
            Menampilkan {{ $gurus->firstItem() }}–{{ $gurus->lastItem() }}
            dari {{ $gurus->total() }} guru
        </div>

        <div>
            {{ $gurus->links() }}
        </div>

    </div>
    @endif

</div>

<div class="modal fade" id="tambahGuruModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.guru.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" id="nip" name="nip" class="form-control" value="{{ old('nip') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="golongan" class="form-label">Golongan</label>
                        <input type="text" id="golongan" name="golongan" class="form-control" value="{{ old('golongan') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="mata_pelajaran" class="form-label">Mata Pelajaran</label>
                        <select id="mata_pelajaran" name="mata_pelajaran" class="form-select" required>
                            <option value="">Pilih mata pelajaran</option>
                            @foreach ($daftarMataPelajaran as $mapel)
                            <option value="{{ $mapel }}" @selected(old('mata_pelajaran')===$mapel)>
                                {{ $mapel }}
                            </option>
                            @endforeach
                        </select>
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