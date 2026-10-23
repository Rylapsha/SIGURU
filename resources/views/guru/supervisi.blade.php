
@extends('layouts.app')

@section('content')

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
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
    ];

    $tanggalJadwal = $jadwalBerikutnya
        ? \Carbon\Carbon::parse($jadwalBerikutnya->tanggal)
        : null;
@endphp

<style>
    .sp-page {
        --sp-text: #181c32;
        --sp-muted: #7e8299;
        --sp-border: #eff2f5;
        --sp-card: #ffffff;
        --sp-soft: #f5f8fa;
        --sp-primary: #3699ff;
        --sp-primary-soft: #e1f0ff;

        max-width: 1600px;
        margin: 0 auto;
        padding: 8px 0 28px;
        color: var(--sp-text);
    }

    .sp-page, .sp-page * { box-sizing: border-box; }

    .sp-heading {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .sp-icon, .sp-small-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 10px;
        background: var(--sp-primary-soft);
        color: var(--sp-primary);
    }

    .sp-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        font-size: 24px;
    }

    .sp-small-icon {
        width: 38px;
        height: 38px;
        font-size: 19px;
    }

    .sp-heading h1 {
        margin: 0 0 5px;
        color: var(--sp-text);
        font-size: 24px;
        font-weight: 700;
        letter-spacing: -.5px;
    }

    .sp-subtitle {
        margin: 0;
        color: var(--sp-muted);
        font-size: 12px;
        line-height: 1.7;
    }

    .sp-card {
        min-width: 0;
        overflow: hidden;
        background: var(--sp-card);
        border: 1px solid var(--sp-border);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(15,23,42,.025);
    }

    .sp-top-grid {
        display: grid;
        grid-template-columns: minmax(0,1fr);
        gap: 20px;
        margin-bottom: 22px;
    }

    .sp-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 68px;
        padding: 16px 22px;
        border-bottom: 1px solid var(--sp-border);
    }

    .sp-card-header h3, .sp-history-title h3 {
        margin: 0;
        color: var(--sp-text);
        font-size: 14px;
        font-weight: 700;
    }

    .sp-next-body { padding: 20px; }

    .sp-next-panel {
        display: grid;
        grid-template-columns: 112px minmax(0,1fr);
        align-items: center;
        gap: 24px;
        padding: 22px;
        border: 1px solid #e5efff;
        border-radius: 12px;
        background: linear-gradient(115deg,#f3f7ff 0%,#f8faff 100%);
    }

    .sp-date-box {
        min-height: 128px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 14px 8px;
        text-align: center;
        background: #e1edff;
        border-radius: 12px;
        color: #3e6bba;
    }

    .sp-date-box .day, .sp-date-box .month {
        font-size: 12px;
        font-weight: 600;
    }

    .sp-date-box .number {
        margin: 3px 0;
        color: #2d5da8;
        font-size: 34px;
        font-weight: 800;
        line-height: 1.2;
    }

    .sp-next-info {
        display: grid;
        grid-template-columns: minmax(0,1fr) auto;
        align-items: center;
        gap: 24px;
        min-width: 0;
    }

    .sp-info-list { display: grid; gap: 18px; min-width: 0; }

    .sp-info-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .sp-info-symbol {
        width: 24px;
        flex-shrink: 0;
        color: #6993d8;
        font-size: 19px;
        text-align: center;
    }

    .sp-info-label {
        display: block;
        margin-bottom: 4px;
        color: var(--sp-muted);
        font-size: 11px;
        font-weight: 500;
    }

    .sp-info-value {
        display: block;
        color: var(--sp-text);
        font-size: 13px;
        font-weight: 600;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }

    .sp-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 9px 13px;
        border: 1px solid #dce4f0;
        border-radius: 8px;
        background: #fff;
        color: #4b658b;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: background .2s,border-color .2s,color .2s;
    }

    .sp-btn:hover {
        border-color: #b8d4ff;
        background: #f1f6ff;
        color: #2463b9;
    }

    .sp-btn-primary {
        border-color: #3699ff;
        background: #3699ff;
        color: #fff;
    }

    .sp-btn-primary:hover {
        border-color: #187de5;
        background: #187de5;
        color: #fff;
    }

    .sp-comment {
        min-width: 190px;
        max-width: 300px;
        padding: 12px 14px;
        border: 1px solid #e4edfc;
        border-radius: 10px;
        background: #f5f8ff;
        overflow-wrap: anywhere;
    }

    .sp-comment-title {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 7px;
        color: #3e73ce;
        font-size: 11px;
        font-weight: 700;
    }

    .sp-comment p {
        margin: 0;
        color: #465775;
        font-size: 12px;
        line-height: 1.75;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .sp-no-comment {
        display: inline-flex;
        align-items: center;
        padding: 7px 10px;
        border-radius: 7px;
        background: var(--sp-soft);
        color: var(--sp-muted);
        font-size: 10px;
        white-space: nowrap;
    }

    .sp-history-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 20px 22px;
        border-bottom: 1px solid var(--sp-border);
    }

    .sp-history-title {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
    }

    .sp-history-title h3 { margin-bottom: 5px; font-size: 15px; }

    .sp-filter {
        min-width: 190px;
        max-width: 240px;
        min-height: 40px;
        padding: 9px 34px 9px 12px;
        border: 1px solid #dce2ec;
        border-radius: 8px;
        outline: none;
        background-color: #fff;
        color: #3f4254;
        font-family: inherit;
        font-size: 12px;
        transition: border-color .2s,box-shadow .2s;
    }

    .sp-filter:focus {
        border-color: #3699ff;
        box-shadow: 0 0 0 3px rgba(54,153,255,.12);
    }

    .sp-table-wrap {
        padding: 18px 22px 22px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .sp-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 12px;
    }

    .sp-table thead th {
        padding: 14px 13px;
        border-bottom: 1px solid #e6ebf2;
        background: #f5f8fa;
        color: #5e6278;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .sp-table thead th:first-child { border-radius: 8px 0 0 8px; }
    .sp-table thead th:last-child { border-radius: 0 8px 8px 0; }

    .sp-table tbody td {
        padding: 16px 13px;
        border-bottom: 1px solid #eff2f5;
        color: #5e6278;
        line-height: 1.7;
        vertical-align: middle;
    }

    .sp-table tbody tr:last-child td { border-bottom: 0; }
    .sp-table tbody tr:hover td { background: #fafcff; }

    .sp-number, .sp-table strong {
        color: var(--sp-text);
        font-weight: 600;
    }

    .sp-muted {
        display: block;
        margin-top: 3px;
        color: var(--sp-muted);
        font-size: 10px;
    }

    .sp-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .sp-status.selesai { background: #e8fff3; color: #17a673; }
    .sp-status.terjadwal { background: #e8f3ff; color: #3699ff; }
    .sp-status.dalam_proses { background: #fff4de; color: #c58b18; }
    .sp-status.dibatalkan { background: #fff0f0; color: #e05260; }
    .sp-status.lainnya { background: #f1f1f6; color: #7e8299; }

    .sp-about {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin: 0 22px 22px;
        padding: 18px;
        border: 1px solid #e1ebff;
        border-radius: 10px;
        background: #f4f8ff;
    }

    .sp-about h4 {
        margin: 2px 0 6px;
        color: #344b78;
        font-size: 13px;
        font-weight: 700;
    }

    .sp-about p {
        margin: 0;
        color: #687a98;
        font-size: 12px;
        line-height: 1.8;
    }

    .sp-empty {
        min-height: 145px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 28px 18px;
        color: var(--sp-muted);
        font-size: 12px;
        text-align: center;
    }

    .sp-empty strong {
        color: var(--sp-text);
        font-size: 13px;
        font-weight: 700;
    }

    .sp-empty .sp-small-icon { margin-bottom: 3px; }

    @media (max-width: 991px) {
        .sp-next-panel {
            grid-template-columns: 95px minmax(0,1fr);
            gap: 18px;
            padding: 18px;
        }

        .sp-next-info {
            grid-template-columns: minmax(0,1fr);
            gap: 18px;
        }

        .sp-next-info > .sp-btn { justify-self: start; }
        .sp-table { min-width: 1100px; }
    }

    @media (max-width: 700px) {
        .sp-page { padding: 4px 0 20px; }
        .sp-heading { gap: 12px; margin-bottom: 18px; }
        .sp-icon { width: 44px; height: 44px; font-size: 21px; }
        .sp-heading h1 { font-size: 21px; }
        .sp-card-header { padding: 14px 15px; }
        .sp-next-body { padding: 12px; }

        .sp-next-panel {
            grid-template-columns: 72px minmax(0,1fr);
            gap: 12px;
            padding: 12px;
        }

        .sp-date-box { min-height: 100px; padding: 10px 4px; }
        .sp-date-box .day, .sp-date-box .month { font-size: 10px; }
        .sp-date-box .number { font-size: 27px; }
        .sp-info-list { gap: 14px; }
        .sp-info-item { gap: 8px; }
        .sp-info-value { font-size: 12px; }

        .sp-history-header {
            align-items: stretch;
            flex-direction: column;
            gap: 15px;
            padding: 16px;
        }

        .sp-filter { width: 100%; max-width: none; }
        .sp-table-wrap { padding: 12px; }
        .sp-table { min-width: 1100px; }
        .sp-about { gap: 10px; margin: 0 12px 12px; padding: 13px; }
        .sp-comment { min-width: 190px; }
    }

    /* Dark mode */
    body.dark-mode .sp-page,
    html[data-bs-theme="dark"] .sp-page {
        --sp-text: #f5f8fa;
        --sp-muted: #a1a5b7;
        --sp-border: #323248;
        --sp-card: #1e1e2d;
        --sp-soft: #252536;
        --sp-primary-soft: #252b43;
    }

    body.dark-mode .sp-card,
    html[data-bs-theme="dark"] .sp-card {
        border-color: #323248;
        background: #1e1e2d;
        box-shadow: none;
    }

    body.dark-mode .sp-heading h1,
    body.dark-mode .sp-card-header h3,
    body.dark-mode .sp-history-title h3,
    body.dark-mode .sp-info-value,
    body.dark-mode .sp-table strong,
    body.dark-mode .sp-number,
    body.dark-mode .sp-empty strong,
    body.dark-mode .sp-about h4,
    html[data-bs-theme="dark"] .sp-heading h1,
    html[data-bs-theme="dark"] .sp-card-header h3,
    html[data-bs-theme="dark"] .sp-history-title h3,
    html[data-bs-theme="dark"] .sp-info-value,
    html[data-bs-theme="dark"] .sp-table strong,
    html[data-bs-theme="dark"] .sp-number,
    html[data-bs-theme="dark"] .sp-empty strong,
    html[data-bs-theme="dark"] .sp-about h4 {
        color: #f5f8fa;
    }

    body.dark-mode .sp-card-header,
    body.dark-mode .sp-history-header,
    body.dark-mode .sp-table tbody td,
    html[data-bs-theme="dark"] .sp-card-header,
    html[data-bs-theme="dark"] .sp-history-header,
    html[data-bs-theme="dark"] .sp-table tbody td {
        border-color: #323248;
    }

    body.dark-mode .sp-next-panel,
    body.dark-mode .sp-about,
    html[data-bs-theme="dark"] .sp-next-panel,
    html[data-bs-theme="dark"] .sp-about {
        border-color: #323248;
        background: #252536;
    }

    body.dark-mode .sp-date-box,
    html[data-bs-theme="dark"] .sp-date-box {
        background: #323248;
        color: #8db5ff;
    }

    body.dark-mode .sp-date-box .number,
    html[data-bs-theme="dark"] .sp-date-box .number { color: #8db5ff; }

    body.dark-mode .sp-small-icon,
    body.dark-mode .sp-icon,
    html[data-bs-theme="dark"] .sp-small-icon,
    html[data-bs-theme="dark"] .sp-icon {
        background: #323248;
        color: #8db5ff;
    }

    body.dark-mode .sp-info-symbol,
    html[data-bs-theme="dark"] .sp-info-symbol { color: #8db5ff; }

    body.dark-mode .sp-table thead th,
    html[data-bs-theme="dark"] .sp-table thead th {
        border-color: #323248;
        background: #252536;
        color: #c3c6d5;
    }

    body.dark-mode .sp-table tbody td,
    html[data-bs-theme="dark"] .sp-table tbody td { color: #c3c6d5; }

    body.dark-mode .sp-table tbody tr:hover td,
    html[data-bs-theme="dark"] .sp-table tbody tr:hover td { background: #252536; }

    body.dark-mode .sp-filter,
    html[data-bs-theme="dark"] .sp-filter {
        border-color: #474761;
        background-color: #1e1e2d;
        color: #f5f8fa;
    }

    body.dark-mode .sp-filter option,
    html[data-bs-theme="dark"] .sp-filter option {
        background: #1e1e2d;
        color: #f5f8fa;
    }

    body.dark-mode .sp-btn:not(.sp-btn-primary),
    html[data-bs-theme="dark"] .sp-btn:not(.sp-btn-primary) {
        border-color: #474761;
        background: #252536;
        color: #8db5ff;
    }

    body.dark-mode .sp-btn:not(.sp-btn-primary):hover,
    html[data-bs-theme="dark"] .sp-btn:not(.sp-btn-primary):hover {
        border-color: #5b78ad;
        background: #323248;
    }

    body.dark-mode .sp-comment,
    html[data-bs-theme="dark"] .sp-comment {
        border-color: #323248;
        background: #252536;
    }

    body.dark-mode .sp-comment-title,
    html[data-bs-theme="dark"] .sp-comment-title { color: #8db5ff; }

    body.dark-mode .sp-comment p,
    html[data-bs-theme="dark"] .sp-comment p { color: #e4e6ef; }

    body.dark-mode .sp-no-comment,
    html[data-bs-theme="dark"] .sp-no-comment {
        background: #252536;
        color: #a1a5b7;
    }

    body.dark-mode .sp-status.selesai,
    html[data-bs-theme="dark"] .sp-status.selesai {
        background: #153e35;
        color: #65d6ad;
    }

    body.dark-mode .sp-status.terjadwal,
    html[data-bs-theme="dark"] .sp-status.terjadwal {
        background: #202f50;
        color: #8db5ff;
    }

    body.dark-mode .sp-status.dalam_proses,
    html[data-bs-theme="dark"] .sp-status.dalam_proses {
        background: #493719;
        color: #ffd477;
    }

    body.dark-mode .sp-status.dibatalkan,
    html[data-bs-theme="dark"] .sp-status.dibatalkan {
        background: #49272d;
        color: #ff9aa5;
    }

    body.dark-mode .sp-status.lainnya,
    html[data-bs-theme="dark"] .sp-status.lainnya {
        background: #323248;
        color: #c3c6d5;
    }
</style>

{{-- SWEETALERT2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                confirmButtonText: 'Oke',
                confirmButtonColor: '#3699ff'
            });
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: @json(session('error')),
                confirmButtonText: 'Oke',
                confirmButtonColor: '#3699ff'
            });
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'warning',
                title: 'Periksa Kembali',
                text: @json($errors->first()),
                confirmButtonText: 'Oke',
                confirmButtonColor: '#3699ff'
            });
        });
    </script>
@endif

<div class="sp-page">

    {{-- HEADER --}}
    <div class="sp-heading">
        <div class="sp-icon">
            <i class="ki-outline ki-calendar-8"></i>
        </div>
        <div>
            <h1>Supervisi</h1>
            <p class="sp-subtitle">
                Lihat jadwal supervisi dan riwayat supervisi pembelajaran Anda.
            </p>
        </div>
    </div>

    {{-- JADWAL SUPERVISI BERIKUTNYA --}}
    <div class="sp-top-grid">
        <div class="sp-card">

            <div class="sp-card-header">
                <span class="sp-small-icon">
                    <i class="ki-outline ki-calendar-tick"></i>
                </span>
                <h3>Jadwal Supervisi Berikutnya</h3>
            </div>

            <div class="sp-next-body">
                @if ($jadwalBerikutnya)
                    <div class="sp-next-panel">

                        <div class="sp-date-box">
                            <span class="day">
                                {{ $hariIndonesia[$tanggalJadwal->format('l')] ?? '' }}
                            </span>
                            <span class="number">{{ $tanggalJadwal->format('d') }}</span>
                            <span class="month">
                                {{ $bulanIndonesia[(int) $tanggalJadwal->format('n')] ?? '' }}
                                {{ $tanggalJadwal->format('Y') }}
                            </span>
                        </div>

                        <div class="sp-next-info">
                            <div class="sp-info-list">

                                <div class="sp-info-item">
                                    <span class="sp-info-symbol">
                                        <i class="ki-outline ki-user"></i>
                                    </span>
                                    <div>
                                        <span class="sp-info-label">Observer</span>
                                        <span class="sp-info-value">
                                            {{ $jadwalBerikutnya->nama_supervisor ?: '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="sp-info-item">
                                    <span class="sp-info-symbol">
                                        <i class="ki-outline ki-book-open"></i>
                                    </span>
                                    <div>
                                        <span class="sp-info-label">Mata Pelajaran</span>
                                        <span class="sp-info-value">
                                            {{ $jadwalBerikutnya->mata_pelajaran ?: '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="sp-info-item">
                                    <span class="sp-info-symbol">
                                        <i class="ki-outline ki-information-2"></i>
                                    </span>
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
                                <i class="ki-outline ki-eye"></i>
                                Lihat Detail
                            </a>
                        </div>
                    </div>

                    @if (filled($jadwalBerikutnya->komentar_admin))
                        <div class="sp-comment" style="margin-top:16px;max-width:none;">
                            <span class="sp-comment-title">
                                <i class="ki-outline ki-message-text-2"></i>
                                Komentar Admin
                            </span>
                            <p>{{ $jadwalBerikutnya->komentar_admin }}</p>
                        </div>
                    @endif

                @else
                    <div class="sp-empty">
                        <span class="sp-small-icon">
                            <i class="ki-outline ki-calendar-remove"></i>
                        </span>
                        <strong>Belum Ada Jadwal Mendatang</strong>
                        <span>
                            Jadwal supervisi berikutnya akan ditampilkan di sini setelah tersedia.
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RIWAYAT SUPERVISI --}}
    <div class="sp-card">

        <div class="sp-history-header">
            <div class="sp-history-title">
                <span class="sp-small-icon">
                    <i class="ki-outline ki-time"></i>
                </span>
                <div>
                    <h3>Riwayat Supervisi</h3>
                    <p class="sp-subtitle">
                        Daftar supervisi yang pernah Anda ikuti.
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('guru.supervisi') }}">
                <select name="semester"
                        class="sp-filter"
                        aria-label="Filter semester"
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
                        <th>Komentar Admin</th>

                    </tr>
                </thead>

                <tbody>
                    @forelse ($supervisis as $index => $item)
                        @php
                            $tanggalRiwayat = $item->tanggal
                                ? \Carbon\Carbon::parse($item->tanggal)
                                : null;

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
                                    {{ $tanggalRiwayat ? $tanggalRiwayat->format('d M Y') : '-' }}
                                </strong>
                                <span class="sp-muted">
                                    {{ $item->semester ?: 'Semester belum ditentukan' }}
                                </span>
                            </td>

                            <td>{{ $item->mata_pelajaran ?: '-' }}</td>
                            <td>{{ $item->nama_supervisor ?: '-' }}</td>

                            <td style="min-width:180px;max-width:270px;">
                                {{ $item->keterangan ?: '-' }}
                            </td>

                            <td>
                                <span class="sp-status {{ $statusClass }}">
                                    {{ $statusLabels[$status] ?? 'Belum ditentukan' }}
                                </span>
                            </td>

                            <td>
                                @if (filled($item->komentar_admin))
                                    <div class="sp-comment">
                                        <span class="sp-comment-title">
                                            <i class="ki-outline ki-message-text-2"></i>
                                            Komentar Admin
                                        </span>
                                        <p>{{ $item->komentar_admin }}</p>
                                    </div>
                                @else
                                    <span class="sp-no-comment">Belum ada komentar</span>
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="sp-empty">
                                    <span class="sp-small-icon">
                                        <i class="ki-outline ki-folder-open"></i>
                                    </span>
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
    </div>
</div>
@endsection
