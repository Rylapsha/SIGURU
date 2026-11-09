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

    <div class="card mb-3">
        <div class="card-body py-5">
            <div class="row g-4 align-items-end">
                <div class="col-12 col-md-6">
                    <label for="cari" class="form-label fw-semibold">
                        Cari Nama Guru
                    </label>
                    <input
                        type="text"
                        id="cari"
                        name="cari"
                        class="form-control form-control-solid"
                        placeholder="Masukkan nama guru..."
                        value="{{ request('cari') }}">
                </div>

                <div class="col-12 col-md-4">
                    <label for="mata_pelajaran" class="form-label fw-semibold">
                        Mata Pelajaran
                    </label>
                    <select
                        id="mata_pelajaran"
                        name="mata_pelajaran"
                        class="form-select form-select-solid">

                        <option value="">Semua Mata Pelajaran</option>

                        @foreach ($daftarMataPelajaran as $mapel)
                        <option
                            value="{{ $mapel }}"
                            @selected(request('mata_pelajaran')===$mapel)>
                            {{ $mapel }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex gap-2">
                    <a
                        href="{{ route('admin.guru.index') }}"
                        class="btn btn-light"
                        title="Reset pencarian dan filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-5">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahGuruModal">
            <i class="bi bi-plus-lg me-2"></i>Tambah Guru
        </button>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="fw-semibold text-gray-700">
            Daftar Guru
        </div>

        <span class="text-muted fs-7">
            {{ $gurus->total() }} guru ditemukan
        </span>
    </div>

    <div class="row g-5">
        @forelse ($gurus as $guru)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-body p-5">

                    <div class="d-flex align-items-center mb-5">
                        <div class="symbol symbol-55px me-4 flex-shrink-0">
                            @if ($guru->foto)
                            <img
                                src="{{ asset('storage/' . $guru->foto) }}"
                                alt="Foto {{ $guru->nama_lengkap }}"
                                class="object-fit-cover">
                            @else
                            <div class="symbol-label bg-light-primary text-primary fw-bold fs-3">
                                {{ strtoupper(substr($guru->nama_lengkap, 0, 1)) }}
                            </div>
                            @endif
                        </div>

                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-bold text-gray-900 fs-5 text-truncate">
                                {{ $guru->nama_lengkap }}
                            </div>

                            <div class="text-muted fs-7 text-truncate">
                                NIP: {{ $guru->nip ?: '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted fs-7 mb-1">
                            Mata Pelajaran
                        </div>

                        <div class="fw-semibold text-gray-800">
                            {{ $guru->mata_pelajaran ?: 'Belum diisi' }}
                        </div>
                    </div>

                    <div class="mb-5">
                        <div class="text-muted fs-7 mb-1">
                            Golongan
                        </div>

                        <div class="fw-semibold text-gray-800">
                            {{ $guru->golongan ?: 'Belum diisi' }}
                        </div>
                    </div>

                    @php
                    $jumlahLengkap = collect($guru->kelengkapanDokumen)
                    ->filter(fn ($item) => $item['status'])
                    ->count();

                    $persentase = ($jumlahLengkap / 6) * 100;
                    @endphp

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-muted fs-7">
                            Kategori tersedia
                        </span>

                        <span class="fw-bold text-gray-800">
                            {{ $jumlahLengkap }}/6
                        </span>
                    </div>

                    <div
                        class="progress h-6px mb-5"
                        role="progressbar"
                        aria-label="Kelengkapan dokumen {{ $guru->nama_lengkap }}"
                        aria-valuenow="{{ $jumlahLengkap }}"
                        aria-valuemin="0"
                        aria-valuemax="6">

                        <div
                            class="progress-bar bg-primary"
                            style="width: {{ $persentase }}%">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="button" class="btn btn-light-primary flex-fill" data-bs-toggle="modal" data-bs-target="#editGuruModal{{ $guru->id }}">
                            <i class="bi bi-pencil-square me-1"></i>Edit
                        </button>

                        <button type="button" class="btn btn-light-danger" data-bs-toggle="modal" data-bs-target="#deleteGuruModal{{ $guru->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>

                    <a
                        href="{{ route('admin.guru.detail', $guru->id) }}"
                        class="btn btn-light w-100 mt-3">
                        Lihat Selengkapnya
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

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
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-15">
                    <i class="bi bi-person-x fs-3x text-muted mb-4"></i>

                    <div class="fw-bold text-gray-800 fs-4 mb-2">
                        Data guru tidak ditemukan
                    </div>

                    <div class="text-muted">
                        Coba ubah kata pencarian atau filter mata pelajaran.
                    </div>

                    <a
                        href="{{ route('admin.guru.index') }}"
                        class="btn btn-light-primary mt-5">
                        Tampilkan Semua Guru
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

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