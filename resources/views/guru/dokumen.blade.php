@extends('layouts.app')

@section('page_title', 'Dokumen')

@section('content')
<div class="container-fluid">

    {{-- Notifikasi --}}
    @if(session('success'))
    <div class="alert alert-success mb-5">
        {{ session('success') }}
    </div>
    @endif

    {{-- Dokumen Pembelajaran --}}
    <div class="mb-7 mt-10">
        <h3 class="fw-bold text-gray-800 mb-4">
            Dokumen Pembelajaran
        </h3>

        <div class="row g-5">

            {{-- Modul Ajar --}}
            <div class="col-md-6 col-xl-4" data-searchable="{{ strtolower('Modul Ajar') }}">
                <a href="{{ route('dokumen.kategori', 'modul-ajar') }}"
                    class="card card-flush h-100 text-decoration-none">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div class="symbol symbol-45px flex-shrink-0">
                                <div class="symbol-label bg-light-primary">
                                    <i class="bi bi-journal-text fs-2x text-primary"></i>
                                </div>
                            </div>

                            <h4 class="fw-bold text-gray-800 mb-0">
                                Modul Ajar
                            </h4>
                        </div>
                        <p class="text-muted mb-4">
                            Akses dan kelola dokumen Modul Ajar.
                        </p>

                        <span class="text-primary fw-semibold">
                            Buka Modul Ajar
                            <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>

            {{-- ATP & AP --}}
            <div class="col-md-6 col-xl-4" data-searchable="{{ strtolower('ATP & AP') }}">
                <a href="{{ route('dokumen.kategori', 'atp-ap') }}"
                    class="card card-flush h-100 text-decoration-none">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div class="symbol symbol-45px flex-shrink-0">
                                <div class="symbol-label bg-light-success">
                                    <i class="bi bi-diagram-3 fs-2x text-success"></i>
                                </div>
                            </div>

                            <h4 class="fw-bold text-gray-800 mb-0">ATP & AP</h4>
                        </div>
                        <p class="text-muted mb-4">
                            Akses dan kelola dokumen ATP dan AP.
                        </p>

                        <span class="text-primary fw-semibold">
                            Buka ATP & AP <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>

            {{-- PROTA & PROSEM --}}
            <div class="col-md-6 col-xl-4" data-searchable="{{ strtolower('PROTA & PROSEM') }}">
                <a href="{{ route('dokumen.kategori', 'prota-prosem') }}"
                    class="card card-flush h-100 text-decoration-none">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div class="symbol symbol-45px flex-shrink-0">
                                <div class="symbol-label bg-light-info">
                                    <i class="bi bi-calendar3 fs-2x text-info"></i>
                                </div>
                            </div>

                            <h4 class="fw-bold text-gray-800 mb-0">PROTA & PROSEM</h4>
                        </div>
                        <p class="text-muted mb-4">
                            Akses dan kelola dokumen Program Tahunan dan Program Semester pembelajaran.
                        </p>

                        <span class="text-primary fw-semibold">
                            Buka PROTA & PROSEM <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>

        </div>
    </div>

    {{-- Bank Soal --}}
    <div class="mb-7">
        <h3 class="fw-bold text-gray-800 mb-4">Bank Soal</h3>

        <div class="row g-5">

            {{-- Kisi-Kisi --}}
            <div class="col-md-6 col-xl-4" data-searchable="{{ strtolower('Kisi-Kisi') }}">
                <a href="{{ route('dokumen.kategori', 'kisi-kisi') }}"
                    class="card card-flush h-100 text-decoration-none">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div class="symbol symbol-45px flex-shrink-0">
                                <div class="symbol-label bg-light-primary">
                                    <i class="bi bi-list-check fs-2x text-primary"></i>
                                </div>
                            </div>

                            <h4 class="fw-bold text-gray-800 mb-0">Kisi-Kisi</h4>
                        </div>
                        <p class="text-muted mb-4">
                            Akses dan kelola dokumen kisi-kisi soal.
                        </p>

                        <span class="text-primary fw-semibold">
                            Buka Kisi-Kisi <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>

            {{-- Naskah Soal --}}
            <div class="col-md-6 col-xl-4" data-searchable="{{ strtolower('Naskah Soal') }}">
                <a href="{{ route('dokumen.kategori', 'naskah-soal') }}"
                    class="card card-flush h-100 text-decoration-none">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div class="symbol symbol-45px flex-shrink-0">
                                <div class="symbol-label bg-light-warning">
                                    <i class="bi bi-file-earmark-ruled fs-2x text-warning"></i>
                                </div>
                            </div>

                            <h4 class="fw-bold text-gray-800 mb-0">Naskah Soal</h4>
                        </div>
                        <p class="text-muted mb-4">
                            Kelola dokumen naskah soal.
                        </p>

                        <span class="text-primary fw-semibold">
                            Buka Naskah Soal <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>

            {{-- Analisis Butir Soal --}}
            <div class="col-md-6 col-xl-4" data-searchable="{{ strtolower('Analisis Butir Soal') }}">
                <a href="{{ route('dokumen.kategori', 'analisis-butir-soal') }}"
                    class="card card-flush h-100 text-decoration-none">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div class="symbol symbol-45px flex-shrink-0">
                                <div class="symbol-label bg-light-success">
                                    <i class="bi bi-bar-chart-line fs-2x text-success"></i>
                                </div>
                            </div>

                            <h4 class="fw-bold text-gray-800 mb-0">
                                Analisis Butir Soal
                            </h4>
                        </div>
                        <p class="text-muted mb-4">
                            Akses dan kelola hasil analisis butir soal.
                        </p>

                        <span class="text-primary fw-semibold">
                            Buka Analisis
                            <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>

        </div>
    </div>

</div>
@endsection