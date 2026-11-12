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
    <div class="card dashboard-panel mt-10">
        <div class="card-header border-0 pt-5 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h3 class="card-title fw-bold text-gray-800 mb-0">
                Riwayat Supervisi
            </h3>

            <form action="{{ route('guru.supervisi') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
                <div class="input-group" style="width: min(320px, 70vw);">
                    <input
                        type="search"
                        name="cari"
                        class="form-control form-control-solid"
                        placeholder="Cari mata pelajaran atau supervisor..."
                        aria-label="Cari mata pelajaran atau supervisor"
                        value="{{ request('cari') }}">
                    <button type="submit" class="btn btn-light-primary" aria-label="Cari riwayat supervisi" title="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                <div class="dropdown">
                    <button
                        type="button"
                        class="btn btn-light-primary btn-sm d-flex align-items-center gap-2"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-label="Filter riwayat supervisi"
                        title="Filter riwayat supervisi">
                        <i class="bi bi-funnel"></i>
                        <span>Filter</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end p-4 shadow-sm" id="guru-supervisi-filter-menu" style="width: 300px; max-width: calc(100vw - 40px);">
                        <div class="fw-bold text-gray-800 mb-3">Filter Riwayat</div>

                        <div class="mb-3">
                            <label for="guru-filter-mata-pelajaran" class="form-label fw-semibold">Mata Pelajaran</label>
                            <select id="guru-filter-mata-pelajaran" name="mata_pelajaran" class="form-select form-select-solid">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach ($daftarMataPelajaran as $mapel)
                                <option value="{{ $mapel }}" @selected(request('mata_pelajaran') === $mapel)>
                                    {{ $mapel }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="guru-filter-semester" class="form-label fw-semibold">Semester</label>
                            <select id="guru-filter-semester" name="semester" class="form-select form-select-solid">
                                <option value="">Semua Semester</option>
                                @foreach ($semesters as $semester)
                                <option value="{{ $semester }}" @selected(request('semester') === $semester)>
                                    {{ $semester }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="guru-supervisi-date-range" class="form-label fw-semibold">Tanggal Pelaksanaan</label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="guru-supervisi-date-range"
                                    class="form-control"
                                    placeholder="Pilih rentang tanggal"
                                    aria-label="Pilih rentang tanggal pelaksanaan"
                                    autocomplete="off"
                                    readonly>
                                <span class="input-group-text"><i class="bi bi-calendar-range"></i></span>
                            </div>
                            <input type="hidden" name="tanggal_mulai" id="guru-supervisi-date-start" value="{{ request('tanggal_mulai') }}">
                            <input type="hidden" name="tanggal_akhir" id="guru-supervisi-date-end" value="{{ request('tanggal_akhir') }}">
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('guru.supervisi') }}" class="btn btn-light btn-sm flex-grow-1">Reset</a>
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Terapkan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body pt-3">

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
                                    @if (request()->filled('cari') || request()->filled('mata_pelajaran') || request()->filled('semester') || request()->filled('tanggal_mulai') || request()->filled('tanggal_akhir'))
                                    Tidak ada riwayat supervisi yang sesuai dengan pencarian atau filter.
                                    @else
                                    Belum Ada Riwayat Supervisi
                                    @endif
                                </div>
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer border-0 bg-transparent pb-5 pt-0">
            @include('partials.table-pagination', ['paginator' => $supervisis])
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!window.jQuery || !window.initSiguruDatePicker || !window.moment) {
            return;
        }

        const $ = window.jQuery;
        const dateRangeInput = $('#guru-supervisi-date-range');
        const startInput = $('#guru-supervisi-date-start');
        const endInput = $('#guru-supervisi-date-end');

        if (!dateRangeInput.length) {
            return;
        }

        const startValue = startInput.val();
        const endValue = endInput.val();
        const pickerOptions = {
            autoUpdateInput: false,
            parentEl: '#guru-supervisi-filter-menu'
        };

        if (startValue && endValue) {
            const startDate = window.moment(startValue, 'YYYY-MM-DD', true);
            const endDate = window.moment(endValue, 'YYYY-MM-DD', true);

            if (startDate.isValid() && endDate.isValid()) {
                pickerOptions.startDate = startDate;
                pickerOptions.endDate = endDate;
                dateRangeInput.val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
            }
        }

        if (!window.initSiguruDatePicker(dateRangeInput, pickerOptions)) {
            return;
        }

        dateRangeInput.on('apply.daterangepicker', function(event, picker) {
            dateRangeInput.val(
                picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY')
            );
            startInput.val(picker.startDate.format('YYYY-MM-DD'));
            endInput.val(picker.endDate.format('YYYY-MM-DD'));
        });

        dateRangeInput.on('cancel.daterangepicker', function() {
            dateRangeInput.val('');
            startInput.val('');
            endInput.val('');
        });
    });
</script>
@endsection
