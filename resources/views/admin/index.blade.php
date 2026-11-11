@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('page_title', 'Dashboard')

@section('content')

@php
/*
|--------------------------------------------------------------------------
| Perhitungan Tahun Ajaran Aktif
|--------------------------------------------------------------------------
*/
$tanggalSekarang = \Carbon\Carbon::now();
$bulanSekarang = (int) $tanggalSekarang->format('n');
$tahunSekarang = (int) $tanggalSekarang->format('Y');

$tahunMulaiAjaran = $bulanSekarang >= 7
? $tahunSekarang
: $tahunSekarang - 1;

$tahunAjaranAktif = $tahunMulaiAjaran . '/' . ($tahunMulaiAjaran + 1);

$semesterAktif = $bulanSekarang >= 7
? 'Ganjil'
: 'Genap';


/*
|--------------------------------------------------------------------------
| Data Kelengkapan Dokumen
|--------------------------------------------------------------------------
*/
$totalGuru = (int) ($totalGuru ?? 0);
$dokumenLengkap = (int) ($dokumenLengkap ?? 0);

$dokumenBelumLengkap = max(
0,
$totalGuru - $dokumenLengkap
);

$persentaseLengkap = $totalGuru > 0
? (int) round(($dokumenLengkap / $totalGuru) * 100)
: 0;


/*
|--------------------------------------------------------------------------
| Data Supervisi
|--------------------------------------------------------------------------
*/
$jumlahJadwalMendatang = isset($jadwalMendatang)
? $jadwalMendatang->count()
: 0;
@endphp


