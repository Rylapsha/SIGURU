@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="siguru-dashboard">

    {{-- HEADER --}}
    <div class="dashboard-heading">
        <div>
            <h1>Dashboard Admin</h1>
            <p>Ringkasan data guru dan jadwal supervisi sekolah.</p>
        </div>
    </div>

    {{-- LAYOUT UTAMA --}}
    <div class="dashboard-grid">

        {{-- KOLOM KIRI --}}
        <div class="left-column">

            {{-- TOTAL GURU --}}
            <div class="total-guru-card">
                <div class="total-guru-icon">
                    <i class="ki-outline ki-people"></i>
                </div>

                <div class="total-guru-content">
                    <span class="total-guru-label">Total Guru</span>
                    <h2>{{ number_format($totalGuru, 0, ',', '.') }}</h2>
                    <span class="total-guru-description">
                        Guru terdaftar di SIGURU
                    </span>
                </div>
            </div>

            {{-- GRAFIK BATANG VERTIKAL --}}
            <div class="dashboard-panel chart-panel">
                <div class="panel-heading">
                    <div>
                        <h3>Statistik Guru</h3>
                        <p>Jumlah guru berdasarkan mata pelajaran</p>
                    </div>

                    <div class="panel-heading-icon">
                        <i class="ki-outline ki-chart-simple"></i>
                    </div>
                </div>

                @if($statistikMapel->isNotEmpty())
                    @php
                        $jumlahMaksimum = max(
                            1,
                            (int) $statistikMapel->max('jumlah')
                        );
                    @endphp

                    <div class="bar-chart">
                        @foreach($statistikMapel as $item)
                            <div class="bar-chart-item">
                                <span class="bar-chart-count">
                                    {{ $item->jumlah }}
                                </span>

                                <div class="bar-chart-track">
                                    <div
                                        class="bar-chart-bar"
                                        style="height: {{ max(8, ($item->jumlah / $jumlahMaksimum) * 100) }}%;">
                                    </div>
                                </div>

                                <span
                                    class="bar-chart-label"
                                    title="{{ $item->mata_pelajaran }}">
                                    {{ $item->mata_pelajaran }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="chart-footer">
                        <span class="chart-legend"></span>
                        Jumlah guru per mata pelajaran
                    </div>
                @else
                    <div class="empty-chart">
                        <i class="ki-outline ki-chart-simple"></i>
                        <h4>Belum ada data mata pelajaran</h4>
                        <p>Grafik akan muncul setelah data mata pelajaran tersedia.</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- KOLOM KANAN: JADWAL SUPERVISI --}}
        <div class="dashboard-panel schedule-panel">

            <div class="panel-heading">
                <div>
                    <h3>Jadwal Supervisi</h3>
                    <p>Daftar supervisi mendatang</p>
                </div>

                <a href="{{ route('admin.supervisi.index') }}"
                   class="view-all-link">
                    Lihat Semua
                    <i class="ki-outline ki-arrow-right"></i>
                </a>
            </div>

            <div class="schedule-list">
                @forelse($jadwalMendatang as $jadwal)
                    <div class="schedule-item">

                        <div class="schedule-date">
                            <span class="schedule-day">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d') }}
                            </span>

                            <span class="schedule-month">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->translatedFormat('M') }}
                            </span>
                        </div>

                        <div class="schedule-detail">
                            <h4>
                                {{ $jadwal->nama_lengkap ?? 'Guru belum ditentukan' }}
                            </h4>

                            <p class="schedule-subject">
                                {{ $jadwal->mata_pelajaran ?: 'Mata pelajaran belum ditentukan' }}
                            </p>

                            <div class="schedule-time">
                                <i class="ki-outline ki-time"></i>

                                <span>
                                    {{ $jadwal->jam ? \Carbon\Carbon::parse($jadwal->jam)->format('H:i') . ' WIB' : 'Jam belum ditentukan' }}
                                </span>
                            </div>
                        </div>

                        <span class="schedule-status">Terjadwal</span>
                    </div>
                @empty
                    <div class="empty-schedule">
                        <div class="empty-schedule-icon">
                            <i class="ki-outline ki-calendar-tick"></i>
                        </div>

                        <h4>Belum ada jadwal supervisi</h4>

                        <p>
                            Jadwal supervisi mendatang akan ditampilkan di sini.
                        </p>

                        <a href="{{ route('admin.supervisi.index') }}">
                            Kelola Jadwal
                        </a>
                    </div>
                @endforelse
            </div>

        </div>

    </div>
</div>

