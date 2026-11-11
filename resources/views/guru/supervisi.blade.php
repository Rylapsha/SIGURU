@extends('layouts.app')

@section('page_title', 'Supervisi')

@section('content')

<div class="container-fluid">

    @php
    use Carbon\Carbon;

    $namaHari = [
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu',
    'Sunday' => 'Minggu',
    ];

    $namaBulan = [
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

    $tanggalJadwal = $jadwalBerikutnya
    ? Carbon::parse($jadwalBerikutnya->tanggal)
    : null;

    $statusLabels = $statusLabels ?? [
    'terjadwal' => 'Terjadwal',
    'berlangsung' => 'Berlangsung',
    'selesai' => 'Selesai',
    'dibatalkan' => 'Dibatalkan',
    ];

    $statusBadgeClasses = [
    'terjadwal' => 'badge-light-primary',
    'berlangsung' => 'badge-light-warning',
    'selesai' => 'badge-light-success',
    'dibatalkan' => 'badge-light-danger',
    ];
    @endphp

    {{-- Notifikasi --}}
    @if (session('success'))
    <div class="alert alert-success d-flex align-items-center p-5 mb-5">
        <i class="bi bi-check-circle fs-2x me-4"></i>
        <div class="fw-semibold">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger d-flex align-items-center p-5 mb-5">
        <i class="bi bi-exclamation-triangle fs-2x me-4"></i>
        <div class="fw-semibold">
            {{ session('error') }}
        </div>
    </div>
    @endif

    <div class="d-flex align-items-center gap-4 mb-6 mt-10">

        <div>
            <h3 class="fw-bold text-gray-800 mb-1">
                Jadwal Supervisi
            </h3>
        </div>
    </div>

    <div class="card dashboard-panel mb-7">

        <div class="card-body pt-5">

            @if ($jadwalBerikutnya && $tanggalJadwal)

            <div class="row g-5 align-items-center">

                {{-- TANGGAL --}}
                <div class="col-md-4 col-lg-3">
                    <div class="bg-light-primary rounded p-5 text-center h-100">
                        <div class="text-primary fw-semibold mb-2">
                            {{ $namaHari[$tanggalJadwal->format('l')] ?? $tanggalJadwal->format('l') }}
                        </div>

                        <div class="text-primary fw-bold fs-2x">
                            {{ $tanggalJadwal->format('d') }}
                        </div>

                        <div class="text-primary fw-semibold">
                            {{ $namaBulan[$tanggalJadwal->month] ?? $tanggalJadwal->format('M') }}
                            {{ $tanggalJadwal->year }}
                        </div>
                    </div>
                </div>

                {{-- INFORMASI JADWAL --}}
                <div class="col-md-8 col-lg-9">

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                        <h4 class="fw-bold text-gray-800 mb-0">
                            Informasi supervisi
                        </h4>

                        @php
                        $statusJadwal = strtolower(
                        $jadwalBerikutnya->status ?? 'terjadwal'
                        );
                        @endphp

                        <span class="badge {{ $statusBadgeClasses[$statusJadwal] ?? 'badge-light-secondary' }} fs-7">
                            {{ $statusLabels[$statusJadwal] ?? ucfirst($statusJadwal) }}
                        </span>
                    </div>

                    <div class="row g-4">

                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-3">
                                <i class="bi bi-book text-primary fs-3 mt-1"></i>

                                <div>
                                    <div class="text-muted fs-7 mb-1">
                                        Mata Pelajaran
                                    </div>

                                    <div class="fw-semibold text-gray-800">
                                        {{ $jadwalBerikutnya->mata_pelajaran ?? 'Belum ditentukan' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-3">
                                <i class="bi bi-clock text-primary fs-3 mt-1"></i>

                                <div>
                                    <div class="text-muted fs-7 mb-1">
                                        Waktu Pelaksanaan
                                    </div>

                                    <div class="fw-semibold text-gray-800">
                                        @php
                                        $jamValue = trim((string) ($jadwalBerikutnya->jam ?? ''));

                                        if ($jamValue === '') {
                                        $jamDisplay = 'Belum ditentukan';
                                        } else {
                                        $jamDisplay = preg_match('/\b(?:WIB|WITA|WIT)\b/i', $jamValue)
                                        ? $jamValue
                                        : \Carbon\Carbon::parse($jamValue)->format('H:i') . ' WIB';
                                        }
                                        @endphp
                                        {{ $jamDisplay }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-3">
                                <i class="bi bi-person-badge text-primary fs-3 mt-1"></i>

                                <div>
                                    <div class="text-muted fs-7 mb-1">
                                        Nama Supervisor
                                    </div>

                                    <div class="fw-semibold text-gray-800">
                                        {{ $jadwalBerikutnya->nama_supervisor ?: 'Belum ditentukan' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-3">
                                <i class="bi bi-calendar3 text-primary fs-3 mt-1"></i>

                                <div>
                                    <div class="text-muted fs-7 mb-1">
                                        Semester
                                    </div>

                                    <div class="fw-semibold text-gray-800">
                                        {{ $jadwalBerikutnya->semester ?: '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (!empty($jadwalBerikutnya->keterangan))
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-3">
                                <i class="bi bi-info-circle text-primary fs-3 mt-1"></i>

                                <div>
                                    <div class="text-muted fs-7 mb-1">
                                        Keterangan
                                    </div>

                                    <div class="fw-semibold text-gray-800">
                                        {{ $jadwalBerikutnya->keterangan }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>

                    <div class="mt-5 d-flex justify-content-start">
                        <a href="{{ route('guru.supervisi.surat', $jadwalBerikutnya->id) }}"
                            class="btn btn-primary d-flex align-items-center justify-content-center gap-2 px-4 py-3 w-100 shadow-sm border-0"
                            style="max-width: 280px; border-radius: 12px;"
                            target="_blank"
                            rel="noopener noreferrer">
                            <i class="bi bi-file-earmark-pdf-fill fs-2"></i>
                            <span class="fw-semibold">Surat Pemberitahuan</span>
                        </a>
                    </div>

                </div>
            </div>

            @else

            <div class="text-center py-10">
                <div class="symbol symbol-60px mx-auto mb-5">
                    <i class="bi bi-calendar-x fs-3x text-muted mb-4 d-block"></i>

                </div>

                <p class="text-muted mb-0">
                    Belum Ada Jadwal Supervisi
                </p>
            </div>

            @endif

        </div>
    </div>

    {{-- RIWAYAT SUPERVISI --}}
    <div class="d-flex align-items-center gap-4 mb-7 mt-10">

        <div>
            <h3 class="fw-bold text-gray-800 mb-1">
                Riwayat Supervisi
            </h3>
        </div>
    </div>
    <div class="card dashboard-panel overflow-hidden">


        <div class="card-body pt-5">

            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-300 align-middle gy-5 guru-supervisi-table">

                    <thead>
                        <tr class="fw-bold text-uppercase fs-7">
                            <th class="min-w-150px">Tanggal</th>
                            <th class="min-w-150px">Mata Pelajaran</th>
                            <th class="min-w-150px">Supervisor</th>
                            <th class="min-w-120px">Semester</th>
                            <th class="min-w-120px">Status</th>
                            <th class="text-end min-w-100px">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($supervisis as $item)

                        @php
                        $tanggalRiwayat = $item->tanggal
                        ? \Carbon\Carbon::parse($item->tanggal)
                        : null;

                        $statusRiwayat = strtolower($item->status ?? '');

                        $labelStatus = $statusLabels[$statusRiwayat]
                        ?? ucfirst($statusRiwayat ?: 'Belum ditentukan');

                        $badgeStatus = $statusBadgeClasses[$statusRiwayat]
                        ?? 'badge-light-secondary';
                        @endphp

                        <tr>

                            {{-- TANGGAL --}}
                            <td>
                                @if ($tanggalRiwayat)
                                <div class="fw-bold text-gray-800">
                                    {{ $tanggalRiwayat->format('d') }}
                                    {{ $namaBulan[$tanggalRiwayat->month] ?? $tanggalRiwayat->format('M') }}
                                    {{ $tanggalRiwayat->year }}
                                </div>

                                <div class="text-muted fs-7">
                                    {{ $namaHari[$tanggalRiwayat->format('l')] ?? $tanggalRiwayat->format('l') }}
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- MATA PELAJARAN --}}
                            <td>
                                <span class="fw-semibold text-gray-800">
                                    {{ $item->mata_pelajaran ?: '-' }}
                                </span>
                            </td>

                            {{-- SUPERVISOR --}}
                            <td>
                                <span class="text-gray-700">
                                    {{ $item->nama_supervisor ?: '-' }}
                                </span>
                            </td>

                            {{-- SEMESTER --}}
                            <td>
                                <span class="text-gray-700">
                                    {{ $item->semester ?: '-' }}
                                </span>
                            </td>

                            {{-- STATUS --}}
                            <td>
                                <span class="badge {{ $badgeStatus }}">
                                    {{ $labelStatus }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <a href="{{ route('guru.supervisi.detail', $item->id) }}"
                                        class="btn btn-sm btn-light-primary d-inline-flex align-items-center gap-2"
                                        title="Lihat Detail"
                                        aria-label="Lihat Detail">
                                        <i class="bi bi-eye fs-2"></i>
                                        <span class="fw-semibold">Detail</span>
                                    </a>

                                    @if (strtolower((string) $item->status) === 'selesai' && (!empty($item->feedback_admin) || !empty($item->tindak_lanjut)))
                                    <a href="{{ route('guru.supervisi.feedback', $item->id) }}"
                                        class="btn btn-sm btn-light-success d-inline-flex align-items-center gap-2"
                                        title="Lihat Feedback"
                                        aria-label="Lihat Feedback">
                                        <i class="bi bi-chat-dots fs-2"></i>
                                        <span class="fw-semibold">Feedback</span>
                                    </a>
                                    @endif
                                </div>
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="6" class="text-center py-10">
                                <i class="bi bi-inbox fs-3x text-muted mb-4 d-block"></i>

                                <div class="text-muted fs-7">
                                    Belum Ada Riwayat Supervisi
                                </div>
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

            @if (method_exists($supervisis, 'links'))
            <div class="mt-5">
                {{ $supervisis->links() }}
            </div>
            @endif

        </div>
    </div>

</div>
@endsection

@section('scripts')
@endsection