<div class="post d-flex flex-column-fluid" id="kt_post">

    <div class="container-fluid dashboard-container">

        {{-- ==========================================================
            HEADER DASHBOARD
        =========================================================== --}}
        <div class="d-flex flex-wrap flex-stack mb-7 mt-4">
            <div>
                    <h1 class="fw-lighter mt-3 mb-10 dashboard-welcome">
                        Selamat Datang <span class="fw-bolder"> {{ Auth::user()->nama_lengkap ?? 'Admin' }}</span>
                    </h1>
              </div>
        </div>

        {{-- ==========================================================
            INFORMASI TAHUN AJARAN
        =========================================================== --}}
        <div class="card dashboard-panel mb-5" id="tahun-ajaran-aktif">

            <div class="card-header border-0">

                <div class="d-flex align-items-center gap-3">

                    <div class="dashboard-section-icon">

                        <i class="bi bi-calendar3"></i>

                    </div>

                    <div>

                        <h3 class="dashboard-heading fw-bold mb-1">

                            Tahun Ajaran Aktif

                        </h3>

                        <span class="dashboard-muted fs-7">

                            {{ $tahunAjaranAktif }}
                            - Semester {{ $semesterAktif }}

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ==========================================================
            TARGET PENCARIAN
        =========================================================== --}}
        <div id="dashboard-search-targets" class="d-none">

            <div
                data-search-target="dashboard"
                data-search-keywords="dashboard, beranda, halaman utama">
                {{ route('admin.dashboard') }}
            </div>

            <div
                data-search-target="guru"
                data-search-keywords="guru, data guru, daftar guru, tenaga pendidik">
                {{ route('admin.guru.index') }}
            </div>

            <div
                data-search-target="dokumen"
                data-search-keywords="dokumen, arsip, file, kelengkapan dokumen">
                {{ route('dokumen.index') }}
            </div>

            <div
                data-search-target="supervisi"
                data-search-keywords="supervisi, jadwal supervisi, pengawasan, monitoring">
                {{ route('admin.supervisi.index') }}
            </div>

        </div>


        {{-- ==========================================================
            LAYOUT UTAMA
        =========================================================== --}}
        <div class="dashboard-layout">


            {{-- ======================================================
                KOLOM KIRI
            ======================================================= --}}
            <div class="dashboard-main d-flex flex-column gap-5">


                {{-- ==================================================
                    KELENGKAPAN DOKUMEN GURU
                =================================================== --}}
                <div
                    class="card dashboard-panel"
                    id="kelengkapan-statistik">

                    {{-- HEADER --}}
                    <div class="card-header border-0 pt-6">

                        <div class="d-flex align-items-center gap-3">

                            <div class="dashboard-section-icon">

                                <i class="bi bi-folder-check"></i>

                            </div>

                            <div>

                                <h3 class="dashboard-heading fw-bold mb-1">

                                    Kelengkapan Dokumen Guru

                                </h3>

                                <span class="dashboard-muted fs-7">

                                    Status kelengkapan dokumen seluruh guru

                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- BODY --}}
                    <div class="card-body pt-4">


                        {{-- DIAGRAM DONAT --}}
                        <div class="dashboard-chart-area">

                            <div class="dashboard-donut-chart">

                                <canvas
                                    id="dashboardDocumentChart"
                                    role="img"
                                    aria-label="Diagram persentase kelengkapan dokumen guru">
                                </canvas>

                                <div class="dashboard-donut-center">

                                    <span class="dashboard-donut-percentage">

                                        {{ $persentaseLengkap }}%

                                    </span>

                                    <span class="dashboard-muted fs-7">

                                        {{ $dokumenLengkap }}
                                        dari
                                        {{ $totalGuru }}
                                        guru

                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- RINCIAN --}}
                        <div class="dashboard-detail-panel mt-5">

                            <div
                                class="d-flex align-items-center justify-content-between gap-3 mb-5">

                                <h4 class="dashboard-heading fw-bold mb-0 fs-6">

                                    Rincian Kelengkapan Dokumen

                                </h4>

                                <span class="badge badge-light-primary">

                                    {{ $dokumenLengkap }}/{{ $totalGuru }}

                                </span>

                            </div>


                            <div class="d-flex flex-column gap-4">


                                {{-- DOKUMEN LENGKAP --}}
                                <div class="dashboard-detail-row">

                                    <div class="d-flex align-items-center gap-2">

                                        <span
                                            class="dashboard-detail-dot detail-dot-success">
                                        </span>

                                        <span class="dashboard-detail-label fs-7">

                                            Dokumen Lengkap

                                        </span>

                                    </div>

                                    <div class="dashboard-detail-progress">

                                        <div class="progress h-6px">

                                            <div
                                                class="progress-bar bg-success"
                                                role="progressbar"
                                                style="width: {{ $persentaseLengkap }}%;"
                                                aria-valuenow="{{ $persentaseLengkap }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>

                                        </div>

                                    </div>

                                    <span class="dashboard-muted fs-8 text-end">

                                        {{ $dokumenLengkap }}

                                    </span>

                                </div>


                                {{-- BELUM LENGKAP --}}
                                <div class="dashboard-detail-row">

                                    <div class="d-flex align-items-center gap-2">

                                        <span
                                            class="dashboard-detail-dot detail-dot-danger">
                                        </span>

                                        <span class="dashboard-detail-label fs-7">

                                            Belum Lengkap

                                        </span>

                                    </div>

                                    <div class="dashboard-detail-progress">

                                        <div class="progress h-6px">

                                            <div
                                                class="progress-bar bg-danger"
                                                role="progressbar"
                                                style="width:
                                                {{ $totalGuru > 0
                                                    ? round(($dokumenBelumLengkap / $totalGuru) * 100)
                                                    : 0 }}%;"
                                                aria-valuenow="{{ $totalGuru > 0
                                                    ? round(($dokumenBelumLengkap / $totalGuru) * 100)
                                                    : 0 }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>

                                        </div>

                                    </div>

                                    <span class="dashboard-muted fs-8 text-end">

                                        {{ $dokumenBelumLengkap }}

                                    </span>

                                </div>

                            </div>


                            {{-- INFORMASI --}}
                            <div class="dashboard-info-box mt-5">

                                <div class="d-flex align-items-start gap-3">

                                    <i class="bi bi-info-circle text-primary fs-5"></i>

                                    <div class="dashboard-muted fs-8">

                                        Dokumen dinyatakan lengkap apabila
                                        seluruh dokumen yang diperlukan
                                        tersedia sesuai tahun ajaran,
                                        mata pelajaran, semester, dan kelas.

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                    STATISTIK SUPERVISI
                =================================================== --}}
                <div
                    class="card dashboard-panel"
                    id="supervisi-status">

                    <div class="card-header border-0 pt-6">

                        <div class="d-flex align-items-center gap-3">

                            <div class="dashboard-section-icon">

                                <i class="bi bi-bar-chart-line"></i>

                            </div>

                            <div>

                                <h3 class="dashboard-heading fw-bold mb-1">

                                    Statistik Supervisi

                                </h3>

                                <span class="dashboard-muted fs-7">

                                    Jumlah guru yang mengikuti supervisi

                                </span>

                            </div>

                        </div>


                        {{-- FILTER TANGGAL --}}
                        <div class="card-toolbar mt-4 mt-md-0">

                            <div class="input-group input-group-sm">

                                <span class="input-group-text">

                                    <i class="bi bi-calendar3"></i>

                                </span>

                                <input
                                    type="text"
                                    id="supervisiDateRange"
                                    class="form-control"
                                    placeholder="Pilih rentang tanggal"
                                    autocomplete="off">

                            </div>

                        </div>

                    </div>


                    <div class="card-body pt-4">

                        {{-- JUMLAH GURU --}}
                        <div class="d-flex justify-content-between align-items-center mb-5">

                            <div>

                                <span class="dashboard-muted fs-7">

                                    Jumlah Guru

                                </span>

                                <div
                                    id="supervisiCountLabel"
                                    class="dashboard-heading fw-bold fs-2">

                                    0

                                </div>

                            </div>

                            <div class="text-end">

                                <span
                                    id="supervisiRangeText"
                                    class="dashboard-muted fs-8">

                                    Seluruh data

                                </span>

                            </div>

                        </div>


                        {{-- CHART --}}
                        <div class="supervisi-chart-wrapper">

                            <canvas
                                id="supervisiChart"
                                height="120">
                            </canvas>

                        </div>

                    </div>

                </div>


            </div>


            {{-- ======================================================
                KOLOM KANAN
            ======================================================= --}}
            <div class="dashboard-agenda d-flex flex-column gap-5">


                {{-- ==================================================
                    JUMLAH GURU
                =================================================== --}}
                <div class="card dashboard-panel">

                    <div class="card-header border-0 pt-6">

                        <div class="d-flex align-items-center gap-3">

                            <div class="dashboard-section-icon">

                                <i class="bi bi-people"></i>

                            </div>

                            <div>

                                <h3 class="dashboard-heading fw-bold mb-1">

                                    Jumlah Guru

                                </h3>

                                <span class="dashboard-muted fs-7">

                                    Total guru terdaftar di sistem

                                </span>

                            </div>

                        </div>

                    </div>


                    <div class="card-body pt-4">

                        <div class="d-flex align-items-center justify-content-between gap-4 py-2">

                            <div>

                                <div class="dashboard-heading fw-bolder display-6 mb-1">

                                    {{ $totalGuru ?? 0 }} <span class="fs-7 text-muted">Orang</span>

                                </div>

                            </div>

                            <a
                                href="{{ route('admin.guru.index') }}"
                                class="btn btn-sm btn-light-primary dashboard-action-btn mt-3">
                                Lihat Detail
                                <i class="bi bi-chevron-right"></i>
                            </a>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                    JADWAL SUPERVISI
                =================================================== --}}
                <div
                    class="card dashboard-panel"
                    id="agenda-mendatang">

                    <div class="card-header border-0 pt-6">

                        <div class="d-flex align-items-center gap-3">

                            <div class="dashboard-section-icon">

                                <i class="bi bi-calendar-range"></i>

                            </div>

                            <div>

                                <h3 class="dashboard-heading fw-bold mb-1">
                                    Agenda

                                </h3>

                                <span class="dashboard-muted fs-7">

                                    Pantau jadwal kegiatan dan agenda mendatang

                                </span>

                            </div>

                            <a
                                href="{{ route('admin.agenda.index') }}"
                                class="btn btn-sm btn-light-primary dashboard-action-btn mt-3">
                                Lihat detail
                                <i class="bi bi-chevron-right"></i>
                            </a>

                        </div>

                    </div>


                    <div class="card-body pt-4">

                        @forelse(($jadwalMendatang ?? collect()) as $jadwal)

                        @php
                        $agendaLabel = ($jadwal->jenis ?? 'supervisi') === 'supervisi'
                        ? 'Supervisi Guru'
                        : ($jadwal->judul ?? 'Kegiatan');

                        $agendaRoute = ($jadwal->jenis ?? 'supervisi') === 'supervisi'
                        ? route('admin.supervisi.index')
                        : route('admin.agenda.index');

                        $agendaWaktu = $jadwal->jam
                        ? \Illuminate\Support\Str::substr($jadwal->jam, 0, 5) . ' WIB'
                        : 'Waktu belum ditentukan';

                        $agendaPengisi = $jadwal->nama_lengkap
                        ?? $jadwal->guru->nama_lengkap
                        ?? null;

                        $agendaLokasi = $jadwal->lokasi ?? null;
                        $agendaKeterangan = $jadwal->keterangan ?? null;
                        $agendaStatus = $jadwal->status ?? 'terjadwal';
                        @endphp

                        @if(!$loop->first)

                        <div class="separator separator-dashed my-5"></div>

                        @endif


                        <a
                            href="{{ $agendaRoute }}"
                            class="dashboard-agenda-item">


                            {{-- TANGGAL --}}
                            <div class="dashboard-agenda-date">

                                <span class="dashboard-agenda-day">

                                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d') }}

                                </span>

                                <span class="dashboard-agenda-month">

                                    {{ mb_strtoupper(
                                            \Carbon\Carbon::parse($jadwal->tanggal)
                                                ->translatedFormat('M')
                                        ) }}

                                </span>

                            </div>


                            {{-- INFORMASI --}}
                            <div class="flex-grow-1 min-w-0">

                                <span
                                    class="dashboard-agenda-title fw-bold d-block fs-6 mb-2">

                                    {{ $agendaLabel }}

                                </span>

                                <span class="dashboard-muted fs-7 d-block mb-1">

                                    <i class="bi bi-clock me-1"></i>

                                    {{ $agendaWaktu }}

                                </span>

                                @if(($jadwal->jenis ?? 'supervisi') === 'supervisi')

                                @if($agendaPengisi)

                                <span class="dashboard-muted fs-7 d-block mb-1">

                                    <i class="bi bi-person me-1"></i>

                                    {{ $agendaPengisi }}

                                </span>

                                @endif

                                @if($jadwal->mata_pelajaran)

                                <span class="dashboard-muted fs-7 d-block mb-1">

                                    <i class="bi bi-book me-1"></i>

                                    {{ $jadwal->mata_pelajaran }}

                                </span>

                                @endif

                                @else

                                @if($agendaLokasi)

                                <span class="dashboard-muted fs-7 d-block mb-1">

                                    <i class="bi bi-geo-alt me-1"></i>

                                    {{ $agendaLokasi }}

                                </span>

                                @endif

                                @if($agendaKeterangan)

                                <span class="dashboard-muted fs-7 d-block mb-1">

                                    <i class="bi bi-card-text me-1"></i>

                                    {{ \Illuminate\Support\Str::limit($agendaKeterangan, 80) }}

                                </span>

                                @endif

                                @endif

                                @if(isset($jadwal->status) || ($jadwal->jenis ?? 'supervisi') === 'kegiatan')

                                <span class="dashboard-muted fs-7 d-block">

                                    <i class="bi bi-check-circle me-1"></i>

                                    {{ ucfirst($agendaStatus) }}

                                </span>

                                @else

                                <span class="dashboard-muted fs-7 d-block">

                                    <i class="bi bi-calendar-check me-1"></i>

                                    Terjadwal

                                </span>

                                @endif

                            </div>


                            <i class="bi bi-chevron-right dashboard-agenda-arrow"></i>

                        </a>

                        @empty

                        <div class="dashboard-muted text-center py-8">

                            <i class="bi bi-calendar2-x fs-2x d-block mb-3"></i>

                            <div class="fw-semibold mb-1">

                                Belum ada agenda kegiatan

                            </div>

                            <div class="fs-8">

                                Belum terdapat agenda supervisi maupun kegiatan tambahan yang akan datang.

                            </div>

                        </div>

                        @endforelse

                    </div>


                    @if(isset($jadwalMendatang) && $jadwalMendatang->count() > 0)

                    <div class="card-footer border-0 pt-0">

                        <div class="dashboard-agenda-footer">

                            <i class="bi bi-calendar-check fs-3 text-primary"></i>

                            <span class="dashboard-muted fs-7">

                                {{ $jumlahJadwalMendatang }}
                                agenda mendatang

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
    /* ==========================================================
       DASHBOARD CONTAINER
    ========================================================== */

    .dashboard-container {
        padding-top: 0.5rem;
        padding-bottom: 2rem;
    }


    /* ==========================================================
       HEADER
    ========================================================== */

    .dashboard-welcome {
        color: var(--bs-gray-900, #181c32) !important;
        font-size: 1.35rem;
        line-height: 1.5;
    }

    .dashboard-description {
        color: var(--bs-gray-600, #7e8299);
        font-size: 0.9rem;
        line-height: 1.7;
        margin-top: -1rem;
    }


    /* ==========================================================
       LAYOUT
    ========================================================== */

    .dashboard-layout {
        display: grid;
        grid-template-columns:
            minmax(0, 7fr) minmax(0, 4.3fr);

        gap: 1.25rem;
        align-items: start;
    }


    .dashboard-main,
    .dashboard-agenda {
        min-width: 0;
    }


    /* ==========================================================
       PANEL
    ========================================================== */

    .dashboard-panel {
        border: 1px solid var(--bs-border-color, #dce8f8);
        border-radius: 12px;

        box-shadow:
            0 2px 8px rgba(35, 75, 130, 0.025);

        background: var(--bs-card-bg, #fff);
        color: var(--bs-body-color, #181c32);

        overflow: hidden;
    }


    .dashboard-panel .card-header,
    .dashboard-panel .card-body,
    .dashboard-panel .card-footer {
        color: var(--bs-body-color, #181c32);
    }


    /* ==========================================================
       HEADING
    ========================================================== */

    .dashboard-heading {
        color: var(--bs-gray-900, #181c32) !important;
        line-height: 1.4;
    }


    .dashboard-muted {
        color: var(--bs-gray-600, #7e8299);
    }


    /* ==========================================================
       ICON
    ========================================================== */

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


    .dashboard-section-icon i {
        color: #2478ed !important;
    }


    /* ==========================================================
       DONUT CHART
    ========================================================== */

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

        font-size: 1.7rem;
        font-weight: 800;

        line-height: 1.2;
    }


    /* ==========================================================
       DETAIL PANEL
    ========================================================== */

    .dashboard-detail-panel {
        background: var(--bs-secondary-bg, #f8fbff);

        border: 1px solid var(--bs-border-color, #e4e8f0);

        border-radius: 10px;

        padding: 1.25rem;
    }


    .dashboard-detail-row {
        display: grid;

        grid-template-columns:
            minmax(140px, 0.9fr) minmax(80px, 1fr) 35px;

        align-items: center;

        gap: 0.75rem;
    }


    .dashboard-detail-label {
        color: var(--bs-gray-700, #454e85);
    }


    .dashboard-detail-dot {
        width: 8px;
        height: 8px;

        border-radius: 50%;

        flex-shrink: 0;
    }


    .detail-dot-success {
        background: #50cd89;
    }


    .detail-dot-danger {
        background: #f1416c;
    }


    .dashboard-detail-progress {
        width: 100%;
    }


    /* ==========================================================
       INFO BOX
    ========================================================== */

    .dashboard-info-box {
        padding: 0.85rem 1rem;

        border-radius: 9px;

        background: var(--bs-secondary-bg, #f1f7ff);
    }


    /* ==========================================================
       BUTTON
    ========================================================== */

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


    /* ==========================================================
       AGENDA
    ========================================================== */

    .dashboard-agenda-item {
        position: relative;
        display: flex;

        align-items: flex-start;

        gap: 1rem;

        min-width: 0;

        padding: 0.75rem 0.85rem 0.75rem 1rem;

        text-decoration: none;

        color: inherit;

        border-radius: 8px;

        transition:
            background 0.2s ease;
    }


    .dashboard-agenda-item::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0.35rem;
        bottom: 0.35rem;
        width: 3px;
        border-radius: 999px;
        background: linear-gradient(180deg,
                #2478ed 0%,
                #71a9ff 100%);
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


    .dashboard-agenda-title {
        color: var(--bs-gray-900, #181c32) !important;
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

        background:
            var(--bs-secondary-bg, #f1f7ff);
    }


    /* ==========================================================
       STATISTIK SUPERVISI
    ========================================================== */

    .supervisi-chart-wrapper {
        position: relative;

        width: 100%;

        min-height: 250px;

        padding: 1rem;

        border-radius: 10px;

        background:
            linear-gradient(to bottom,
                rgba(36, 120, 237, 0.04),
                rgba(36, 120, 237, 0.01));
    }


    #supervisiChart {
        width: 100% !important;
        max-height: 250px;
    }


    /* ==========================================================
       DARK MODE
    ========================================================== */

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


    [data-bs-theme="dark"] .dashboard-detail-panel {
        background: #252538;
    }


    [data-bs-theme="dark"] .dashboard-section-icon {
        background: #252d41;

        color: #79aaff;
    }


    [data-bs-theme="dark"] .dashboard-section-icon i {
        color: #79aaff !important;
    }


    [data-bs-theme="dark"] .dashboard-info-box,
    [data-bs-theme="dark"] .dashboard-agenda-footer {
        background: #252538;
    }


    [data-bs-theme="dark"] .dashboard-agenda-date {
        background: #252d41;

        color: #79aaff;
    }


    [data-bs-theme="dark"] .dashboard-agenda-arrow {
        color: #7e8299;
    }


    [data-bs-theme="dark"] .supervisi-chart-wrapper {
        background:
            rgba(121, 170, 255, 0.04);
    }


    /* ==========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 1199.98px) {

        .dashboard-layout {
            grid-template-columns:
                minmax(0, 1fr) minmax(0, 1fr);
        }

    }


    @media (max-width: 991.98px) {

        .dashboard-layout {
            grid-template-columns: 1fr;
        }


        .dashboard-main,
        .dashboard-agenda {
            width: 100%;
        }

    }


    @media (max-width: 767.98px) {

        .dashboard-container {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }


        .dashboard-welcome {
            font-size: 1.15rem;
            margin-bottom: 1.5rem !important;
        }


        .dashboard-description {
            font-size: 0.8rem;
        }


        .dashboard-panel .card-header {
            padding: 1.25rem !important;
        }


        .dashboard-panel .card-body {
            padding: 1.25rem !important;
        }


        .dashboard-section-icon {
            width: 42px;
            height: 42px;

            border-radius: 13px;

            font-size: 1.2rem;
        }


        .dashboard-detail-row {
            grid-template-columns:
                minmax(95px, 0.9fr) minmax(50px, 1fr) 30px;

            gap: 0.65rem;
        }


        .dashboard-donut-chart {
            max-width: 195px;
        }


        .dashboard-agenda-item {
            gap: 0.75rem;
        }


        .dashboard-agenda-date {
            width: 48px;
            min-width: 48px;

            min-height: 60px;
        }


        .dashboard-agenda-day {
            font-size: 1.1rem;
        }


        .dashboard-agenda-month {
            font-size: 0.7rem;
        }


        .supervisi-chart-wrapper {
            min-height: 210px;

            padding: 0.5rem;
        }

    }


    @media (prefers-reduced-motion: reduce) {

        .dashboard-agenda-item {
            transition: none;
        }

    }
</style>


@endsection


@section('scripts')

{{-- ==========================================================
    CHART.JS
=========================================================== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {


        /* ==========================================================
           DIAGRAM DONAT KELENGKAPAN DOKUMEN
        =========================================================== */

        const documentCanvas =
            document.getElementById('dashboardDocumentChart');


        if (
            documentCanvas &&
            typeof Chart !== 'undefined'
        ) {

            const completeDocuments = @json($dokumenLengkap);
            const incompleteDocuments = @json($dokumenBelumLengkap);


            new Chart(documentCanvas, {

                type: 'doughnut',

                data: {

                    labels: [
                        'Dokumen Lengkap',
                        'Belum Lengkap'
                    ],

                    datasets: [{

                        data: [
                            completeDocuments,
                            incompleteDocuments
                        ],

                        backgroundColor: [
                            '#50cd89',
                            '#e4e6ef'
                        ],

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

                                label: function(context) {

                                    return context.label +
                                        ': ' +
                                        context.raw;

                                }

                            }

                        }

                    }

                }

            });

        }


        /* ==========================================================
           DATA SUPERVISI
        =========================================================== */

        const allSupervisiData =
            @json($supervisiSelesai ?? []);


        /* ==========================================================
           ELEMEN SUPERVISI
        =========================================================== */

        const supervisiCountLabel =
            document.getElementById('supervisiCountLabel');

        const supervisiRangeText =
            document.getElementById('supervisiRangeText');

        const supervisiCanvas =
            document.getElementById('supervisiChart');


        let supervisiChart = null;


        /* ==========================================================
           FORMAT TANGGAL
        =========================================================== */

        function parseTanggal(value) {

            if (!value) {
                return null;
            }

            const date = new Date(value);

            return isNaN(date.getTime()) ?
                null :
                date;

        }


        /* ==========================================================
           RENDER STATISTIK SUPERVISI
        =========================================================== */

        function renderSupervisiChart(startDate = null, endDate = null) {

            let filteredData =
                Array.isArray(allSupervisiData) ?
                allSupervisiData : [];


            if (startDate && endDate) {

                filteredData = filteredData.filter(function(item) {

                    const tanggal =
                        parseTanggal(item.tanggal);

                    if (!tanggal) {
                        return false;
                    }

                    return (
                        tanggal >= startDate &&
                        tanggal <= endDate
                    );

                });

            }


            /* -----------------------------------------------
               Hitung guru unik
            ------------------------------------------------ */

            const guruIds = new Set();


            filteredData.forEach(function(item) {

                const guruId =
                    item.guru_id ??
                    item.guruId ??
                    item.id_guru ??
                    item.guru?.id ??
                    item.guru?.id_guru ??
                    item.id;


                if (guruId !== undefined && guruId !== null) {

                    guruIds.add(String(guruId));

                }

            });


            if (supervisiCountLabel) {

                supervisiCountLabel.textContent =
                    guruIds.size;

            }


            /* -----------------------------------------------
               Buat bucket berdasarkan bulan
            ------------------------------------------------ */

            const monthlyData = {};


            filteredData.forEach(function(item) {

                const tanggal =
                    parseTanggal(item.tanggal);


                if (!tanggal) {
                    return;
                }


                const year =
                    tanggal.getFullYear();

                const month =
                    tanggal.getMonth();


                const key =
                    `${year}-${String(month + 1).padStart(2, '0')}`;


                if (!monthlyData[key]) {

                    monthlyData[key] = {

                        year: year,

                        month: month,

                        count: 0

                    };

                }


                monthlyData[key].count++;

            });


            const sortedMonths =
                Object.values(monthlyData)
                .sort(function(a, b) {

                    if (a.year !== b.year) {
                        return a.year - b.year;
                    }

                    return a.month - b.month;

                });


            const labels =
                sortedMonths.map(function(item) {

                    return new Date(
                        item.year,
                        item.month,
                        1
                    ).toLocaleDateString(
                        'id-ID', {
                            month: 'short'
                        }
                    );

                });


            const values =
                sortedMonths.map(function(item) {

                    return item.count;

                });


            /* -----------------------------------------------
               Jika tidak ada data
            ------------------------------------------------ */

            if (labels.length === 0) {

                labels.push('Tidak ada data');
                values.push(0);

            }


            /* -----------------------------------------------
               Update range
            ------------------------------------------------ */

            if (supervisiRangeText) {

                if (startDate && endDate) {

                    const startText =
                        startDate.toLocaleDateString(
                            'id-ID', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            }
                        );

                    const endText =
                        endDate.toLocaleDateString(
                            'id-ID', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            }
                        );

                    supervisiRangeText.textContent =
                        `${startText} - ${endText}`;

                } else {

                    supervisiRangeText.textContent =
                        'Seluruh data';

                }

            }


            /* -----------------------------------------------
               Destroy chart sebelumnya
            ------------------------------------------------ */

            if (supervisiChart) {

                supervisiChart.destroy();

            }


            if (!supervisiCanvas ||
                typeof Chart === 'undefined') {

                return;

            }


            supervisiChart =
                new Chart(supervisiCanvas, {

                    type: 'bar',

                    data: {

                        labels: labels,

                        datasets: [{

                            label: 'Supervisi',

                            data: values,

                            backgroundColor: '#2478ed',

                            borderRadius: 5,

                            borderSkipped: false

                        }]

                    },

                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        plugins: {

                            legend: {
                                display: false
                            },

                            tooltip: {

                                callbacks: {

                                    label: function(context) {

                                        return (
                                            ' ' +
                                            context.raw +
                                            ' supervisi'
                                        );

                                    }

                                }

                            }

                        },

                        scales: {

                            x: {

                                grid: {
                                    display: false
                                },

                                ticks: {

                                    color: '#7e8299'

                                }

                            },

                            y: {

                                beginAtZero: true,

                                ticks: {

                                    precision: 0,

                                    color: '#7e8299'

                                },

                                grid: {

                                    color: 'rgba(126,130,153,0.12)'

                                }

                            }

                        }

                    }

                });

        }


        /* ==========================================================
           INITIAL CHART
        =========================================================== */

        renderSupervisiChart();


        /* ==========================================================
           DATE RANGE PICKER
        =========================================================== */

        const dateRangeInput =
            $('#supervisiDateRange');


        if (
            dateRangeInput.length &&
            typeof $.fn.daterangepicker !== 'undefined'
        ) {

            dateRangeInput.daterangepicker({

                autoUpdateInput: false,

                locale: {

                    format: 'DD/MM/YYYY',

                    separator: ' - ',

                    applyLabel: 'Terapkan',

                    cancelLabel: 'Batal',

                    customRangeLabel: 'Rentang Khusus',

                    daysOfWeek: [
                        'Mg',
                        'Sn',
                        'Sl',
                        'Rb',
                        'Km',
                        'Jm',
                        'Sb'
                    ],

                    monthNames: [
                        'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember'
                    ],

                    firstDay: 1

                },

                ranges: {

                    'Bulan Ini': [

                        moment().startOf('month'),
                        moment().endOf('month')

                    ],

                    '3 Bulan Terakhir': [

                        moment().subtract(2, 'months').startOf('month'),
                        moment().endOf('month')

                    ],

                    '6 Bulan Terakhir': [

                        moment().subtract(5, 'months').startOf('month'),
                        moment().endOf('month')

                    ],

                    'Tahun Ini': [

                        moment().startOf('year'),
                        moment().endOf('year')

                    ]

                }

            });


            dateRangeInput.on(
                'apply.daterangepicker',
                function(ev, picker) {

                    $(this).val(

                        picker.startDate.format('DD/MM/YYYY') +
                        ' - ' +
                        picker.endDate.format('DD/MM/YYYY')

                    );


                    const startDate =
                        picker.startDate
                        .toDate();


                    const endDate =
                        picker.endDate
                        .toDate();


                    endDate.setHours(
                        23,
                        59,
                        59,
                        999
                    );


                    renderSupervisiChart(
                        startDate,
                        endDate
                    );

                }
            );


            dateRangeInput.on(
                'cancel.daterangepicker',
                function() {

                    $(this).val('');

                    renderSupervisiChart();

                }
            );

        }


        /* ==========================================================
           PENCARIAN GLOBAL
        =========================================================== */

        const searchInput =
            document.getElementById(
                'headerGlobalSearch'
            );

        const searchForm =
            document.getElementById(
                'headerSearchForm'
            );


        if (
            searchInput &&
            searchForm
        ) {

            const mappings = [

                {
                    key: 'dashboard',

                    match: [
                        'dashboard',
                        'beranda',
                        'home'
                    ],

                    url: @json(route('admin.dashboard'))
                },

                {
                    key: 'guru',

                    match: [
                        'guru',
                        'data guru',
                        'daftar guru',
                        'tenaga pendidik'
                    ],

                    url: @json(route('admin.guru.index'))
                },

                {
                    key: 'dokumen',

                    match: [
                        'dokumen',
                        'file',
                        'arsip',
                        'kelengkapan dokumen'
                    ],

                    url: @json(route('dokumen.index'))
                },

                {
                    key: 'supervisi',

                    match: [
                        'supervisi',
                        'pengawasan',
                        'monitoring',
                        'jadwal supervisi'
                    ],

                    url: @json(route('admin.supervisi.index'))
                }

            ];


            const normalize =
                value =>
                (value || '')
                .toLowerCase()
                .trim();


            function handleSearch() {

                const query =
                    normalize(
                        searchInput.value
                    );


                if (!query) {
                    return;
                }


                const matched =
                    mappings.find(function(item) {

                        return item.match.some(
                            function(keyword) {

                                return query.includes(
                                    keyword
                                );

                            }
                        );

                    });


                if (!matched) {
                    return;
                }


                window.location.href =
                    matched.url;

            }


            searchInput.addEventListener(
                'keydown',
                function(event) {

                    if (event.key === 'Enter') {

                        event.preventDefault();

                        handleSearch();

                    }

                }
            );

        }

    });
</script>

@endsection