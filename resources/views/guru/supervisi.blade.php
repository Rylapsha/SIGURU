@extends('layouts.app')

@section('content')
<style>
    .sp-page {
        color: #25365d;
        max-width: 1600px;
        padding: 16px 12px 28px;
    }

    .sp-heading {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 22px;
    }

    .sp-icon {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #e5edff;
        color: #3568dc;
        font-size: 24px;
        font-weight: 700;
    }

    .sp-heading h1 {
        font-size: 27px;
        font-weight: 750;
        letter-spacing: -0.5px;
        margin: 0 0 4px;
        color: #26365b;
    }

    .sp-subtitle {
        font-size: 12px;
        line-height: 1.6;
        color: #75819a;
        margin: 0;
    }

    .sp-card {
        background: #fff;
        border: 1px solid #e7edf6;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(38, 54, 91, .035);
        overflow: hidden;
        min-width: 0;
    }

    .sp-card-header {
        min-height: 50px;
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px 16px;
        border-bottom: 1px solid #edf1f7;
    }

    .sp-card-header h3 {
        font-size: 14px;
        font-weight: 700;
        margin: 0;
        color: #2b3d64;
    }

    .sp-small-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #e8efff;
        color: #386bdd;
        font-size: 19px;
        font-weight: 700;
    }

    .sp-top-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .sp-next-body {
        padding: 12px;
    }

    .sp-next-panel {
        min-height: 140px;
        display: grid;
        grid-template-columns: 100px minmax(0, 1fr);
        align-items: center;
        gap: 18px;
        padding: 14px 18px;
        background: linear-gradient(110deg, #f0f5ff, #f7faff);
        border-radius: 9px;
    }

    .sp-date-box {
        min-height: 116px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        background: #e0ebff;
        border-radius: 10px;
        padding: 12px 5px;
    }

    .sp-date-box .day {
        font-size: 12px;
        font-weight: 600;
    }

    .sp-date-box .number {
        font-size: 32px;
        line-height: 1.25;
        font-weight: 800;
        margin: 2px 0;
    }

    .sp-date-box .month {
        font-size: 12px;
    }

    .sp-next-info {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: center;
        gap: 18px;
    }

    .sp-info-list {
        display: grid;
        gap: 10px;
        min-width: 0;
    }

    .sp-info-item {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        min-width: 0;
    }

    .sp-info-symbol {
        width: 23px;
        flex-shrink: 0;
        color: #344b78;
        font-size: 19px;
        font-weight: 700;
        text-align: center;
    }

    .sp-info-label {
        display: block;
        font-size: 11px;
        color: #77839c;
        margin-bottom: 2px;
    }

    .sp-info-value {
        display: block;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.6;
        color: #2d3d61;
        overflow-wrap: anywhere;
    }

    .sp-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 13px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #c9d7f1;
        border-radius: 8px;
        color: #315fc7;
        background: #fff;
        text-decoration: none;
        white-space: nowrap;
        transition: .2s;
    }

    .sp-btn:hover {
        background: #edf3ff;
        color: #244fae;
    }

    .sp-btn-primary {
        color: #fff;
        background: #3974d5;
        border-color: #3974d5;
    }

    .sp-btn-primary:hover {
        background: #285fbd;
        color: #fff;
    }

    .sp-history-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 17px 18px;
    }

    .sp-history-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sp-history-title h3 {
        font-size: 15px;
        font-weight: 750;
        margin: 0 0 4px;
        color: #2b3d64;
    }

    .sp-filter {
        min-width: 180px;
        max-width: 220px;
        border: 1px solid #dce4f0;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 12px;
        color: #435575;
        background: #fff;
    }

    .sp-table-wrap {
        padding: 0 16px 18px;
        overflow-x: auto;
    }

    .sp-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 12px;
    }

    .sp-table thead th {
        padding: 13px 12px;
        background: #f0f5fd;
        color: #344667;
        font-weight: 700;
        white-space: nowrap;
    }

    .sp-table thead th:first-child {
        border-radius: 8px 0 0 0;
    }

    .sp-table thead th:last-child {
        border-radius: 0 8px 0 0;
    }

    .sp-table tbody td {
        padding: 16px 12px;
        border-bottom: 1px solid #edf0f5;
        vertical-align: middle;
        color: #344461;
        line-height: 1.65;
    }

    .sp-number {
        font-weight: 700;
        color: #293b60;
    }

    .sp-muted {
        display: block;
        font-size: 10px;
        color: #8490a7;
    }

    .sp-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 650;
        white-space: nowrap;
    }

    .sp-status.selesai {
        color: #16845e;
        background: #dff5ed;
    }

    .sp-status.terjadwal {
        color: #3267c7;
        background: #e6eeff;
    }

    .sp-status.dalam_proses {
        color: #bc801b;
        background: #fff1d8;
    }

    .sp-status.dibatalkan {
        color: #c44f5a;
        background: #ffeaed;
    }

    .sp-status.lainnya {
        color: #65718a;
        background: #edf0f5;
    }

    .sp-about {
        display: flex;
        gap: 13px;
        align-items: flex-start;
        margin: 0 16px 16px;
        padding: 16px 18px;
        border-radius: 9px;
        background: linear-gradient(110deg, #edf4ff, #f1f7ff);
    }

    .sp-about h4 {
        font-size: 13px;
        font-weight: 750;
        margin: 2px 0 5px;
        color: #2d416b;
    }

    .sp-about p {
        font-size: 12px;
        color: #62728f;
        line-height: 1.8;
        margin: 0;
    }

    .sp-empty {
        min-height: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 25px 15px;
        text-align: center;
        color: #7b879d;
        font-size: 12px;
    }

    .sp-empty strong {
        color: #354667;
        font-size: 13px;
    }

    @media (max-width: 700px) {
        .sp-page {
            padding: 12px 4px 20px;
        }

        .sp-heading h1 {
            font-size: 23px;
        }

        .sp-next-panel {
            grid-template-columns: 78px minmax(0, 1fr);
            gap: 10px;
            padding: 10px;
        }

        .sp-date-box {
            min-height: 100px;
        }

        .sp-date-box .number {
            font-size: 27px;
        }

        .sp-next-info {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .sp-next-info .sp-btn {
            justify-self: start;
        }

        .sp-history-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .sp-filter {
            max-width: none;
            width: 100%;
        }

        .sp-table {
            min-width: 850px;
        }

        .sp-about {
            margin: 0 10px 10px;
            padding: 13px;
        }
    }

    /* =========================================
       TAMBAHAN: MODE GELAP SUPERVISI SIGURU
       Mengikuti class body.dark-mode Metronic
       ========================================= */

    body.dark-mode .sp-page {
        color: #f5f8fa;
    }

    body.dark-mode .sp-page .sp-heading h1,
    body.dark-mode .sp-page .sp-card-header h3,
    body.dark-mode .sp-page .sp-history-title h3,
    body.dark-mode .sp-page .sp-about h4,
    body.dark-mode .sp-page .sp-empty strong {
        color: #f5f8fa !important;
    }

    body.dark-mode .sp-page .sp-subtitle,
    body.dark-mode .sp-page .sp-info-label,
    body.dark-mode .sp-page .sp-muted,
    body.dark-mode .sp-page .sp-empty,
    body.dark-mode .sp-page .sp-about p {
        color: #a1a5b7 !important;
    }

    body.dark-mode .sp-page .sp-card,
    body.dark-mode .sp-page .sp-card-header,
    body.dark-mode .sp-page .sp-table tbody td {
        background: #1e1e2d !important;
        color: #f5f8fa !important;
        border-color: #323248 !important;
    }

    body.dark-mode .sp-page .sp-next-panel,
    body.dark-mode .sp-page .sp-about {
        background: #252536 !important;
    }

    body.dark-mode .sp-page .sp-date-box,
    body.dark-mode .sp-page .sp-small-icon,
    body.dark-mode .sp-page .sp-icon {
        background: #323248 !important;
        color: #8db5ff !important;
    }

    body.dark-mode .sp-page .sp-info-value,
    body.dark-mode .sp-page .sp-info-symbol,
    body.dark-mode .sp-page .sp-number {
        color: #f5f8fa !important;
    }

    body.dark-mode .sp-page .sp-table thead th {
        background: #252536 !important;
        color: #f5f8fa !important;
        border-color: #323248 !important;
    }

    body.dark-mode .sp-page .sp-filter {
        background: #1e1e2d !important;
        color: #f5f8fa !important;
        border-color: #474761 !important;
    }

    body.dark-mode .sp-page .sp-btn:not(.sp-btn-primary) {
        background: #252536 !important;
        color: #8db5ff !important;
        border-color: #474761 !important;
    }

    body.dark-mode .sp-page .sp-btn:not(.sp-btn-primary):hover {
        background: #323248 !important;
    }

    body.dark-mode .sp-page .sp-status.selesai {
        background: #153e35 !important;
        color: #65d6ad !important;
    }

    body.dark-mode .sp-page .sp-status.terjadwal {
        background: #202f50 !important;
        color: #8db5ff !important;
    }

    body.dark-mode .sp-page .sp-status.dalam_proses {
        background: #493719 !important;
        color: #ffd477 !important;
    }

    body.dark-mode .sp-page .sp-status.dibatalkan {
        background: #49272d !important;
        color: #ff9aa5 !important;
    }

    body.dark-mode .sp-page .sp-status.lainnya {
        background: #323248 !important;
        color: #c3c6d5 !important;
    }
</style>

@php
    $hariIndonesia = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];

    $bulanIndonesia = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'Mei',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Agu',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Des',
    ];
