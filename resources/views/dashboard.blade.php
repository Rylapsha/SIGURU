@extends('layouts.app')

@section('title', 'Dashboard - SIGURU')
@section('page_title', 'Dashboard',)

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
                    Kelola administrasi anda secara mudah dan sistematis melalui Sistem Informasi Direktori Guru SMP Negeri 2 Purwakarta.
                </span>
            </div>
        </div>

        <div id="dashboard-search-targets" class="d-none">
            <div data-search-target="profil" data-search-keywords="profil, profile, biodata, data saya">
                {{ route('profil') }}
            </div>
            <div data-search-target="dokumen" data-search-keywords="dokumen, file, modul, atp, ap, prota, prosem, bank soal, materi, arsip">
                {{ route('dokumen.index') }}
            </div>
            <div data-search-target="supervisi" data-search-keywords="supervisi, pengawasan, monitoring, evaluasi">
                {{ url('#supervisi-status') }}
            </div>
            <div data-search-target="agenda" data-search-keywords="agenda, jadwal, kegiatan, rapat, pengumpulan, evaluasi pembelajaran, supervisi guru">
                {{ url('#agenda-mendatang') }}
            </div>
        </div>

        {{-- Dashboard Cards --}}
        <div class="row g-5 g-xl-8">

            {{-- Kelengkapan Dokumen --}}
            <div class="col-xl-6">
                <div class="card h-100">

                    <div class="card-header border-0 pt-6">
                        <div>
                            <h3 class="card-title fw-bold text-gray-900">
                                <i class="ki-duotone ki-folder fs-2 text-primary me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Kelengkapan Dokumen
                            </h3>

                            <span class="text-gray-500 fs-7">
                                Status kelengkapan dokumen Anda
                            </span>
                        </div>

                        <div class="card-toolbar">
                            <a href="{{ route('dokumen.index') }}" class="btn btn-sm btn-light-primary">
                                Lihat Selengkapnya
                            </a>
                        </div>
                    </div>

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
                                <div
                                    class="progress-bar bg-success"
                                    role="progressbar"
                                    style="width: 100%;"></div>
                            </div>

                            <div class="text-success fs-7 mt-2">
                                <i class="ki-duotone ki-check-circle fs-6">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
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
                                <div
                                    class="progress-bar bg-warning"
                                    role="progressbar"
                                    style="width: 75%;"></div>
                            </div>

                            <div class="text-warning fs-7 mt-2">
                                <i class="ki-duotone ki-information-2 fs-6">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
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
                                <div
                                    class="progress-bar bg-warning"
                                    role="progressbar"
                                    style="width: 67%;"></div>
                            </div>

                            <div class="text-warning fs-7 mt-2">
                                <i class="ki-duotone ki-information-2 fs-6">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                2 dokumen belum tersedia
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            {{-- Status Supervisi --}}
            <div class="col-xl-6" id="supervisi-status">
                <div class="card h-100">

                    <div class="card-header border-0 pt-6">
                        <div>
                            <h3 class="card-title fw-bold text-gray-900">
                                <i class="ki-duotone ki-chart-simple fs-2 text-success me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                                Status Supervisi
                            </h3>

                            <span class="text-gray-500 fs-7">
                                Informasi pelaksanaan supervisi
                            </span>
                        </div>

                        <div class="card-toolbar">
                            <a href="#" class="btn btn-sm btn-light-primary">
                                Lihat Selengkapnya
                            </a>
                        </div>
                    </div>

                    <div class="card-body pt-4">

                        <div class="d-flex align-items-center mb-8">

                            <div class="symbol symbol-50px symbol-circle bg-light-success me-5">
                                <i class="ki-duotone ki-check fs-2x text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>

                            <div>
                                <span class="text-success fw-semibold fs-7">
                                    STATUS
                                </span>

                                <div class="fw-bold fs-4 text-gray-900">
                                    Sudah Dilaksanakan
                                </div>
                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-6 mb-6">
                                <span class="text-gray-500 fs-7 d-block mb-1">
                                    Supervisi Terakhir
                                </span>

                                <span class="fw-bold text-gray-900">
                                    12 September 2026
                                </span>
                            </div>

                            <div class="col-md-6 mb-6">
                                <span class="text-gray-500 fs-7 d-block mb-1">
                                    Pengawas
                                </span>

                                <span class="fw-bold text-gray-900">
                                    Nama Pengawas
                                </span>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>


        {{-- Agenda Mendatang --}}
        <div class="card mt-8" id="agenda-mendatang">

            <div class="card-header border-0 pt-6">

                <div>
                    <h3 class="card-title fw-bold text-gray-900">
                        <i class="ki-duotone ki-calendar fs-2 text-primary me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Agenda Mendatang
                    </h3>

                    <span class="text-gray-500 fs-7">
                        Kegiatan yang perlu diperhatikan
                    </span>
                </div>

                <div class="card-toolbar">
                    <a href="#" class="btn btn-sm btn-light-primary">
                        Lihat Selengkapnya
                    </a>
                </div>

            </div>


            <div class="card-body pt-4">

                {{-- Agenda 1 --}}
                <div class="d-flex align-items-center mb-7">

                    <div class="symbol symbol-50px symbol-circle bg-light-primary me-5">
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
                            <i class="ki-duotone ki-time fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            08:00 WIB
                        </span>
                    </div>

                </div>


                {{-- Agenda 2 --}}
                <div class="separator separator-dashed my-5"></div>

                <div class="d-flex align-items-center mb-7">

                    <div class="symbol symbol-50px symbol-circle bg-light-primary me-5">
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
                            <i class="ki-duotone ki-time fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            23:59 WIB
                        </span>
                    </div>

                </div>


                {{-- Agenda 3 --}}
                <div class="separator separator-dashed my-5"></div>

                <div class="d-flex align-items-center mb-7">

                    <div class="symbol symbol-50px symbol-circle bg-light-primary me-5">
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
                            <i class="ki-duotone ki-geolocation fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Ruang Guru
                        </span>
                    </div>

                </div>


                {{-- Agenda 4 --}}
                <div class="separator separator-dashed my-5"></div>

                <div class="d-flex align-items-center">

                    <div class="symbol symbol-50px symbol-circle bg-light-primary me-5">
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
                            <i class="ki-duotone ki-time fs-6">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            09:00 WIB
                        </span>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

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
                url: '{{ url(' / profil ') }}'
            },
            {
                key: 'dokumen',
                match: ['dokumen', 'file', 'modul', 'atp', 'ap', 'prota', 'prosem', 'bank soal', 'materi', 'arsip'],
                url: '{{ url(' / dokumen ') }}'
            },
            {
                key: 'supervisi',
                match: ['supervisi', 'pengawasan', 'monitoring', 'evaluasi'],
                url: '#supervisi-status'
            },
            {
                key: 'agenda',
                match: ['agenda', 'jadwal', 'kegiatan', 'rapat', 'pengumpulan', 'evaluasi pembelajaran', 'supervisi guru'],
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
                    target.classList.add('border', 'border-primary', 'rounded');
                    setTimeout(() => target.classList.remove('border', 'border-primary', 'rounded'), 2000);
                }
                searchForm.dispatchEvent(new Event('reset'));
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