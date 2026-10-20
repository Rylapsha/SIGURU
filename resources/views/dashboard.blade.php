@extends('layouts.app')

@section('title', 'Dashboard - SIGURU')
@section('page_title', 'Dashboard')

@section('content')

<div class="post d-flex flex-column-fluid" id="kt_post">
    <div class="container-fluid">

        {{-- Page Heading --}}
        <div class="d-flex flex-wrap flex-stack m-8">
            <div>
                <h1 class="fw-bold text-gray-900 mb-2">
                    Selamat Datang, {{ Auth::user()->nama_lengkap ?? 'Guru' }}!
                </h1>

                <span class="text-gray-400 fw-semibold fs-6">
                    Kelola administrasi Anda secara mudah dan sistematis melalui
                    Sistem Informasi Direktori Guru SMP Negeri 2 Purwakarta.
                </span>
            </div>
        </div>

        {{-- Search Targets --}}
        <div id="dashboard-search-targets" class="d-none">
            <div data-search-target="profil"
                data-search-keywords="profil, profile, biodata, data saya">
                {{ route('profil') }}
            </div>

            <div data-search-target="dokumen"
                data-search-keywords="dokumen, file, modul, atp, ap, prota, prosem, bank soal, materi, arsip">
                {{ route('dokumen.index') }}
            </div>

            <div data-search-target="supervisi"
                data-search-keywords="supervisi, pengawasan, monitoring, evaluasi">
                {{ url('#supervisi-status') }}
            </div>

            <div data-search-target="agenda"
                data-search-keywords="agenda, jadwal, kegiatan, rapat, pengumpulan, evaluasi pembelajaran, supervisi guru">
                {{ url('#agenda-mendatang') }}
            </div>
        </div>


        {{-- Dashboard Layout --}}
        <div class="row g-5 g-xl-8 align-items-stretch">

            {{-- ====================================== --}}
            {{-- KOLOM KIRI --}}
            {{-- ====================================== --}}
            <div class="col-xl-6 d-flex flex-column gap-5">

                {{-- ====================================== --}}
                {{-- KELENGKAPAN DOKUMEN --}}
                {{-- ====================================== --}}
                <div class="card">

                    {{-- Header --}}
                    <div class="card-header border-0 pt-6">

                        <div class="d-flex align-items-center gap-3">
                            <div class="symbol symbol-45px flex-shrink-0">
                                <div class="symbol-label bg-light-primary rounded-2">
                                    <i class="bi bi-folder2-open fs-2 text-primary"></i>
                                </div>
                            </div>

                            <div>
                                <h3 class="fw-bold text-gray-900 mb-1">
                                    Kelengkapan Dokumen
                                </h3>

                                <span class="text-gray-500 fs-7">
                                    Status kelengkapan dokumen Anda
                                </span>
                            </div>
                        </div>

                        <div class="card-toolbar">
                            <a href="{{ route('dokumen.index') }}"
                                class="btn btn-sm btn-light-primary dashboard-action-btn">
                                <span>Lihat Selengkapnya</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>

                    </div>

                    {{-- Body --}}
                    <div class="card-body pt-4">

                        {{-- Kepegawaian --}}
                        <div class="mb-8">

                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold text-gray-700">
                                    Kepegawaian
                                </span>

                                <span class="text-gray-500">
                                    4/4
                                </span>
                            </div>

                            <div class="progress h-8px">
                                <div class="progress-bar bg-success"
                                    role="progressbar"
                                    style="width: 100%;"
                                    aria-valuenow="100"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>

                            <div class="text-success fs-7 mt-2">
                                <i class="bi bi-check-circle me-1"></i>
                                Lengkap
                            </div>

                        </div>

                        {{-- Pendidikan --}}
                        <div class="mb-8">

                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold text-gray-700">
                                    Pendidikan
                                </span>

                                <span class="text-gray-500">
                                    3/4
                                </span>
                            </div>

                            <div class="progress h-8px">
                                <div class="progress-bar bg-warning"
                                    role="progressbar"
                                    style="width: 75%;"
                                    aria-valuenow="75"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>

                            <div class="text-warning fs-7 mt-2">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                1 dokumen belum tersedia
                            </div>

                        </div>

                        {{-- Pembelajaran --}}
                        <div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold text-gray-700">
                                    Pembelajaran
                                </span>

                                <span class="text-gray-500">
                                    4/6
                                </span>
                            </div>

                            <div class="progress h-8px">
                                <div class="progress-bar bg-warning"
                                    role="progressbar"
                                    style="width: 66.67%;"
                                    aria-valuenow="67"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>

                            <div class="text-warning fs-7 mt-2">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                2 dokumen belum tersedia
                            </div>

                        </div>

                    </div>
                </div>


                {{-- ====================================== --}}
                {{-- RINGKASAN SUPERVISI --}}
                {{-- ====================================== --}}
                <div class="card" id="supervisi-status">

                    {{-- Header --}}
                    <div class="card-header border-0 pt-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="symbol symbol-45px flex-shrink-0">
                                <div class="symbol-label bg-light-primary rounded-2">
                                    <i class="bi bi-person-workspace fs-2 text-primary"></i>
                                </div>
                            </div>

                            <div>
                                <h3 class="fw-bold text-gray-900 mb-1">
                                    Ringkasan Supervisi
                                </h3>

                                <span class="text-gray-500 fs-7">
                                    Pantau jadwal supervisi
                                    {{ strtolower($semesterAktif ?? 'ganjil') === 'ganjil'
                        ? 'semester ganjil'
                        : 'semester genap' }}
                                    tahun ajaran {{ $tahunAjaran ?? '2026/2027' }}
                                </span>
                            </div>
                        </div>

                        <div class="card-toolbar">
                            <a href="{{ route('guru.supervisi') }}"
                                class="btn btn-sm btn-light-primary dashboard-action-btn">
                                <span>Lihat Selengkapnya</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="card-body pt-4">
                        <div class="row g-4">

                            {{-- Semester Ganjil --}}
                            <div class="col-12">
                                <div class="rounded-3 p-5 bg-light-primary h-100">
                                    <div class="d-flex align-items-start gap-4">

                                        <div class="symbol symbol-45px flex-shrink-0">
                                            <div class="symbol-label bg-white rounded-2">
                                                <i class="bi bi-calendar-week fs-2 text-primary"></i>
                                            </div>
                                        </div>

                                        <div class="flex-grow-1">

                                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                                <h4 class="fw-bold text-gray-900 mb-0">
                                                    Semester Ganjil
                                                </h4>

                                                @if(isset($jadwalGanjil) && $jadwalGanjil)
                                                @php
                                                $statusGanjil = strtolower(
                                                $jadwalGanjil->status ?? 'terjadwal'
                                                );
                                                @endphp

                                                @if($statusGanjil === 'selesai')
                                                <span class="badge badge-light-success">
                                                    Selesai
                                                </span>
                                                @elseif(in_array($statusGanjil, ['terjadwal', 'dijadwalkan']))
                                                <span class="badge badge-light-primary">
                                                    Terjadwal
                                                </span>
                                                @else
                                                <span class="badge badge-light-warning">
                                                    {{ $statusLabels[$statusGanjil] ?? ucfirst(str_replace('_', ' ', $statusGanjil)) }}
                                                </span>
                                                @endif
                                                @endif
                                            </div>

                                            @if(isset($jadwalGanjil) && $jadwalGanjil)
                                            <div class="d-flex align-items-center gap-2 text-gray-700 fs-7">
                                                <i class="bi bi-calendar3 text-gray-500"></i>

                                                <span>
                                                    {{ $jadwalGanjil->tanggal
                                            ? \Carbon\Carbon::parse($jadwalGanjil->tanggal)->translatedFormat('d F Y')
                                            : 'Tanggal belum ditentukan' }}
                                                </span>
                                            </div>
                                            @else
                                            <span class="text-gray-500 fs-7">
                                                Belum ada jadwal supervisi untuk semester ini.
                                            </span>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Semester Genap --}}
                            <div class="col-12">
                                <div class="rounded-3 p-5 bg-light-primary h-100">
                                    <div class="d-flex align-items-start gap-4">

                                        <div class="symbol symbol-45px flex-shrink-0">
                                            <div class="symbol-label bg-white rounded-2">
                                                <i class="bi bi-calendar-week fs-2 text-primary"></i>
                                            </div>
                                        </div>

                                        <div class="flex-grow-1">

                                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                                <h4 class="fw-bold text-gray-900 mb-0">
                                                    Semester Genap
                                                </h4>

                                                @if(isset($jadwalGenap) && $jadwalGenap)
                                                @php
                                                $statusGenap = strtolower(
                                                $jadwalGenap->status ?? 'terjadwal'
                                                );
                                                @endphp

                                                @if($statusGenap === 'selesai')
                                                <span class="badge badge-light-success">
                                                    Selesai
                                                </span>
                                                @elseif(in_array($statusGenap, ['terjadwal', 'dijadwalkan']))
                                                <span class="badge badge-light-primary">
                                                    Terjadwal
                                                </span>
                                                @else
                                                <span class="badge badge-light-warning">
                                                    {{ $statusLabels[$statusGenap] ?? ucfirst(str_replace('_', ' ', $statusGenap)) }}
                                                </span>
                                                @endif
                                                @endif
                                            </div>

                                            @if(isset($jadwalGenap) && $jadwalGenap)
                                            <div class="d-flex align-items-center gap-2 text-gray-700 fs-7">
                                                <i class="bi bi-calendar3 text-gray-500"></i>

                                                <span>
                                                    {{ $jadwalGenap->tanggal
                                            ? \Carbon\Carbon::parse($jadwalGenap->tanggal)->translatedFormat('d F Y')
                                            : 'Tanggal belum ditentukan' }}
                                                </span>
                                            </div>
                                            @else
                                            <span class="text-gray-500 fs-7">
                                                Belum ada jadwal supervisi untuk semester ini.
                                            </span>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>


            {{-- ====================================== --}}
            {{-- KOLOM KANAN: AGENDA MENDATANG --}}
            {{-- ====================================== --}}
            <div class="col-xl-6" id="agenda-mendatang">
                <div class="card h-100">

                    {{-- Header --}}
                    <div class="card-header border-0 pt-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="symbol symbol-45px flex-shrink-0">
                                <div class="symbol-label bg-light-primary rounded-2">
                                    <i class="bi bi-calendar-range fs-2 text-primary"></i>
                                </div>
                            </div>

                            <div>
                                <h3 class="fw-bold text-gray-900 mb-1">
                                    Agenda Mendatang
                                </h3>

                                <span class="text-gray-500 fs-7">
                                    Kegiatan dan jadwal yang akan datang
                                </span>
                            </div>
                        </div>
                    </div>


                    {{-- Body --}}
                    <div class="card-body pt-4">

                        {{-- Agenda 1 --}}
                        <div class="d-flex align-items-center mb-7">

                            <div class="symbol symbol-50px symbol-circle bg-light-primary me-5 flex-shrink-0">
                                <div class="text-primary fw-bold text-center">
                                    <div class="fs-5">08</div>
                                    <div class="fs-8">OKT</div>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <span class="fw-bold text-gray-900 d-block fs-6">
                                    Supervisi Guru
                                </span>

                                <span class="text-gray-500 fs-7">
                                    <i class="bi bi-clock me-1"></i>
                                    08:00 WIB
                                </span>
                            </div>

                        </div>


                        {{-- Agenda 2 --}}
                        <div class="separator separator-dashed my-5"></div>

                        <div class="d-flex align-items-center mb-7">

                            <div class="symbol symbol-50px symbol-circle bg-light-primary me-5 flex-shrink-0">
                                <div class="text-primary fw-bold text-center">
                                    <div class="fs-5">12</div>
                                    <div class="fs-8">OKT</div>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <span class="fw-bold text-gray-900 d-block fs-6">
                                    Pengumpulan Dokumen
                                </span>

                                <span class="text-gray-500 fs-7">
                                    <i class="bi bi-clock me-1"></i>
                                    23:59 WIB
                                </span>
                            </div>

                        </div>


                        {{-- Agenda 3 --}}
                        <div class="separator separator-dashed my-5"></div>

                        <div class="d-flex align-items-center mb-7">

                            <div class="symbol symbol-50px symbol-circle bg-light-primary me-5 flex-shrink-0">
                                <div class="text-primary fw-bold text-center">
                                    <div class="fs-5">16</div>
                                    <div class="fs-8">OKT</div>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <span class="fw-bold text-gray-900 d-block fs-6">
                                    Rapat Guru
                                </span>

                                <span class="text-gray-500 fs-7">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    Ruang Guru
                                </span>
                            </div>

                        </div>


                        {{-- Agenda 4 --}}
                        <div class="separator separator-dashed my-5"></div>

                        <div class="d-flex align-items-center">

                            <div class="symbol symbol-50px symbol-circle bg-light-primary me-5 flex-shrink-0">
                                <div class="text-primary fw-bold text-center">
                                    <div class="fs-5">21</div>
                                    <div class="fs-8">OKT</div>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <span class="fw-bold text-gray-900 d-block fs-6">
                                    Evaluasi Pembelajaran
                                </span>

                                <span class="text-gray-500 fs-7">
                                    <i class="bi bi-clock me-1"></i>
                                    09:00 WIB
                                </span>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
