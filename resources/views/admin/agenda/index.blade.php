
@extends('layouts.admin')

@section('title', 'Agenda Kegiatan')
@section('page_title', 'Agenda Kegiatan')

@section('content')
<div class="admin-agenda">

    {{-- HEADER HALAMAN --}}
    <div class="page-heading">
        <div>
    </div>

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

        <div class="section-heading">
            <div>
                <h2>Daftar Agenda</h2>
                <p>Kelola kegiatan tambahan sekolah beserta waktu, lokasi, dan statusnya.</p>
            </div>

            <div class="section-heading-tools">
                <span class="total-badge">
                    {{ $agenda->count() }} agenda
                </span>

                <button
                    type="button"
                    class="btn btn-light-primary dashboard-action-btn fw-semibold px-5 py-3 fs-6"
                    style="min-height: 43px;"
                    data-bs-toggle="modal"
                    data-bs-target="#tambahAgendaModal">

                    <i class="bi bi-plus-lg me-2"></i>
                    Tambah Agenda
                </button>
            </div>
        </div>

        {{-- TABEL AGENDA --}}
        <div class="table-responsive">
            <table class="table table-row-bordered table-row-gray-300 align-middle gy-5">

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
                                'terjadwal' => 'status-terjadwal',
                                'selesai' => 'status-selesai',
                                'dibatalkan' => 'status-dibatalkan',
                                default => 'status-default',
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
                                <span class="status-badge {{ $statusBadgeClass }}">
                                    <span class="status-dot"></span>
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
                                    class="form-select">

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
                                    value="{{ old('jam', $item->jam ? substr((string) $item->jam, 0, 5) : '') }}">
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
                                    placeholder="Ruang 1 / Aula">
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
                                    placeholder="Tambahkan keterangan kegiatan (opsional)">{{ old('keterangan', $item->keterangan) }}</textarea>
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

</div>

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
                            class="form-select">

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
                            value="{{ old('jam') }}">
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
                            value="{{ old('lokasi') }}">
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
                            placeholder="Tambahkan keterangan kegiatan (opsional)">{{ old('keterangan') }}</textarea>
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
    }

    .admin-agenda * {
        box-sizing: border-box;
    }

    /* HEADER */
    .admin-agenda .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        width: 100%;
        margin-bottom: 28px;
    }

    .admin-agenda .page-heading h1 {
        margin: 0 0 9px;
        color: var(--agenda-text);
        font-size: 32px;
        font-weight: 750;
    }

    .admin-agenda .page-heading p,
    .admin-agenda .section-heading p {
        margin: 0;
        color: var(--agenda-muted);
        font-size: 15px;
        line-height: 1.7;
    }

    /* PANEL TABEL */
    .admin-agenda .agenda-panel {
        display: block;
        width: 100%;
        max-width: none;
        margin-bottom: 26px;
        padding: 32px;
        background: var(--agenda-bg);
        border: 1px solid var(--agenda-border);
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(32, 96, 196, 0.06);
    }

    .admin-agenda .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        width: 100%;
        margin-bottom: 28px;
    }

    .admin-agenda .section-heading h2 {
        margin: 0 0 8px;
        color: var(--agenda-text);
        font-size: 22px;
        font-weight: 700;
    }

    .admin-agenda .section-heading-tools {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 14px;
        margin-left: auto;
    }

    .admin-agenda .total-badge {
        padding: 10px 15px;
        color: #1e5cc7;
        background: #eaf2ff;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
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
    }

    .admin-agenda .table thead th {
        color: #5f728d;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .02em;
        padding: 20px 18px;
        white-space: nowrap;
    }

    .admin-agenda .table tbody td {
        padding: 24px 18px;
        color: var(--agenda-text);
        font-size: 15px;
        font-weight: 500;
        vertical-align: middle;
    }

    .admin-agenda .table tbody td .fw-bold {
        font-size: 15px;
        font-weight: 700 !important;
    }

    .admin-agenda .table tbody td .text-muted {
        font-size: 14px;
    }

    /* BADGE STATUS */
    .admin-agenda .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        white-space: nowrap;
    }

    .admin-agenda .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
    }

    .admin-agenda .status-terjadwal {
        color: #1e5cc7;
        background: #eaf2ff;
    }

    .admin-agenda .status-selesai {
        color: #0f8f5f;
        background: #ebfaf3;
    }

    .admin-agenda .status-dibatalkan {
        color: #c82333;
        background: #fff1f2;
    }

    .admin-agenda .status-default {
        color: #596f8d;
        background: #f1f5f9;
    }

    /* TOMBOL */
    .admin-agenda .btn {
        padding: 11px 16px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 8px;
    }

    .admin-agenda .btn i {
        line-height: 1;
        font-size: 16px;
    }

    .admin-agenda .dashboard-action-btn {
        padding: 0.75rem 1.25rem;
        font-size: 1rem;
        font-weight: 600;
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

        .admin-agenda .section-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .admin-agenda .section-heading-tools {
            width: 100%;
            justify-content: space-between;
            margin-left: 0;
        }

        .admin-agenda .page-heading h1 {
            font-size: 27px;
        }

        .admin-agenda .table {
            min-width: 1050px;
        }
    }

    @media (max-width: 480px) {
        .admin-agenda .section-heading-tools {
            align-items: flex-start;
            flex-direction: column;
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