@endphp

<div class="sp-page">

    {{-- Header halaman --}}
    <div class="sp-heading">
        <div class="sp-icon">▦</div>
        <div>
            <h1>Supervisi</h1>
            <p class="sp-subtitle">
                Lihat jadwal supervisi Anda serta riwayat supervisi yang pernah dilakukan.
            </p>
        </div>
    </div>

    {{-- Jadwal supervisi berikutnya --}}
    <div class="sp-top-grid">
        <div class="sp-card">
            <div class="sp-card-header">
                <span class="sp-small-icon">▣</span>
                <h3>Jadwal Supervisi Berikutnya</h3>
            </div>

            <div class="sp-next-body">
                @if ($jadwalBerikutnya)
                    <div class="sp-next-panel">
                        <div class="sp-date-box">
                            <span class="day">
                                {{ $hariIndonesia[$jadwalBerikutnya->tanggal->format('l')] ?? '' }}
                            </span>

                            <span class="number">
                                {{ $jadwalBerikutnya->tanggal->format('d') }}
                            </span>

                            <span class="month">
                                {{ $bulanIndonesia[(int) $jadwalBerikutnya->tanggal->format('n')] ?? '' }}
                                {{ $jadwalBerikutnya->tanggal->format('Y') }}
                            </span>
                        </div>

                        <div class="sp-next-info">
                            <div class="sp-info-list">

                                <div class="sp-info-item">
                                    <span class="sp-info-symbol">♙</span>
                                    <div>
                                        <span class="sp-info-label">Observer</span>
                                        <span class="sp-info-value">
                                            {{ $jadwalBerikutnya->nama_supervisor ?: '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="sp-info-item">
                                    <span class="sp-info-symbol">▤</span>
                                    <div>
                                        <span class="sp-info-label">Mata Pelajaran</span>
                                        <span class="sp-info-value">
                                            {{ $jadwalBerikutnya->mata_pelajaran ?: '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="sp-info-item">
                                    <span class="sp-info-symbol">☷</span>
                                    <div>
                                        <span class="sp-info-label">Keterangan</span>
                                        <span class="sp-info-value">
                                            {{ $jadwalBerikutnya->keterangan ?: 'Belum ada keterangan.' }}
                                        </span>
                                    </div>
                                </div>

                            </div>

                            <a href="{{ route('guru.supervisi.detail', $jadwalBerikutnya->id) }}"
                               class="sp-btn sp-btn-primary">
                                ▣ Lihat Detail
                            </a>
                        </div>
                    </div>
                @else
                    <div class="sp-empty">
                        <span class="sp-small-icon">▣</span>
                        <strong>Belum Ada Jadwal Mendatang</strong>
                        <span>
                            Jadwal supervisi berikutnya akan ditampilkan di sini setelah tersedia.
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Riwayat supervisi --}}
    <div class="sp-card">

        <div class="sp-history-header">
            <div class="sp-history-title">
                <span class="sp-small-icon">↻</span>

                <div>
                    <h3>Riwayat Supervisi</h3>
                    <p class="sp-subtitle">
                        Daftar riwayat supervisi yang pernah Anda ikuti.
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('guru.supervisi') }}">
                <select name="semester"
                        class="sp-filter"
                        onchange="this.form.submit()">
                    <option value="">Semua Semester</option>

                    @foreach ($semesters as $semester)
                        <option value="{{ $semester }}"
                            @selected(request('semester') == $semester)>
                            {{ $semester }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="sp-table-wrap">
            <table class="sp-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Mata Pelajaran</th>
                        <th>Observer</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($supervisis as $index => $item)
                        @php
                            $status = $item->status ?? '';

                            $statusClass = in_array(
                                $status,
                                ['selesai', 'terjadwal', 'dalam_proses', 'dibatalkan'],
                                true
                            ) ? $status : 'lainnya';
                        @endphp

                        <tr>
                            <td class="sp-number">{{ $index + 1 }}</td>

                            <td>
                                <strong>
                                    {{ $item->tanggal
                                        ? $item->tanggal->format('d M Y')
                                        : '-' }}
                                </strong>

                                <span class="sp-muted">
                                    {{ $item->semester ?: 'Semester belum ditentukan' }}
                                </span>
                            </td>

                            <td>{{ $item->mata_pelajaran ?: '-' }}</td>

                            <td>{{ $item->nama_supervisor ?: '-' }}</td>

                            <td style="min-width: 180px; max-width: 270px;">
                                {{ $item->keterangan ?: '-' }}
                            </td>

                            <td>
                                <span class="sp-status {{ $statusClass }}">
                                    {{ $statusLabels[$status] ?? 'Belum ditentukan' }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('guru.supervisi.detail', $item->id) }}"
                                   class="sp-btn">
                                    ◉ Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="sp-empty">
                                    <strong>Belum Ada Riwayat Supervisi</strong>
                                    <span>
                                        Riwayat supervisi akan muncul setelah data tersedia.
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Informasi supervisi --}}
        <div class="sp-about">
            <span class="sp-small-icon">ⓘ</span>

            <div>
                <h4>Tentang Supervisi</h4>
                <p>
                    Supervisi pembelajaran dilakukan secara berkala oleh tim supervisi
                    untuk memantau dan meningkatkan kualitas pembelajaran di kelas.
                    <br>
                    Jadwal supervisi dapat berubah sewaktu-waktu sesuai dengan kebijakan sekolah.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
