
@extends('layouts.admin')

@section('title', 'Agenda Kegiatan')
@section('page_title', 'Agenda Kegiatan')

@section('content')
<div class="admin-agenda">

    {{-- NOTIFIKASI --}}
    @if (session('success'))
    <div class="alert alert-success d-flex align-items-center mb-5">
        <i class="bi bi-check-circle-fill me-3 fs-2"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger mb-5">
        <div class="fw-bold mb-2">Periksa kembali input Anda:</div>
        <ul class="mb-0 ps-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- DAFTAR AGENDA --}}
    <section class="agenda-panel">

        {{-- JUDUL DAFTAR AGENDA --}}
        <div class="agenda-heading">
            <h3>Daftar Agenda</h3>
        </div>

        {{-- TOMBOL TAMBAH AGENDA DAN FILTER --}}
        <div class="agenda-toolbar">

            <button
                type="button"
                class="btn btn-light-primary dashboard-action-btn fw-semibold px-5 py-3 fs-6"
                style="min-height: 43px;"
                data-bs-toggle="modal"
                data-bs-target="#tambahAgendaModal">

                <i class="bi bi-plus-lg me-2"></i>
                Tambah Agenda
            </button>

            {{-- FILTER TANGGAL --}}
            <form
                action="{{ route('admin.agenda.index') }}"
                method="GET"
                class="agenda-filter-form">

                <div class="dropdown">
                    <button
                        type="button"
                        class="btn btn-light btn-icon agenda-filter-toggle"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-label="Filter rentang tanggal kegiatan"
                        title="Filter rentang tanggal kegiatan">

                        <i class="bi bi-funnel fs-4"></i>
                    </button>

                    <div
                        class="dropdown-menu dropdown-menu-end p-4 shadow-sm agenda-filter-menu"
                        id="agenda-filter-menu">

                        <div class="fw-bold text-gray-800 mb-3">
                            Filter Agenda
                        </div>

                        <div class="mb-3">
                            <label
                                for="agenda-filter-date-range"
                                class="form-label fw-semibold">
                                Rentang Tanggal Kegiatan
                            </label>

                            <div class="input-group">
                                <input
                                    type="text"
                                    id="agenda-filter-date-range"
                                    class="form-control agenda-date-range"
                                    placeholder="Pilih rentang tanggal"
                                    aria-label="Pilih rentang tanggal kegiatan"
                                    autocomplete="off"
                                    readonly>

                                <span class="input-group-text">
                                    <i class="bi bi-calendar-range"></i>
                                </span>
                            </div>

                            <input
                                type="hidden"
                                name="tanggal_mulai"
                                id="agenda-filter-start"
                                value="{{ request('tanggal_mulai') }}">

                            <input
                                type="hidden"
                                name="tanggal_akhir"
                                id="agenda-filter-end"
                                value="{{ request('tanggal_akhir') }}">
                        </div>

                        <div class="d-flex gap-2">
                            <a
                                href="{{ route('admin.agenda.index') }}"
                                class="btn btn-light btn-sm flex-grow-1">
                                Reset Filter
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary btn-sm flex-grow-1">
                                Terapkan
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        </div>

        {{-- TABEL AGENDA --}}
        <div class="table-responsive">
            <table class="table table-sm table-row-bordered table-row-gray-300 align-middle gy-5">

                <thead>
                    <tr class="fw-bold text-uppercase fs-7">
                        <th class="min-w-140px">Tanggal</th>
                        <th class="min-w-220px">Kegiatan</th>
                        <th class="min-w-120px">Waktu</th>
                        <th class="min-w-150px">Lokasi</th>
                        <th class="min-w-130px">Status</th>
                        <th class="text-end min-w-180px">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($agenda as $item)

                    @php
                    $statusValue = strtolower($item->status ?? 'terjadwal');

                    $statusBadgeClass = match ($statusValue) {
                        'terjadwal' => 'badge-light-primary',
                        'selesai' => 'badge-light-success',
                        'dibatalkan' => 'badge-light-danger',
                        default => 'badge-light-secondary',
                    };
                    @endphp

                    <tr>

                        {{-- TANGGAL --}}
                        <td>
                            <div class="fw-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d M Y') }}
                            </div>
                        </td>

                        {{-- JUDUL KEGIATAN --}}
                        <td>
                            <div class="fw-bold text-gray-800">
                                {{ $item->judul }}
                            </div>

                            @if ($item->keterangan)
                            <div class="text-muted fs-7 mt-1">
                                {{ \Illuminate\Support\Str::limit($item->keterangan, 65) }}
                            </div>
                            @endif
                        </td>

                        {{-- JAM --}}
                        <td>
                            <span class="text-gray-700">
                                {{ $item->jam ? substr((string) $item->jam, 0, 5) . ' WIB' : '-' }}
                            </span>
                        </td>

                        {{-- LOKASI --}}
                        <td>
                            <span class="text-gray-700">
                                {{ $item->lokasi ?: '-' }}
                            </span>
                        </td>

                        {{-- STATUS --}}
                        <td>
                            <span class="badge {{ $statusBadgeClass }}">
                                {{ ucfirst($statusValue) }}
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2 flex-wrap">

                                <button
                                    type="button"
                                    class="btn btn-sm btn-light-primary d-inline-flex align-items-center gap-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editAgendaModal{{ $item->id }}"
                                    title="Edit Agenda">

                                    <i class="bi bi-pencil-square fs-2"></i>
                                    <span class="fw-semibold">Edit</span>
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-light-danger d-inline-flex align-items-center gap-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteAgendaModal{{ $item->id }}"
                                    title="Hapus Agenda">

                                    <i class="bi bi-trash fs-2"></i>
                                    <span class="fw-semibold">Hapus</span>
                                </button>

                            </div>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" class="text-center py-10">
                            <i class="bi bi-calendar2-x fs-3x text-muted mb-4 d-block"></i>

                            <div class="fw-bold text-gray-800">
                                Belum Ada Agenda Kegiatan
                            </div>

                            <div class="text-muted fs-7 mt-2">
                                Agenda kegiatan yang ditambahkan akan ditampilkan di sini.
                            </div>
                        </td>
                    </tr>

                    @endforelse
                </tbody>

            </table>
        </div>

    </section>

    {{-- MODAL EDIT DAN HAPUS --}}
    @foreach ($agenda as $item)

    {{-- MODAL EDIT AGENDA --}}
    <div
        class="modal fade"
        id="editAgendaModal{{ $item->id }}"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <form
                    method="POST"
                    action="{{ route('admin.agenda.update', $item->id) }}">

                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-pencil-square text-primary me-2"></i>
                            Edit Agenda Kegiatan
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>
                    </div>

                    <div class="modal-body row g-3">

                        <div class="col-md-8">
                            <label
                                for="edit-judul-{{ $item->id }}"
                                class="form-label fw-semibold">
                                Judul Kegiatan
                            </label>

                            <input
                                type="text"
                                id="edit-judul-{{ $item->id }}"
                                name="judul"
                                class="form-control"
                                value="{{ old('judul', $item->judul) }}"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label
                                for="edit-status-{{ $item->id }}"
                                class="form-label fw-semibold">
                                Status
                            </label>

                            <select
                                id="edit-status-{{ $item->id }}"
                                name="status"
                                class="form-select"
                                required>

                                <option value="terjadwal" {{ old('status', $item->status) === 'terjadwal' ? 'selected' : '' }}>
                                    Terjadwal
                                </option>

                                <option value="selesai" {{ old('status', $item->status) === 'selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>

                                <option value="dibatalkan" {{ old('status', $item->status) === 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label
                                for="edit-tanggal-{{ $item->id }}"
                                class="form-label fw-semibold">
                                Tanggal
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-calendar3"></i>
                                </span>

                                <input
                                    type="text"
                                    id="edit-tanggal-{{ $item->id }}"
                                    name="tanggal"
                                    class="form-control"
                                    data-date-picker="single"
                                    placeholder="YYYY-MM-DD"
                                    pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
                                    title="Gunakan format YYYY-MM-DD"
                                    autocomplete="off"
                                    value="{{ old('tanggal', $item->tanggal) }}"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label
                                for="edit-jam-{{ $item->id }}"
                                class="form-label fw-semibold">
                                Jam
                            </label>

                            <input
                                type="time"
                                id="edit-jam-{{ $item->id }}"
                                name="jam"
                                class="form-control"
                                value="{{ old('jam', $item->jam ? substr((string) $item->jam, 0, 5) : '') }}"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label
                                for="edit-lokasi-{{ $item->id }}"
                                class="form-label fw-semibold">
                                Lokasi
                            </label>

                            <input
                                type="text"
                                id="edit-lokasi-{{ $item->id }}"
                                name="lokasi"
                                class="form-control"
                                value="{{ old('lokasi', $item->lokasi) }}"
                                placeholder="Ruang 1 / Aula"
                                required>
                        </div>

                        <div class="col-12">
                            <label
                                for="edit-keterangan-{{ $item->id }}"
                                class="form-label fw-semibold">
                                Keterangan
                            </label>

                            <textarea
                                id="edit-keterangan-{{ $item->id }}"
                                name="keterangan"
                                class="form-control"
                                rows="3"
                                placeholder="Tambahkan keterangan kegiatan"
                                required>{{ old('keterangan', $item->keterangan) }}</textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="bi bi-check-lg me-1"></i>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- MODAL HAPUS AGENDA --}}
    <div
        class="modal fade"
        id="deleteAgendaModal{{ $item->id }}"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form
                    method="POST"
                    action="{{ route('admin.agenda.destroy', $item->id) }}">

                    @csrf
                    @method('DELETE')

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold text-danger">
                            <i class="bi bi-trash me-2"></i>
                            Hapus Agenda
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>
                    </div>

                    <div class="modal-body">

                        <p class="text-gray-700 mb-3">
                            Apakah Anda yakin ingin menghapus agenda berikut?
                        </p>

                        <div class="agenda-delete-info">
                            <div class="fw-bold text-gray-800">
                                {{ $item->judul }}
                            </div>

                            <div class="text-muted fs-7 mt-2">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d F Y') }}
                            </div>
                        </div>

                        <div class="text-muted fs-7 mt-3">
                            Agenda yang dihapus tidak dapat dikembalikan.
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button
                            type="submit"
                            class="btn btn-danger">

                            <i class="bi bi-trash me-1"></i>
                            Ya, Hapus
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    @endforeach

    {{-- MODAL TAMBAH AGENDA --}}
    <div
        class="modal fade"
        id="tambahAgendaModal"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <form
                    method="POST"
                    action="{{ route('admin.agenda.store') }}">

                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-calendar-plus text-primary me-2"></i>
                            Tambah Agenda Kegiatan
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>
                    </div>

                    <div class="modal-body row g-3">

                        <div class="col-md-8">
                            <label for="judul" class="form-label fw-semibold">
                                Judul Kegiatan
                            </label>

                            <input
                                type="text"
                                id="judul"
                                name="judul"
                                class="form-control"
                                placeholder="Contoh: Rapat Koordinasi, Evaluasi Pembelajaran"
                                value="{{ old('judul') }}"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label for="status" class="form-label fw-semibold">
                                Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="form-select"
                                required>

                                <option value="terjadwal" {{ old('status', 'terjadwal') === 'terjadwal' ? 'selected' : '' }}>
                                    Terjadwal
                                </option>

                                <option value="selesai" {{ old('status') === 'selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>

                                <option value="dibatalkan" {{ old('status') === 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="tanggal" class="form-label fw-semibold">
                                Tanggal
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-calendar3"></i>
                                </span>

                                <input
                                    type="text"
                                    id="tanggal"
                                    name="tanggal"
                                    class="form-control"
                                    data-date-picker="single"
                                    placeholder="YYYY-MM-DD"
                                    pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
                                    title="Gunakan format YYYY-MM-DD"
                                    autocomplete="off"
                                    value="{{ old('tanggal') }}"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="jam" class="form-label fw-semibold">
                                Jam
                            </label>

                            <input
                                type="time"
                                id="jam"
                                name="jam"
                                class="form-control"
                                value="{{ old('jam') }}"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label for="lokasi" class="form-label fw-semibold">
                                Lokasi
                            </label>

                            <input
                                type="text"
                                id="lokasi"
                                name="lokasi"
                                class="form-control"
                                placeholder="Aula / ruang guru"
                                value="{{ old('lokasi') }}"
                                required>
                        </div>

                        <div class="col-12">
                            <label for="keterangan" class="form-label fw-semibold">
                                Keterangan
                            </label>

                            <textarea
                                id="keterangan"
                                name="keterangan"
                                class="form-control"
                                rows="3"
                                placeholder="Tambahkan keterangan kegiatan"
                                required>{{ old('keterangan') }}</textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="bi bi-plus-lg me-1"></i>
                            Tambahkan Agenda
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</div>
@endsection

{{-- STYLE HALAMAN AGENDA --}}
@push('styles')
<style>
    .admin-agenda {
        --agenda-bg: #f4f9ff;
        --agenda-border: #dfeeff;
        --agenda-text: #1d3557;
        --agenda-muted: #59749a;

        width: 100%;
        max-width: none;
        padding: 20px 10px;
        color: var(--agenda-text);
        font-family: Inter, sans-serif;
        box-sizing: border-box;
        flex: 1 0 auto;
    }

    #kt_page_content {
        display: flex;
        flex-direction: column;
    }

    #kt_page_content > .container-fluid {
        display: flex;
        flex: 1 0 auto;
        flex-direction: column;
    }

    .admin-agenda * {
        box-sizing: border-box;
    }

    /* PANEL TABEL */
    .admin-agenda .agenda-panel {
        display: block;
        width: 100%;
        max-width: none;
        margin-bottom: 26px;
        padding: 32px;
        background: #fff;
        border: 1px solid var(--admin-border, #CFDCEB);
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(32, 96, 196, 0.06);
    }

    /* JUDUL DAFTAR AGENDA */
    .admin-agenda .agenda-heading {
        width: 100%;
        margin-bottom: 20px;
    }

    .admin-agenda .agenda-heading h3 {
        margin: 0;
        color: var(--agenda-text);
        font-size: 20px;
        font-weight: 700;
    }

    /* TOOLBAR TAMBAH AGENDA DAN FILTER */
    .admin-agenda .agenda-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        width: 100%;
        margin-bottom: 24px;
    }

    .admin-agenda .agenda-filter-form {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin: 0;
    }

    .admin-agenda .agenda-filter-menu {
        width: 340px;
        max-width: calc(100vw - 32px);
    }

    .admin-agenda .agenda-filter-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 43px;
        height: 43px;
        padding: 0;
    }

    .admin-agenda .agenda-date-range {
        cursor: pointer;
        background-color: #fff;
    }

    /* TABEL */
    .admin-agenda .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .admin-agenda .table {
        width: 100%;
        min-width: 1050px;
        margin-bottom: 0;
        font-family: Poppins, sans-serif;
    }

    .admin-agenda .table thead th {
        color: #1f3c57;
        font-size: inherit;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .02em;
        white-space: nowrap;
    }

    .admin-agenda .table tbody td {
        color: var(--agenda-text);
        vertical-align: middle;
    }

    /* TOMBOL */
    .admin-agenda .btn:not(.dashboard-action-btn) {
        padding: 11px 16px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 8px;
    }

    .admin-agenda .btn i {
        line-height: 1;
        font-size: 16px;
    }

    /* MODAL */
    .admin-agenda .modal-content {
        overflow: hidden;
        border: 1px solid var(--agenda-border);
        border-radius: 16px;
    }

    .admin-agenda .modal-header {
        padding: 26px 30px;
        border-bottom: 1px solid #edf2f8;
    }

    .admin-agenda .modal-header .modal-title {
        color: var(--agenda-text);
        font-size: 20px;
    }

    .admin-agenda .modal-body {
        padding: 30px;
    }

    .admin-agenda .modal-body .form-label {
        color: var(--agenda-text);
        font-size: 15px;
        margin-bottom: 10px;
    }

    .admin-agenda .modal-body .form-control,
    .admin-agenda .modal-body .form-select {
        min-height: 50px;
        padding: 13px 15px;
        color: var(--agenda-text);
        background-color: #fff;
        border: 1px solid var(--agenda-border);
        border-radius: 9px;
        font-size: 15px;
    }

    .admin-agenda .modal-body textarea.form-control {
        min-height: auto;
    }

    .admin-agenda .modal-body .form-control:focus,
    .admin-agenda .modal-body .form-select:focus {
        border-color: #438cf5;
        box-shadow: 0 0 0 3px rgba(67, 140, 245, .1);
    }

    .admin-agenda .modal-footer {
        padding: 22px 30px;
        border-top: 1px solid #edf2f8;
    }

    .admin-agenda .agenda-delete-info {
        padding: 20px;
        background: #f4f9ff;
        border: 1px solid var(--agenda-border);
        border-radius: 10px;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .admin-agenda {
            padding: 16px 0;
        }

        .admin-agenda .agenda-panel {
            padding: 20px;
        }

        .admin-agenda .agenda-toolbar {
            align-items: center;
            flex-direction: row;
            justify-content: space-between;
            gap: 12px;
        }

        .admin-agenda .agenda-filter-form {
            margin-left: auto;
        }

        .admin-agenda .table {
            min-width: 1050px;
        }
    }

    @media (max-width: 480px) {
        .admin-agenda .agenda-toolbar {
            gap: 8px;
        }

        .admin-agenda .agenda-panel {
            padding: 16px;
        }

        .admin-agenda .modal-body {
            padding: 20px;
        }

        .admin-agenda .modal-header,
        .admin-agenda .modal-footer {
            padding: 17px 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!window.jQuery || typeof window.jQuery.fn.daterangepicker !== 'function' || !window.moment) {
            return;
        }

        const $ = window.jQuery;
        const dateRangeInput = $('#agenda-filter-date-range');
        const startInput = $('#agenda-filter-start');
        const endInput = $('#agenda-filter-end');

        if (!dateRangeInput.length) {
            return;
        }

        const startValue = startInput.val();
        const endValue = endInput.val();

        const pickerOptions = {
            autoUpdateInput: false,
            showDropdowns: true,
            parentEl: '#agenda-filter-menu',
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Terapkan',
                cancelLabel: 'Batal',
                customRangeLabel: 'Rentang Khusus',
                daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ],
                firstDay: 1
            }
        };

        if (startValue && endValue) {
            const startDate = window.moment(startValue, 'YYYY-MM-DD', true);
            const endDate = window.moment(endValue, 'YYYY-MM-DD', true);

            if (startDate.isValid() && endDate.isValid()) {
                pickerOptions.startDate = startDate;
                pickerOptions.endDate = endDate;

                dateRangeInput.val(
                    startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY')
                );
            }
        }

        dateRangeInput.daterangepicker(pickerOptions);

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
@endpush