<style>
    /* =========================================
       DASAR DAN WARNA TEMA
    ========================================= */

    .siguru-dashboard {
        --panel-bg: #ffffff;
        --panel-border: #e2e8f0;
        --text-main: #17243a;
        --text-muted: #64748b;
        --blue-soft: #e8f1ff;
        --blue: #3478e5;

        width: 100%;
        padding: 26px 30px;
        color: var(--text-main);
        font-family: Inter, sans-serif;
        box-sizing: border-box;
    }

    .siguru-dashboard *,
    .siguru-dashboard *::before,
    .siguru-dashboard *::after {
        box-sizing: border-box;
    }

    /* =========================================
       MODE TERANG
    ========================================= */

    html[data-admin-theme="light"] .siguru-dashboard {
        --panel-bg: #ffffff;
        --panel-border: #e2e8f0;
        --text-main: #17243a;
        --text-muted: #64748b;
        --blue-soft: #e8f1ff;
        --blue: #3478e5;
    }

    html[data-admin-theme="light"] .dashboard-panel,
    html[data-admin-theme="light"] .total-guru-card {
        background: #ffffff;
        border-color: #e2e8f0;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .04);
    }

    html[data-admin-theme="light"] .dashboard-heading h1,
    html[data-admin-theme="light"] .panel-heading h3,
    html[data-admin-theme="light"] .total-guru-content h2,
    html[data-admin-theme="light"] .schedule-detail h4,
    html[data-admin-theme="light"] .empty-chart h4,
    html[data-admin-theme="light"] .empty-schedule h4 {
        color: #17243a;
    }

    html[data-admin-theme="light"] .dashboard-heading p,
    html[data-admin-theme="light"] .panel-heading p,
    html[data-admin-theme="light"] .total-guru-label,
    html[data-admin-theme="light"] .total-guru-description,
    html[data-admin-theme="light"] .bar-chart-label,
    html[data-admin-theme="light"] .bar-chart-count,
    html[data-admin-theme="light"] .chart-footer,
    html[data-admin-theme="light"] .schedule-subject,
    html[data-admin-theme="light"] .schedule-time,
    html[data-admin-theme="light"] .empty-chart p,
    html[data-admin-theme="light"] .empty-schedule p {
        color: #64748b;
    }

    html[data-admin-theme="light"] .total-guru-icon,
    html[data-admin-theme="light"] .panel-heading-icon,
    html[data-admin-theme="light"] .schedule-date,
    html[data-admin-theme="light"] .empty-schedule-icon {
        background: #e8f1ff;
        border-color: #d5e5ff;
        color: #2563eb;
    }

    html[data-admin-theme="light"] .schedule-day,
    html[data-admin-theme="light"] .schedule-month {
        color: #2563eb;
    }

    html[data-admin-theme="light"] .bar-chart-track {
        background: #e9eff7;
    }

    html[data-admin-theme="light"] .panel-heading,
    html[data-admin-theme="light"] .schedule-item,
    html[data-admin-theme="light"] .chart-footer {
        border-color: #e8edf4;
    }

    html[data-admin-theme="light"] .schedule-status {
        color: #15803d;
        background: #dcfce7;
        border-color: #bbf7d0;
    }

    /* =========================================
       MODE GELAP
    ========================================= */

    html[data-admin-theme="dark"] .siguru-dashboard {
        --panel-bg: #111d30;
        --panel-border: #26364e;
        --text-main: #edf3fc;
        --text-muted: #91a1b8;
        --blue-soft: #1b3557;
        --blue: #438cf5;
    }

    html[data-admin-theme="dark"] .dashboard-panel,
    html[data-admin-theme="dark"] .total-guru-card {
        background: #111d30;
        border-color: #26364e;
        box-shadow: none;
    }

    html[data-admin-theme="dark"] .dashboard-heading h1,
    html[data-admin-theme="dark"] .panel-heading h3,
    html[data-admin-theme="dark"] .total-guru-content h2,
    html[data-admin-theme="dark"] .schedule-detail h4,
    html[data-admin-theme="dark"] .empty-chart h4,
    html[data-admin-theme="dark"] .empty-schedule h4 {
        color: #edf3fc;
    }

    html[data-admin-theme="dark"] .dashboard-heading p,
    html[data-admin-theme="dark"] .panel-heading p,
    html[data-admin-theme="dark"] .total-guru-label,
    html[data-admin-theme="dark"] .total-guru-description,
    html[data-admin-theme="dark"] .bar-chart-label,
    html[data-admin-theme="dark"] .bar-chart-count,
    html[data-admin-theme="dark"] .chart-footer,
    html[data-admin-theme="dark"] .schedule-subject,
    html[data-admin-theme="dark"] .schedule-time,
    html[data-admin-theme="dark"] .empty-chart p,
    html[data-admin-theme="dark"] .empty-schedule p {
        color: #91a1b8;
    }

    html[data-admin-theme="dark"] .total-guru-icon,
    html[data-admin-theme="dark"] .panel-heading-icon,
    html[data-admin-theme="dark"] .schedule-date,
    html[data-admin-theme="dark"] .empty-schedule-icon {
        background: #1b3557;
        border-color: #2c4a70;
        color: #8abaff;
    }

    html[data-admin-theme="dark"] .schedule-day,
    html[data-admin-theme="dark"] .schedule-month {
        color: #b5d6ff;
    }

    html[data-admin-theme="dark"] .bar-chart-track {
        background: #1c2b40;
    }

    html[data-admin-theme="dark"] .panel-heading,
    html[data-admin-theme="dark"] .schedule-item,
    html[data-admin-theme="dark"] .chart-footer {
        border-color: #26354a;
    }

    html[data-admin-theme="dark"] .schedule-status {
        color: #7de0b1;
        background: #15382f;
        border-color: #245344;
    }

    /* =========================================
       HEADER DASHBOARD
    ========================================= */

    .dashboard-heading {
        margin-bottom: 24px;
    }

    .dashboard-heading h1 {
        margin: 0 0 7px;
        font-size: 26px;
        font-weight: 750;
        letter-spacing: -.5px;
    }

    .dashboard-heading p {
        margin: 0;
        font-size: 13px;
        line-height: 1.6;
    }

    /* Hilangkan tanggal tambahan */
    .dashboard-date {
        display: none !important;
    }

    /* =========================================
       GRID UTAMA
    ========================================= */

    .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(0, .75fr);
        align-items: stretch;
        gap: 18px;
    }

    .left-column {
        display: flex;
        flex-direction: column;
        gap: 18px;
        min-width: 0;
    }

    /* =========================================
       KARTU TOTAL GURU
    ========================================= */

    .total-guru-card {
        width: 100%;
        max-width: 360px;
        min-height: 120px;
        display: flex;
        align-items: center;
        gap: 19px;
        padding: 22px;
        border: 1px solid var(--panel-border);
        border-radius: 12px;
    }

    .total-guru-icon {
        width: 58px;
        height: 58px;
        flex: 0 0 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        background: var(--blue-soft);
        color: var(--blue);
    }

    .total-guru-icon i {
        font-size: 27px;
    }

    .total-guru-content {
        min-width: 0;
    }

    .total-guru-label {
        display: block;
        margin-bottom: 5px;
        font-size: 13px;
        font-weight: 500;
    }

    .total-guru-content h2 {
        margin: 0;
        font-size: 34px;
        font-weight: 750;
        line-height: 1.2;
    }

    .total-guru-description {
        display: block;
        margin-top: 5px;
        font-size: 11px;
    }

    /* =========================================
       PANEL UMUM
    ========================================= */

    .dashboard-panel {
        min-width: 0;
        padding: 23px;
        border: 1px solid var(--panel-border);
        border-radius: 13px;
    }

    .panel-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding-bottom: 19px;
        border-bottom: 1px solid var(--panel-border);
    }

    .panel-heading h3 {
        margin: 0 0 6px;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.5;
    }

    .panel-heading p {
        margin: 0;
        font-size: 11px;
        line-height: 1.6;
    }

    .panel-heading-icon {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: var(--blue-soft);
        color: var(--blue);
    }

    .panel-heading-icon i {
        font-size: 21px;
    }

    /* =========================================
       GRAFIK BATANG VERTIKAL
    ========================================= */

    .chart-panel {
        flex: 1;
        min-height: 330px;
    }

    .bar-chart {
        display: flex;
        align-items: flex-end;
        gap: 13px;
        min-height: 245px;
        padding: 25px 5px 0;
        overflow-x: auto;
    }

    .bar-chart-item {
        display: flex;
        flex: 1 0 44px;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        min-width: 0;
        height: 220px;
    }

    .bar-chart-count {
        margin-bottom: 7px;
        font-size: 11px;
        font-weight: 650;
    }

    .bar-chart-track {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        width: 100%;
        max-width: 43px;
        height: 155px;
        background: #e9eff7;
        border-radius: 7px 7px 3px 3px;
        overflow: hidden;
    }

    .bar-chart-bar {
        width: 100%;
        min-height: 8px;
        background: linear-gradient(180deg, #67adff 0%, #3478e5 100%);
        border-radius: 6px 6px 2px 2px;
        transition: height .25s ease;
    }

    .bar-chart-label {
        width: 100%;
        margin-top: 10px;
        font-size: 10px;
        text-align: center;
        overflow-wrap: anywhere;
        line-height: 1.5;
    }

    .chart-footer {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid var(--panel-border);
        font-size: 10px;
    }

    .chart-legend {
        width: 8px;
        height: 8px;
        flex: 0 0 8px;
        background: #438cf5;
        border-radius: 3px;
    }

    /* =========================================
       JADWAL SUPERVISI
    ========================================= */

    .schedule-panel {
        align-self: stretch;
        min-height: 100%;
    }

    .view-all-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        flex-shrink: 0;
        padding-top: 4px;
        color: #3478e5;
        font-size: 11px;
        font-weight: 650;
        text-decoration: none;
        white-space: nowrap;
    }

    html[data-admin-theme="dark"] .view-all-link {
        color: #8abaff;
    }

    .view-all-link:hover {
        opacity: .8;
    }

    .view-all-link i {
        font-size: 15px;
    }

    .schedule-list {
        padding-top: 5px;
    }

    .schedule-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 18px 0;
        border-bottom: 1px solid var(--panel-border);
    }

    .schedule-item:last-child {
        border-bottom: 0;
    }

    .schedule-date {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 49px;
        min-width: 49px;
        min-height: 55px;
        border: 1px solid #d5e5ff;
        border-radius: 9px;
        background: var(--blue-soft);
    }

    .schedule-day {
        font-size: 20px;
        font-weight: 750;
        line-height: 1.1;
    }

    .schedule-month {
        margin-top: 5px;
        font-size: 10px;
        font-weight: 650;
        text-transform: uppercase;
    }

    .schedule-detail {
        flex: 1;
        min-width: 0;
    }

    .schedule-detail h4 {
        margin: 1px 0 5px;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .schedule-subject {
        margin: 0 0 7px;
        font-size: 11px;
        line-height: 1.5;
    }

    .schedule-time {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 10px;
    }

    .schedule-time i {
        font-size: 13px;
    }

    .schedule-status {
        display: inline-flex;
        align-items: center;
        flex-shrink: 0;
        padding: 5px 8px;
        border: 1px solid #bbf7d0;
        border-radius: 6px;
        background: #dcfce7;
        color: #15803d;
        font-size: 9px;
        font-weight: 650;
        white-space: nowrap;
    }

    /* =========================================
       EMPTY STATE
    ========================================= */

    .empty-chart,
    .empty-schedule {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 35px 18px;
        text-align: center;
    }

    .empty-chart {
        min-height: 245px;
    }

    .empty-chart > i {
        margin-bottom: 14px;
        font-size: 34px;
        color: #8aa2c2;
    }

    .empty-chart h4,
    .empty-schedule h4 {
        margin: 0 0 8px;
        font-size: 13px;
        font-weight: 650;
    }

    .empty-chart p,
    .empty-schedule p {
        max-width: 260px;
        margin: 0;
        font-size: 11px;
        line-height: 1.7;
    }

    .empty-schedule {
        min-height: 300px;
    }

    .empty-schedule-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        border-radius: 12px;
        background: var(--blue-soft);
        color: var(--blue);
    }

    .empty-schedule-icon i {
        font-size: 25px;
    }

    .empty-schedule a {
        display: inline-flex;
        align-items: center;
        margin-top: 17px;
        padding: 10px 15px;
        border-radius: 7px;
        background: #245da5;
        color: #ffffff;
        font-size: 11px;
        font-weight: 650;
        text-decoration: none;
    }

    .empty-schedule a:hover {
        background: #3478cf;
    }

    /* =========================================
       RESPONSIVE
    ========================================= */

    @media (max-width: 1100px) {
        .siguru-dashboard {
            padding: 22px 18px;
        }

        .dashboard-grid {
            gap: 14px;
        }

        .dashboard-panel {
            padding: 18px;
        }
    }

    @media (max-width: 767px) {
        .siguru-dashboard {
            padding: 16px;
        }

        .dashboard-heading {
            margin-bottom: 18px;
        }

        .dashboard-heading h1 {
            font-size: 22px;
        }

        .dashboard-grid {
            grid-template-columns: minmax(0, 1fr);
            gap: 16px;
        }

        .left-column {
            gap: 16px;
        }

        .total-guru-card {
            max-width: 100%;
            min-height: 120px;
            padding: 19px;
        }

        .dashboard-panel {
            padding: 17px;
        }

        .chart-panel {
            min-height: 0;
        }

        .schedule-panel {
            min-height: auto;
        }

        .schedule-item {
            gap: 9px;
        }

        .schedule-status {
            padding: 4px 6px;
            font-size: 8px;
        }

        .bar-chart {
            gap: 10px;
        }
    }
</style>
@endsection