</div>


{{-- ====================================== --}}
{{-- STYLE BUTTON KONSISTEN --}}
{{-- ====================================== --}}
<style>
    .dashboard-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 34px;
        padding: 7px 12px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.4;
        white-space: nowrap;
    }

    .dashboard-action-btn i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        line-height: 1;
    }

    #supervisi-status,
    #agenda-mendatang {
        scroll-margin-top: 100px;
    }

    @media (max-width: 767.98px) {
        .container-fluid>.d-flex.flex-stack {
            margin: 1rem !important;
        }

        .dashboard-action-btn {
            min-height: 32px;
            padding: 6px 10px;
        }
    }
</style>


@endsection


@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('headerGlobalSearch');
        const searchForm = document.getElementById('headerSearchForm');

        if (!searchInput || !searchForm) {
            return;
        }

        const mappings = [{
                key: 'profil',
                match: ['profil', 'profile', 'biodata', 'data saya'],
                url: @json(route('profil'))
            },
            {
                key: 'dokumen',
                match: [
                    'dokumen', 'file', 'modul', 'atp', 'ap',
                    'prota', 'prosem', 'bank soal', 'materi', 'arsip'
                ],
                url: @json(route('dokumen.index'))
            },
            {
                key: 'supervisi',
                match: ['supervisi', 'pengawasan', 'monitoring', 'evaluasi'],
                url: '#supervisi-status'
            },
            {
                key: 'agenda',
                match: [
                    'agenda', 'jadwal', 'kegiatan', 'rapat',
                    'pengumpulan', 'evaluasi pembelajaran', 'supervisi guru'
                ],
                url: '#agenda-mendatang'
            }
        ];

        const normalize = (value) => (value || '').toLowerCase().trim();

        const handleSearch = () => {
            const query = normalize(searchInput.value);

            if (!query) {
                return;
            }

            const matched = mappings.find((item) =>
                item.match.some((keyword) => query.includes(keyword))
            );

            if (!matched) {
                return;
            }

            if (matched.url.startsWith('#')) {
                const target = document.querySelector(matched.url);

                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    target.classList.add(
                        'border',
                        'border-primary',
                        'rounded'
                    );

                    setTimeout(() => {
                        target.classList.remove(
                            'border',
                            'border-primary',
                            'rounded'
                        );
                    }, 2000);
                }

                searchForm.reset();
            } else {
                window.location.href = matched.url;
            }
        };

        searchInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                handleSearch();
            }
        });
    });
</script>
@endsection