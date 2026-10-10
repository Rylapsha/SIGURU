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
                <a href="{{ route('dokumen.modul-ajar') }}"
                    class="card card-flush h-100 text-decoration-none">
                    <div class="card-body p-6">
                        <div class="symbol symbol-45px mb-4">
                            <div class="symbol-label bg-light-primary">
                                <i class="bi bi-journal-text fs-2x text-primary"></i>
                            </div>
                        </div>

                        <h4 class="fw-bold text-gray-800 mb-2">
                            Modul Ajar
                        </h4>
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

            {{-- ATP --}}
            <div class="col-md-6 col-xl-4" data-searchable="{{ strtolower('ATP') }}">
                <a href="{{ route('dokumen.kategori', 'atp') }}"
                    class="card card-flush h-100 text-decoration-none">
                    <div class="card-body p-6">
                        <div class="symbol symbol-45px mb-4">
                            <div class="symbol-label bg-light-success">
                                <i class="bi bi-diagram-3 fs-2x text-success"></i>
                            </div>
                        </div>

                        <h4 class="fw-bold text-gray-800 mb-2">ATP</h4>
                        <p class="text-muted mb-4">
                            Akses dan kelola dokumen Alur Tujuan Pembelajaran.
                        </p>

                        <span class="text-primary fw-semibold">
                            Buka ATP <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>

            {{-- AP --}}
            <div class="col-md-6 col-xl-4" data-searchable="{{ strtolower('AP') }}">
                <a href="{{ route('dokumen.kategori', 'ap') }}"
                    class="card card-flush h-100 text-decoration-none">
                    <div class="card-body p-6">
                        <div class="symbol symbol-45px mb-4">
                            <div class="symbol-label bg-light-warning">
                                <i class="bi bi-file-earmark-text fs-2x text-warning"></i>
                            </div>
                        </div>

                        <h4 class="fw-bold text-gray-800 mb-2">AP</h4>
                        <p class="text-muted mb-4">
                            Akses dan kelola dokumen Alur Pembelajaran.
                        </p>

                        <span class="text-primary fw-semibold">
                            Buka AP <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>

            {{-- PROTA --}}
            <div class="col-md-6 col-xl-4" data-searchable="{{ strtolower('PROTA') }}">
                <a href="{{ route('dokumen.kategori', 'prota') }}"
                    class="card card-flush h-100 text-decoration-none">
                    <div class="card-body p-6">
                        <div class="symbol symbol-45px mb-4">
                            <div class="symbol-label bg-light-info">
                                <i class="bi bi-calendar3 fs-2x text-info"></i>
                            </div>
                        </div>

                        <h4 class="fw-bold text-gray-800 mb-2">PROTA</h4>
                        <p class="text-muted mb-4">
                            Akses dan kelola dokumen Program Tahunan pembelajaran.
                        </p>

                        <span class="text-primary fw-semibold">
                            Buka PROTA <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </a>
            </div>

            {{-- PROSEM --}}
            <div class="col-md-6 col-xl-4" data-searchable="{{ strtolower('PROSEM') }}">
                <a href="{{ route('dokumen.kategori', 'prosem') }}"
                    class="card card-flush h-100 text-decoration-none">
                    <div class="card-body p-6">
                        <div class="symbol symbol-45px mb-4">
                            <div class="symbol-label bg-light-danger">
                                <i class="bi bi-calendar-week fs-2x text-danger"></i>
                            </div>
                        </div>

                        <h4 class="fw-bold text-gray-800 mb-2">PROSEM</h4>
                        <p class="text-muted mb-4">
                            Akses dan kelola dokumen Program Semester pembelajaran.
                        </p>

                        <span class="text-primary fw-semibold">
                            Buka PROSEM <i class="bi bi-arrow-right ms-1"></i>
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
                        <div class="symbol symbol-45px mb-4">
                            <div class="symbol-label bg-light-primary">
                                <i class="bi bi-list-check fs-2x text-primary"></i>
                            </div>
                        </div>

                        <h4 class="fw-bold text-gray-800 mb-2">Kisi-Kisi</h4>
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
                        <div class="symbol symbol-45px mb-4">
                            <div class="symbol-label bg-light-warning">
                                <i class="bi bi-file-earmark-ruled fs-2x text-warning"></i>
                            </div>
                        </div>

                        <h4 class="fw-bold text-gray-800 mb-2">Naskah Soal</h4>
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
                        <div class="symbol symbol-45px mb-4">
                            <div class="symbol-label bg-light-success">
                                <i class="bi bi-bar-chart-line fs-2x text-success"></i>
                            </div>
                        </div>

                        <h4 class="fw-bold text-gray-800 mb-2">
                            Analisis Butir Soal
                        </h4>
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