
@extends('layouts.admin')

@section('title', 'Data Guru')
@section('page_title', 'Data Guru')

@section('content')
<div class="container-fluid px-0">

    {{-- Judul halaman --}}
    <div class="mb-6">
        <h2 class="fw-bold text-gray-900 mb-2">Data Guru</h2>
        <div class="text-muted fs-6">
            Kelola data guru dan pantau kelengkapan dokumen pembelajaran.
        </div>
    </div>

    {{-- Pencarian dan filter --}}
    <div class="card mb-6">
        <div class="card-body py-5">
            <form method="GET" action="{{ route('admin.guru.index') }}">
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
                                    @selected(request('mata_pelajaran') === $mapel)>
                                    {{ $mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search me-1"></i>
                            Cari
                        </button>

                        <a
                            href="{{ route('admin.guru.index') }}"
                            class="btn btn-light"
                            title="Reset pencarian dan filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Informasi jumlah guru --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="fw-semibold text-gray-700">
            Daftar Guru
        </div>

        <span class="text-muted fs-7">
            {{ $gurus->total() }} guru ditemukan
        </span>
    </div>

    {{-- Kartu guru --}}
    <div class="row g-5">
        @forelse ($gurus as $guru)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card h-100">
                    <div class="card-body p-5">

                        {{-- Foto dan identitas guru --}}
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

                        {{-- Mata pelajaran --}}
                        <div class="mb-3">
                            <div class="text-muted fs-7 mb-1">
                                Mata Pelajaran
                            </div>

                            <div class="fw-semibold text-gray-800">
                                {{ $guru->mata_pelajaran ?: 'Belum diisi' }}
                            </div>
                        </div>

                        {{-- Golongan --}}
                        <div class="mb-5">
                            <div class="text-muted fs-7 mb-1">
                                Golongan
                            </div>

                            <div class="fw-semibold text-gray-800">
                                {{ $guru->golongan ?: 'Belum diisi' }}
                            </div>
                        </div>

                        {{-- Ringkasan kelengkapan dokumen --}}
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

                        {{-- Menuju halaman detail terpisah --}}
                        <a
                            href="{{ route('admin.guru.detail', $guru->id) }}"
                            class="btn btn-light-primary w-100">

                            Lihat Selengkapnya
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>

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

    {{-- Pagination --}}
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
@endsection
