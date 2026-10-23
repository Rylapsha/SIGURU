
@extends('layouts.app')

@section('title', 'Dashboard - SIGURU')
@section('page_title', 'Dashboard')

@section('content')

@php
    $totalKategoriDokumen = count($kategoriDokumen ?? []);
    $jumlahDokumenLengkap = (int) ($jumlahKategoriLengkap ?? 0);
    $jumlahDokumenBelumLengkap = max(
        0,
        $totalKategoriDokumen - $jumlahDokumenLengkap
    );

    $persentaseDokumen = $totalKategoriDokumen > 0
        ? (int) round(
            ($jumlahDokumenLengkap / $totalKategoriDokumen) * 100
        )
        : 0;

    $jumlahSupervisiMendatang = isset($agendaMendatang)
        ? $agendaMendatang->count()
        : 0;
@endphp

<div class="post d-flex flex-column-fluid" id="kt_post">
    <div class="container-fluid dashboard-container">

        {{-- Header Dashboard --}}
        <div class="d-flex flex-wrap flex-stack mb-7 mt-4">
            <div>
                <h1 class="fw-bold mb-2 dashboard-welcome">
                    Selamat Datang, {{ Auth::user()->nama_lengkap ?? 'Guru' }}
                </h1>
            </div>
        </div>

        {{-- Target Pencarian --}}
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
                {{ route('guru.supervisi') }}
            </div>

            <div data-search-target="agenda"
                data-search-keywords="agenda, jadwal, kegiatan, rapat, pengumpulan, evaluasi pembelajaran, supervisi guru">
                {{ route('guru.supervisi') }}
            </div>
        </div>

        {{-- Layout Utama --}}
        <div class="dashboard-layout">

            {{-- Kolom Kiri --}}
            <div class="dashboard-main d-flex flex-column gap-5">

                {{-- Kelengkapan Dokumen --}}
                <div class="card dashboard-panel" id="kelengkapan-statistik">

                    <div class="card-header border-0 pt-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="dashboard-section-icon">
                                <i class="bi bi-folder-check"></i>
                            </div>

                            <div>
                                <h3 class="dashboard-heading fw-bold mb-1">
                                    Kelengkapan Dokumen
                                </h3>

                                <span class="dashboard-muted fs-7">
                                    Status kelengkapan dokumen Anda
                                </span>
                            </div>
                        </div>

                        <div class="card-toolbar">
                            <a href="{{ route('dokumen.index') }}"
                                class="btn btn-sm btn-light-primary dashboard-action-btn">
                                Lihat Selengkapnya
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body pt-4">

                        {{-- Diagram Donat --}}
                        <div class="dashboard-chart-area">

                            <div class="dashboard-donut-chart">
                                <canvas id="dashboardDocumentChart"
                                    role="img"
                                    aria-label="Diagram persentase kelengkapan dokumen">
                                </canvas>

                                <div class="dashboard-donut-center">
                                    <span class="dashboard-donut-percentage">
                                        {{ $persentaseDokumen }}%
                                    </span>

                                    <span class="dashboard-muted fs-7">
                                        {{ $jumlahDokumenLengkap }} dari {{ $totalKategoriDokumen }} kategori
                                    </span>
                                </div>
                            </div>

                            <button type="button"
                                class="btn btn-sm btn-light-primary dashboard-detail-toggle"
                                id="toggleDocumentDetails"
                                aria-expanded="false"
                                aria-controls="documentDetails">
                                <i class="bi bi-eye"></i>
                                <span id="documentToggleText">Lihat Detail</span>
                                <i class="bi bi-chevron-down" id="documentToggleIcon"></i>
                            </button>

                        </div>

                        {{-- Rincian Dokumen --}}
                        <div id="documentDetails"
                            class="dashboard-document-details mt-5"
                            hidden>

                            <div class="dashboard-detail-panel">

                                <div class="d-flex align-items-center justify-content-between gap-3 mb-5">
                                    <h4 class="dashboard-heading fw-bold mb-0 fs-6">
                                        Rincian Kelengkapan Dokumen
                                    </h4>

                                    <span class="badge badge-light-primary">
                                        {{ $jumlahDokumenLengkap }}/{{ $totalKategoriDokumen }}
                                    </span>
                                </div>

                                <div class="d-flex flex-column gap-4">

                                    @forelse(($kategoriDokumen ?? []) as $namaKategori => $lengkap)

                                        <div class="dashboard-detail-row">

                                            <div class="d-flex align-items-center gap-2">
                                                <span class="dashboard-detail-dot {{ $lengkap ? 'detail-dot-success' : 'detail-dot-danger' }}"></span>

                                                <span class="dashboard-detail-label fs-7">
                                                    {{ $namaKategori }}
                                                </span>
                                            </div>

                                            <div class="dashboard-detail-progress">
                                                <div class="progress h-6px">
                                                    <div class="progress-bar {{ $lengkap ? 'bg-success' : 'bg-gray-300' }}"
                                                        role="progressbar"
                                                        style="width: {{ $lengkap ? 100 : 0 }}%;"
                                                        aria-valuenow="{{ $lengkap ? 100 : 0 }}"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>

                                            @if($lengkap)
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                            @else
                                                <i class="bi bi-x-circle-fill text-danger"></i>
                                            @endif

                                        </div>

                                    @empty
                                        <div class="dashboard-muted text-center py-3">
                                            Belum ada data dokumen.
                                        </div>
                                    @endforelse

                                </div>

                                <a href="{{ route('dokumen.index') }}"
                                    class="btn btn-sm btn-light-primary w-100 mt-5">
                                    Kelola Dokumen
                                    <i class="bi bi-arrow-right ms-2"></i>
                                </a>

                            </div>
                        </div>

                    </div>
                </div>

                {{-- Ringkasan Supervisi --}}
                <div class="card dashboard-panel" id="supervisi-status">

                    <div class="card-header border-0 pt-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="dashboard-section-icon">
                                <i class="bi bi-person-workspace"></i>
                            </div>

                            <div>
                                <h3 class="dashboard-heading fw-bold mb-1">
                                    Ringkasan Supervisi
                                </h3>

                                <span class="dashboard-muted fs-7">
                                    Pantau jadwal supervisi
                                </span>
                            </div>
                        </div>

                        <div class="card-toolbar">
                            <a href="{{ route('guru.supervisi') }}"
                                class="btn btn-sm btn-light-primary dashboard-action-btn">
                                Lihat Agenda
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body pt-4">
                        <div class="row g-4">

                            {{-- Semester Ganjil --}}
                            <div class="col-12 col-md-6">
                                <div class="dashboard-semester-card h-100">

                                    <div class="dashboard-semester-icon">
                                        <i class="bi bi-calendar-event"></i>
                                    </div>

                                    <div class="flex-grow-1 min-w-0">
                                        <h4 class="dashboard-heading fw-bold fs-6 mb-3">
                                            Semester Ganjil
                                        </h4>

                                        @if(isset($jadwalGanjil) && $jadwalGanjil)

                                            @php
                                                $statusGanjil = strtolower(
                                                    $jadwalGanjil->status ?? 'terjadwal'
                                                );
                                            @endphp

                                            @if($statusGanjil === 'selesai')
                                                <span class="badge badge-light-success mb-3">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Selesai
                                                </span>
                                            @elseif(in_array($statusGanjil, ['terjadwal', 'dijadwalkan']))
                                                <span class="badge badge-light-primary mb-3">
                                                    <i class="bi bi-calendar-check me-1"></i>
                                                    Terjadwal
                                                </span>
                                            @else
                                                <span class="badge badge-light-warning mb-3">
                                                    {{ $statusLabels[$statusGanjil] ?? ucfirst(str_replace('_', ' ', $statusGanjil)) }}
                                                </span>
                                            @endif

                                            <div class="dashboard-muted fs-7">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                {{ $jadwalGanjil->tanggal
                                                    ? \Carbon\Carbon::parse($jadwalGanjil->tanggal)->translatedFormat('d F Y')
                                                    : 'Tanggal belum ditentukan' }}
                                            </div>

                                        @else
                                            <span class="badge badge-light-secondary mb-3">
                                                Belum dijadwalkan
                                            </span>

                                            <div class="dashboard-muted fs-7">
                                                Belum ada jadwal supervisi.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Semester Genap --}}
                            <div class="col-12 col-md-6">
                                <div class="dashboard-semester-card h-100">

                                    <div class="dashboard-semester-icon">
                                        <i class="bi bi-calendar-event"></i>
                                    </div>

                                    <div class="flex-grow-1 min-w-0">
                                        <h4 class="dashboard-heading fw-bold fs-6 mb-3">
                                            Semester Genap
                                        </h4>

                                        @if(isset($jadwalGenap) && $jadwalGenap)

                                            @php
                                                $statusGenap = strtolower(
                                                    $jadwalGenap->status ?? 'terjadwal'
                                                );
                                            @endphp

                                            @if($statusGenap === 'selesai')
                                                <span class="badge badge-light-success mb-3">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Selesai
                                                </span>
                                            @elseif(in_array($statusGenap, ['terjadwal', 'dijadwalkan']))
                                                <span class="badge badge-light-primary mb-3">
                                                    <i class="bi bi-calendar-check me-1"></i>
                                                    Terjadwal
                                                </span>
                                            @else
                                                <span class="badge badge-light-warning mb-3">
                                                    {{ $statusLabels[$statusGenap] ?? ucfirst(str_replace('_', ' ', $statusGenap)) }}
                                                </span>
                                            @endif

                                            <div class="dashboard-muted fs-7">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                {{ $jadwalGenap->tanggal
                                                    ? \Carbon\Carbon::parse($jadwalGenap->tanggal)->translatedFormat('d F Y')
                                                    : 'Tanggal belum ditentukan' }}
                                            </div>

                                        @else
                                            <span class="badge badge-light-secondary mb-3">
                                                Belum dijadwalkan
                                            </span>

                                            <div class="dashboard-muted fs-7">
                                                Belum ada jadwal supervisi.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan: Agenda Mendatang --}}
            <div class="dashboard-agenda" id="agenda-mendatang">

                <div class="card dashboard-panel dashboard-agenda-card">

                    <div class="card-header border-0 pt-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="dashboard-section-icon">
                                <i class="bi bi-calendar-range"></i>
                            </div>

                            <div>
                                <h3 class="dashboard-heading fw-bold mb-1">
                                    Agenda Mendatang
                                </h3>

                                <span class="dashboard-muted fs-7">
                                    Kegiatan dan jadwal yang akan datang
                                </span>
                            </div>
                        </div>

                        <div class="card-toolbar">
                            <a href="{{ route('guru.supervisi') }}"
                                class="btn btn-sm btn-light-primary dashboard-action-btn">
                                Lihat Semua
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body pt-4">

                        @forelse(($agendaMendatang ?? collect()) as $agenda)

                            @if(!$loop->first)
                                <div class="separator separator-dashed my-5"></div>
                            @endif

                            <a href="{{ route('guru.supervisi') }}"
                                class="dashboard-agenda-item">

                                <div class="dashboard-agenda-date">
                                    <span class="dashboard-agenda-day">
                                        {{ \Carbon\Carbon::parse($agenda->tanggal)->format('d') }}
                                    </span>

                                    <span class="dashboard-agenda-month">
                                        {{ mb_strtoupper(\Carbon\Carbon::parse($agenda->tanggal)->translatedFormat('M')) }}
                                    </span>
                                </div>

                                <div class="flex-grow-1 min-w-0">

                                    <span class="dashboard-agenda-title fw-bold d-block fs-6 mb-2">
                                        Supervisi Guru
                                    </span>

                                    <span class="dashboard-muted fs-7 d-block mb-1">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $agenda->jam
                                            ? \Illuminate\Support\Str::substr($agenda->jam, 0, 5) . ' WIB'
                                            : 'Waktu belum ditentukan' }}
                                    </span>

                                    @if($agenda->mata_pelajaran)
                                        <span class="dashboard-muted fs-7 d-block mb-1">
                                            <i class="bi bi-book me-1"></i>
                                            {{ $agenda->mata_pelajaran }}
                                        </span>
                                    @endif

                                    @if($agenda->nama_supervisor)
                                        <span class="dashboard-muted fs-7 d-block">
                                            <i class="bi bi-person me-1"></i>
                                            {{ $agenda->nama_supervisor }}
                                        </span>
                                    @endif

                                </div>

                            </a>

                        @empty
                            <div class="dashboard-muted text-center py-8">
                                <i class="bi bi-calendar2-x fs-2x d-block mb-3"></i>

                                <div class="fw-semibold mb-1">
                                    Belum ada agenda
                                </div>

                                <span class="fs-7">
                                    Jadwal supervisi mendatang akan muncul di sini.
                                </span>
                            </div>
                        @endforelse

                    </div>

                    @if(isset($agendaMendatang) && $agendaMendatang->count() > 0)
                        <div class="card-footer border-0 pt-0">
                            <div class="dashboard-agenda-footer">
                                <i class="bi bi-calendar-check fs-3 text-primary"></i>

                                <span class="dashboard-muted fs-7">
                                    {{ $jumlahSupervisiMendatang }} agenda mendatang
                                </span>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .dashboard-container {
        padding-bottom: 2rem;
    }

    .dashboard-welcome {
        color: var(--bs-gray-900, #181c32);
        font-size: clamp(1.5rem, 2vw, 2rem);
        letter-spacing: -0.5px;
    }

    .dashboard-description {
        color: var(--bs-gray-600, #7e8299);
        line-height: 1.7;
        display: inline-block;
        max-width: 750px;
    }

    .dashboard-layout {
        display: grid;
        grid-template-columns: minmax(0, 7fr) minmax(0, 4.3fr);
        gap: 1.25rem;
        align-items: start;
    }

    .dashboard-main,
    .dashboard-agenda {
        min-width: 0;
    }

    .dashboard-panel {
        border: 1px solid var(--bs-border-color, #dce8f8);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(35, 75, 130, 0.025);
        background: var(--bs-card-bg, #fff);
        color: var(--bs-body-color, #181c32);
    }

    .dashboard-panel .card-header,
    .dashboard-panel .card-body,
    .dashboard-panel .card-footer {
        color: var(--bs-body-color, #181c32);
    }

    .dashboard-heading,
    .dashboard-agenda-title {
        color: var(--bs-gray-900, #181c32) !important;
    }

    .dashboard-muted {
        color: var(--bs-gray-600, #7e8299);
    }

    .dashboard-section-icon {
        width: 48px;
        height: 48px;
        border-radius: 15px;
        background: #e6f0ff;
        color: #2478ed;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .dashboard-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 34px;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.4;
        white-space: nowrap;
    }

    .dashboard-chart-area {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        padding: 1rem 0;
    }

    .dashboard-donut-chart {
        width: 100%;
        max-width: 230px;
        aspect-ratio: 1 / 1;
        position: relative;
        margin: 0 auto;
    }

    .dashboard-donut-chart canvas {
        width: 100% !important;
        height: 100% !important;
    }

    .dashboard-donut-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        pointer-events: none;
        white-space: nowrap;
    }

    .dashboard-donut-percentage {
        color: var(--bs-gray-900, #181c32);
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1.25;
    }

    .dashboard-detail-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        border-radius: 22px;
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 600;
    }

    .dashboard-document-details[hidden] {
        display: none !important;
    }

    .dashboard-detail-panel {
        border: 1px solid var(--bs-border-color, #e0e9f6);
        border-radius: 10px;
        background: var(--bs-card-bg, #fff);
        color: var(--bs-body-color, #181c32);
        padding: 1.25rem;
        animation: dashboardDetailIn 0.2s ease;
    }

    .dashboard-detail-row {
        display: grid;
        grid-template-columns: minmax(110px, 0.8fr) minmax(80px, 1fr) 16px;
        align-items: center;
        gap: 1rem;
    }

    .dashboard-detail-label {
        color: var(--bs-gray-700, #5e6278);
        overflow-wrap: anywhere;
    }

    .dashboard-detail-row > div:first-child {
        min-width: 0;
    }

    .dashboard-detail-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .detail-dot-success {
        background: #10a779;
    }

    .detail-dot-danger {
        background: #f04452;
    }

    .dashboard-detail-progress {
        min-width: 0;
    }

    .dashboard-semester-card {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.15rem;
        border: 1px solid var(--bs-border-color, #e2edfc);
        border-radius: 10px;
        background: var(--bs-secondary-bg, #f5f9ff);
        color: var(--bs-body-color, #181c32);
    }

    .dashboard-semester-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #dfedff;
        color: #2478ed;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .dashboard-agenda-card .card-body {
        padding-top: 1rem;
    }

    .dashboard-agenda-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        min-width: 0;
        padding: 0.25rem;
        text-decoration: none;
        color: inherit;
        border-radius: 8px;
        transition: background 0.2s ease;
    }

    .dashboard-agenda-item:hover {
        color: inherit;
        background: var(--bs-secondary-bg, #f5f9ff);
    }

    .dashboard-agenda-date {
        width: 55px;
        min-width: 55px;
        min-height: 66px;
        border-radius: 10px;
        background: #edf5ff;
        color: #2478ed;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 7px 3px;
    }

    .dashboard-agenda-day {
        font-size: 1.25rem;
        font-weight: 800;
        line-height: 1.3;
    }

    .dashboard-agenda-month {
        font-size: 0.75rem;
        font-weight: 600;
    }

    .dashboard-agenda-arrow {
        color: #8ca2c2;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        flex-shrink: 0;
    }

    .dashboard-agenda-footer {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1rem;
        border-radius: 9px;
        background: var(--bs-secondary-bg, #f1f7ff);
    }

    #supervisi-status,
    #agenda-mendatang,
    #kelengkapan-statistik {
        scroll-margin-top: 100px;
    }

    /* Mode gelap */
    [data-bs-theme="dark"] .dashboard-welcome,
    [data-bs-theme="dark"] .dashboard-heading,
    [data-bs-theme="dark"] .dashboard-donut-percentage,
    [data-bs-theme="dark"] .dashboard-agenda-title {
        color: #f5f8ff !important;
    }

    [data-bs-theme="dark"] .dashboard-description,
    [data-bs-theme="dark"] .dashboard-muted,
    [data-bs-theme="dark"] .dashboard-detail-label {
        color: #b5b5c3 !important;
    }

    [data-bs-theme="dark"] .dashboard-panel,
    [data-bs-theme="dark"] .dashboard-detail-panel {
        background: #1e1e2d;
        border-color: #343448;
    }

    [data-bs-theme="dark"] .dashboard-semester-card {
        background: #252538;
        border-color: #343448;
    }

    [data-bs-theme="dark"] .dashboard-agenda-date,
    [data-bs-theme="dark"] .dashboard-agenda-footer {
        background: #252d41;
    }

    [data-bs-theme="dark"] .dashboard-agenda-item:hover {
        background: #252538;
    }

    [data-bs-theme="dark"] .dashboard-section-icon,
    [data-bs-theme="dark"] .dashboard-semester-icon {
        background: #252d41;
        color: #79aaff;
    }

    [data-bs-theme="dark"] .dashboard-agenda-arrow {
        color: #a1a5b7;
    }

    [data-bs-theme="dark"] .dashboard-panel .separator {
        border-color: #343448 !important;
    }

    [data-bs-theme="dark"] .dashboard-panel .progress {
        background-color: #343448;
    }

    [data-bs-theme="dark"] .dashboard-detail-panel .bg-gray-300 {
        background-color: #45455a !important;
    }

    @keyframes dashboardDetailIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 1399.98px) {
        .dashboard-layout {
            grid-template-columns: minmax(0, 7fr) minmax(0, 4fr);
            gap: 1rem;
        }

        .dashboard-donut-chart {
            max-width: 210px;
        }
    }

    @media (max-width: 1199.98px) {
        .dashboard-layout {
            grid-template-columns: minmax(0, 1fr);
        }

        .dashboard-agenda {
            width: 100%;
        }

        .dashboard-donut-chart {
            max-width: 230px;
        }
    }

    @media (max-width: 767.98px) {
        .dashboard-container {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .dashboard-welcome {
            font-size: 1.4rem;
        }

        .dashboard-description {
            font-size: 0.85rem !important;
        }

        .dashboard-panel .card-header {
            flex-wrap: wrap;
            gap: 1rem;
        }

        .dashboard-panel .card-toolbar {
            width: 100%;
        }

        .dashboard-panel .card-toolbar .dashboard-action-btn {
            width: 100%;
        }

        .dashboard-donut-chart {
            max-width: 195px;
        }

        .dashboard-detail-panel {
            padding: 1rem;
        }

        .dashboard-detail-row {
            grid-template-columns: minmax(95px, 0.9fr) minmax(50px, 1fr) 16px;
            gap: 0.65rem;
        }

        .dashboard-agenda-item {
            gap: 0.75rem;
        }

        .dashboard-agenda-date {
            width: 48px;
            min-width: 48px;
            min-height: 60px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .dashboard-detail-panel {
            animation: none;
            transition: none;
        }
    }
</style>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Diagram Donat Kelengkapan Dokumen
    const documentCanvas = document.getElementById('dashboardDocumentChart');

    if (documentCanvas && typeof Chart !== 'undefined') {
        const completeDocuments = {{ $jumlahDokumenLengkap }};
        const incompleteDocuments = {{ $jumlahDokumenBelumLengkap }};

        new Chart(documentCanvas, {
            type: 'doughnut',

            data: {
                labels: ['Tersedia', 'Belum tersedia'],
                datasets: [{
                    data: [completeDocuments, incompleteDocuments],
                    backgroundColor: ['#10A779', '#DCE3ED'],
                    borderWidth: 0,
                    borderRadius: 5,
                    spacing: 3,
                    hoverOffset: 5
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '76%',

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function (context) {
                                return context.label + ': ' + context.raw;
                            }
                        }
                    }
                }
            }
        });
    }

    // Tombol Rincian Dokumen
    const detailToggle = document.getElementById('toggleDocumentDetails');
    const documentDetails = document.getElementById('documentDetails');
    const toggleText = document.getElementById('documentToggleText');
    const toggleIcon = document.getElementById('documentToggleIcon');

    if (detailToggle && documentDetails) {
        detailToggle.addEventListener('click', function () {
            const isExpanded =
                detailToggle.getAttribute('aria-expanded') === 'true';

            documentDetails.hidden = isExpanded;
            detailToggle.setAttribute('aria-expanded', String(!isExpanded));

            if (toggleText) {
                toggleText.textContent = isExpanded
                    ? 'Lihat Detail'
                    : 'Tutup Detail';
            }

            if (toggleIcon) {
                toggleIcon.classList.toggle('bi-chevron-down', isExpanded);
                toggleIcon.classList.toggle('bi-chevron-up', !isExpanded);
            }

            if (!isExpanded) {
                documentDetails.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }
        });
    }

    // Pencarian Global
    const searchInput = document.getElementById('headerGlobalSearch');
    const searchForm = document.getElementById('headerSearchForm');

    if (!searchInput || !searchForm) {
        return;
    }

    const mappings = [
        {
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
            url: @json(route('guru.supervisi'))
        },
        {
            key: 'agenda',
            match: [
                'agenda', 'jadwal', 'kegiatan', 'rapat',
                'pengumpulan', 'evaluasi pembelajaran', 'supervisi guru'
            ],
            url: @json(route('guru.supervisi'))
        }
    ];

    const normalize = value => (value || '').toLowerCase().trim();

    const handleSearch = function () {
        const query = normalize(searchInput.value);

        if (!query) {
            return;
        }

        const matched = mappings.find(item =>
            item.match.some(keyword => query.includes(keyword))
        );

        if (!matched) {
            return;
        }

        window.location.href = matched.url;
    };

    searchInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            handleSearch();
        }
    });

});
</script>

@endsection
