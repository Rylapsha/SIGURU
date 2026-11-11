@extends('layouts.app')

@section('page_title', $label ?? 'Dokumen')

@section('content')
<div class="container-fluid">

    <div class="mt-5">
        <a href="{{ route('dokumen.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card mt-5 mb-7">
        <div class="card-body py-6">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <i class="bi bi-file-earmark-pdf-fill fs-2x text-primary"></i>
                    </div>
                </div>

                <div class="flex-grow-1">
                    <h2 class="fw-bold text-gray-900 mb-1">
                        Dokumen {{ $label }}
                    </h2>
                    <span class="text-muted fs-6">
                        Kelola, lihat, unduh, dan unggah dokumen {{ $label }}.
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div id="success-message" data-message="{{ session('success') }}" class="d-none"></div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger mb-5">
        <strong>Periksa kembali input kamu:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @php
    $isKelasRequired = !in_array(
    $jenis,
    ['prota-prosem', 'prota', 'prosem'],
    true
    );

    $kelasList = ['10', '11', '12'];
    @endphp

    {{-- FORM UPLOAD DOKUMEN --}}
    <div class="card mb-7">
        <div class="card-body">
            <form
                action="{{ route('dokumen.store', ['jenis' => $jenis]) }}"
                method="POST"
                enctype="multipart/form-data"
                id="form-upload-dokumen">

                @csrf

                <div class="row g-4 align-items-end">
                    <h3>
                        Unggah Dokumen {{ $label }}
                    </h3>

                    @if($isKelasRequired)
                    <div class="col-md-6 col-xl-3">
                        <label for="kelas" class="form-label fw-semibold">
                            Kelas
                        </label>

                        <div class="custom-select-picker" data-custom-select>
                            <button type="button"
                                class="custom-select-trigger form-control text-start"
                                aria-expanded="false">
                                <span class="custom-select-value">
                                    {{ old('kelas') ? 'Kelas ' . old('kelas') : 'Pilih kelas' }}
                                </span>
                                <i class="bi bi-chevron-down ms-auto"></i>
                            </button>

                            <div class="custom-select-menu d-none" role="listbox">
                                <button type="button" class="custom-select-option {{ old('kelas') === '' ? 'selected' : '' }}" data-value="" data-label="Pilih kelas" disabled aria-disabled="true">
                                    <span>Pilih kelas</span>
                                </button>

                                @foreach($kelasList as $kelas)
                                <button type="button" class="custom-select-option {{ old('kelas') == $kelas ? 'selected' : '' }}" data-value="{{ $kelas }}" data-label="Kelas {{ $kelas }}">
                                    <span>Kelas {{ $kelas }}</span>
                                </button>
                                @endforeach
                            </div>

                            <input type="hidden" name="kelas" value="{{ old('kelas') }}" id="kelas">
                        </div>
                    </div>
                    @endif

                    <div class="col-md-6 col-xl-3">
                        <label for="semester" class="form-label fw-semibold">
                            Semester
                        </label>

                        <div class="custom-select-picker" data-custom-select>
                            <button type="button"
                                class="custom-select-trigger form-control text-start"
                                aria-expanded="false">
                                <span class="custom-select-value">
                                    @if(old('semester') === '1')
                                    Semester 1
                                    @elseif(old('semester') === '2')
                                    Semester 2
                                    @else
                                    Pilih semester
                                    @endif
                                </span>
                                <i class="bi bi-chevron-down ms-auto"></i>
                            </button>

                            <div class="custom-select-menu d-none" role="listbox">
                                <button type="button" class="custom-select-option {{ old('semester') === '' ? 'selected' : '' }}" data-value="" data-label="Pilih semester" disabled aria-disabled="true">
                                    <span>Pilih semester</span>
                                </button>
                                <button type="button" class="custom-select-option {{ old('semester') == '1' ? 'selected' : '' }}" data-value="1" data-label="Semester 1">
                                    <span>Semester 1</span>
                                </button>
                                <button type="button" class="custom-select-option {{ old('semester') == '2' ? 'selected' : '' }}" data-value="2" data-label="Semester 2">
                                    <span>Semester 2</span>
                                </button>
                            </div>

                            <input type="hidden" name="semester" value="{{ old('semester') }}" id="semester">
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <label for="tahun_ajaran" class="form-label fw-semibold">
                            Tahun Ajaran
                        </label>

                        <div class="custom-select-picker" data-custom-select>
                            <button type="button"
                                class="custom-select-trigger form-control text-start"
                                aria-expanded="false">
                                <span class="custom-select-value">
                                    {{ old('tahun_ajaran', $tahunAjaranAktif) ?: 'Pilih tahun ajaran' }}
                                </span>
                                <i class="bi bi-chevron-down ms-auto"></i>
                            </button>

                            <div class="custom-select-menu d-none" role="listbox">
                                <button type="button" class="custom-select-option {{ old('tahun_ajaran', $tahunAjaranAktif) === '' ? 'selected' : '' }}" data-value="" data-label="Pilih tahun ajaran" disabled aria-disabled="true">
                                    <span>Pilih tahun ajaran</span>
                                </button>

                                @foreach($tahunAjaranOptions as $tahunAjaran)
                                <button type="button" class="custom-select-option {{ old('tahun_ajaran', $tahunAjaranAktif) == $tahunAjaran ? 'selected' : '' }}" data-value="{{ $tahunAjaran }}" data-label="{{ $tahunAjaran }}">
                                    <span>{{ $tahunAjaran }}</span>
                                </button>
                                @endforeach
                            </div>

                            <input type="hidden" name="tahun_ajaran" value="{{ old('tahun_ajaran', $tahunAjaranAktif) }}" id="tahun_ajaran">
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <label for="mata_pelajaran" class="form-label fw-semibold">
                            Mata Pelajaran
                        </label>

                        <div class="position-relative custom-subject-picker" data-custom-subject-picker>
                            <input
                                name="mata_pelajaran"
                                id="mata_pelajaran"
                                type="text"
                                class="form-control custom-subject-input"
                                value="{{ old('mata_pelajaran') }}"
                                placeholder="Pilih mata pelajaran"
                                autocomplete="off"
                                required>

                            <div class="custom-subject-suggestions d-none" role="listbox" aria-label="Pilihan mata pelajaran">
                                <div class="custom-subject-list"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-xl-12">
                        <div class="d-grid">
                            <label
                                for="file-dokumen"
                                class="btn btn-light-primary dashboard-action-btn fw-semibold px-5 py-3 fs-6 mb-0 w-100"
                                style="min-height: 43px;">
                                <i class="bi bi-plus-lg me-2"></i>
                                Unggah File
                            </label>

                            <input
                                type="file"
                                name="file"
                                id="file-dokumen"
                                accept=".pdf,application/pdf"
                                class="d-none"
                                required>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- DAFTAR DAN FILTER DOKUMEN --}}
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">

            <h3 class="card-title fw-bold mb-0">
                Daftar {{ $label }}
                <span class="badge badge-light-primary ms-2">
                    {{ $dokumens->total() }}
                </span>
            </h3>

            <form
                action="{{ route('dokumen.kategori', ['jenis' => $jenis]) }}"
                method="GET"
                class="d-flex align-items-center gap-2 flex-wrap mb-0"
                id="filter-mata-pelajaran-form">

                <div class="position-relative">
                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control form-control-sm"
                        style="width: 220px;"
                        placeholder="Cari dokumen...">
                </div>

                <div class="position-relative d-inline-block" id="document-filter">

                    <button
                        type="button"
                        class="btn btn-light-primary btn-sm d-flex align-items-center gap-2"
                        id="filter-toggle"
                        aria-expanded="false">

                        <i class="bi bi-funnel"></i>
                        <span>Filter</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <div
                        id="filter-panel"
                        class="card shadow-lg border-0 p-4 position-absolute mt-2"
                        style="display: none; width: 300px; max-width: calc(100vw - 40px); z-index: 1050; right: 0;">

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold mb-0">Filter Dokumen</h6>

                            <button
                                type="button"
                                class="btn btn-sm btn-icon btn-light"
                                id="filter-close"
                                aria-label="Tutup filter">

                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>

                        <div class="mb-4">
                            <label for="filter-semester" class="form-label fw-semibold">
                                Semester
                            </label>

                            <div class="custom-select-picker" data-custom-select>
                                <button type="button"
                                    class="custom-select-trigger form-control text-start"
                                    aria-expanded="false">
                                    <span class="custom-select-value">
                                        @if(request('semester') === '1')
                                        Semester 1
                                        @elseif(request('semester') === '2')
                                        Semester 2
                                        @else
                                        Semua semester
                                        @endif
                                    </span>
                                    <i class="bi bi-chevron-down ms-auto"></i>
                                </button>

                                <div class="custom-select-menu d-none" role="listbox">
                                    <button type="button" class="custom-select-option {{ request('semester') === '' ? 'selected' : '' }}" data-value="" data-label="Semua semester" disabled aria-disabled="true">
                                        <span>Semua semester</span>
                                    </button>
                                    <button type="button" class="custom-select-option {{ request('semester') == '1' ? 'selected' : '' }}" data-value="1" data-label="Semester 1">
                                        <span>Semester 1</span>
                                    </button>
                                    <button type="button" class="custom-select-option {{ request('semester') == '2' ? 'selected' : '' }}" data-value="2" data-label="Semester 2">
                                        <span>Semester 2</span>
                                    </button>
                                </div>

                                <input type="hidden" name="semester" value="{{ request('semester') }}" id="filter-semester">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="filter-tahun-ajaran" class="form-label fw-semibold">
                                Tahun Ajaran
                            </label>

                            <div class="custom-select-picker" data-custom-select>
                                <button type="button"
                                    class="custom-select-trigger form-control text-start"
                                    aria-expanded="false">
                                    <span class="custom-select-value">
                                        {{ request('tahun_ajaran') ?: 'Semua tahun ajaran' }}
                                    </span>
                                    <i class="bi bi-chevron-down ms-auto"></i>
                                </button>

                                <div class="custom-select-menu d-none" role="listbox">
                                    <button type="button" class="custom-select-option {{ request('tahun_ajaran') === '' ? 'selected' : '' }}" data-value="" data-label="Semua tahun ajaran" disabled aria-disabled="true">
                                        <span>Semua tahun ajaran</span>
                                    </button>

                                    @foreach($tahunAjaranOptions as $tahunAjaran)
                                    <button type="button" class="custom-select-option {{ request('tahun_ajaran') == $tahunAjaran ? 'selected' : '' }}" data-value="{{ $tahunAjaran }}" data-label="{{ $tahunAjaran }}">
                                        <span>{{ $tahunAjaran }}</span>
                                    </button>
                                    @endforeach
                                </div>

                                <input type="hidden" name="tahun_ajaran" value="{{ request('tahun_ajaran') }}" id="filter-tahun-ajaran">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="filter-mapel" class="form-label fw-semibold">
                                Mata Pelajaran
                            </label>

                            <div class="position-relative custom-subject-picker" data-custom-subject-picker>
                                <input
                                    name="mata_pelajaran"
                                    id="filter-mapel"
                                    type="text"
                                    class="form-control custom-subject-input"
                                    value="{{ request('mata_pelajaran') }}"
                                    placeholder="Pilih mata pelajaran"
                                    autocomplete="off">

                                <div class="custom-subject-suggestions d-none" role="listbox" aria-label="Pilihan mata pelajaran filter">
                                    <div class="custom-subject-list"></div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button
                                type="button"
                                class="btn btn-light w-50"
                                id="filter-reset">
                                Reset
                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary w-50"
                                id="filter-apply">
                                Terapkan
                            </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">

            @if($isKelasRequired)

            @foreach($kelasList as $kelas)
            @php
            $dokumenKelas = collect($dokumens->items())->where('kelas', (string) $kelas);
            @endphp

            <div class="mb-7">
                <h4 class="fw-bold text-gray-800 mb-4">
                    Kelas {{ $kelas }}
                </h4>

                @if($dokumenKelas->isEmpty())
                <div class="border rounded p-5 text-center text-muted">
                    Belum ada dokumen untuk kelas {{ $kelas }}.
                </div>
                @else

                @foreach($dokumenKelas as $dokumen)
                <div class="border rounded p-4 mb-4">

                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">

                        <div class="symbol symbol-45px">
                            <div class="symbol-label bg-light-danger">
                                <i class="bi bi-file-earmark-pdf-fill fs-2 text-danger"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-bold text-gray-800 text-break">
                                {{ $dokumen->nama_file }}
                            </div>

                            <div class="text-muted fs-7 mt-1">
                                {{ $dokumen->mata_pelajaran ?? '-' }}
                                <span class="mx-1">•</span>
                                Semester {{ $dokumen->semester ?? '-' }}
                                <span class="mx-1">•</span>
                                Tahun Ajaran {{ $dokumen->tahun_ajaran ?? 'Belum ditentukan' }}
                                <span class="mx-1">•</span>
                                PDF
                                <span class="mx-1">•</span>

                                @if(\Illuminate\Support\Facades\Storage::disk('public')->exists($dokumen->file_path))
                                {{ number_format(\Illuminate\Support\Facades\Storage::disk('public')->size($dokumen->file_path) / 1024, 1, ',', '.') }} KB
                                @else
                                File tidak ditemukan
                                @endif

                                <span class="mx-1">•</span>
                                Diunggah {{ $dokumen->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <a
                                href="{{ route('dokumen.lihat', $dokumen->id) }}"
                                target="_blank"
                                class="btn btn-sm btn-light-primary">

                                <i class="bi bi-eye me-1"></i> Lihat
                            </a>

                            <a
                                href="{{ route('dokumen.unduh', $dokumen->id) }}"
                                class="btn btn-sm btn-light-success">

                                <i class="bi bi-download me-1"></i> Unduh
                            </a>

                            <form
                                action="{{ route('dokumen.destroy', $dokumen->id) }}"
                                method="POST"
                                class="form-hapus-dokumen">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-sm btn-light-danger">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </button>
                            </form>
                        </div>

                    </div>

                    @php
                    $komentarDokumen = $komentarPerDokumen->get(
                    $dokumen->id,
                    collect()
                    );
                    @endphp

                    <div class="mt-4 pt-4 border-top">
                        <button class="btn btn-link text-gray-800 fw-bold px-0 d-flex align-items-center gap-2"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#komentar-dokumen-{{ $dokumen->id }}"
                            aria-expanded="false"
                            aria-controls="komentar-dokumen-{{ $dokumen->id }}">
                            <i class="bi bi-chat-left-text me-1"></i>
                            Komentar
                            <i class="bi bi-chevron-down fs-7"></i>
                        </button>

                        <div id="komentar-dokumen-{{ $dokumen->id }}" class="collapse mt-3">
                            @if($komentarDokumen->isNotEmpty())
                            @foreach($komentarDokumen as $komentar)
                            <div class="bg-light rounded p-3 mb-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-person-circle fs-3 text-primary"></i>

                                    <div>
                                        <div class="fw-semibold text-gray-800">
                                            Admin
                                        </div>

                                        <small class="text-muted">
                                            {{ $komentar->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>

                                <div class="text-gray-700"
                                    style="white-space: pre-line; overflow-wrap: anywhere;">{{ $komentar->komentar }}</div>
                            </div>
                            @endforeach
                            @else
                            <div class="text-muted fs-7">
                                <i class="bi bi-chat-left me-1"></i>
                                Tidak ada komentar.
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
                @endforeach

                @endif
            </div>
            @endforeach

            @else

            @if($dokumens->isEmpty())
            <div class="border rounded p-5 text-center text-muted">
                Belum ada dokumen untuk {{ $label }}.
            </div>
            @else

            @foreach($dokumens as $dokumen)
            <div class="border rounded p-4 mb-4">

                <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">

                    <div class="symbol symbol-45px">
                        <div class="symbol-label bg-light-danger">
                            <i class="bi bi-file-earmark-pdf-fill fs-2 text-danger"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold text-gray-800 text-break">
                            {{ $dokumen->nama_file }}
                        </div>

                        <div class="text-muted fs-7 mt-1">
                            {{ $dokumen->mata_pelajaran ?? '-' }}
                            <span class="mx-1">•</span>
                            Semester {{ $dokumen->semester ?? '-' }}
                            <span class="mx-1">•</span>
                            Tahun Ajaran {{ $dokumen->tahun_ajaran ?? 'Belum ditentukan' }}
                            <span class="mx-1">•</span>
                            PDF
                            <span class="mx-1">•</span>

                            @if(\Illuminate\Support\Facades\Storage::disk('public')->exists($dokumen->file_path))
                            {{ number_format(\Illuminate\Support\Facades\Storage::disk('public')->size($dokumen->file_path) / 1024, 1, ',', '.') }} KB
                            @else
                            File tidak ditemukan
                            @endif

                            <span class="mx-1">•</span>
                            Diunggah {{ $dokumen->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a
                            href="{{ route('dokumen.lihat', $dokumen->id) }}"
                            target="_blank"
                            class="btn btn-sm btn-light-primary">

                            <i class="bi bi-eye me-1"></i> Lihat
                        </a>

                        <a
                            href="{{ route('dokumen.unduh', $dokumen->id) }}"
                            class="btn btn-sm btn-light-success">

                            <i class="bi bi-download me-1"></i> Unduh
                        </a>

                        <form
                            action="{{ route('dokumen.destroy', $dokumen->id) }}"
                            method="POST"
                            class="form-hapus-dokumen">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-sm btn-light-danger">
                                <i class="bi bi-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>

                </div>

                @php
                $komentarDokumen = $komentarPerDokumen->get(
                $dokumen->id,
                collect()
                );
                @endphp

                <div class="mt-4 pt-4 border-top">
                    <button class="btn btn-link text-gray-800 fw-bold px-0 d-flex align-items-center gap-2"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#komentar-dokumen-{{ $dokumen->id }}"
                        aria-expanded="false"
                        aria-controls="komentar-dokumen-{{ $dokumen->id }}">
                        <i class="bi bi-chat-left-text me-1"></i>
                        Komentar
                        <i class="bi bi-chevron-down fs-7"></i>
                    </button>

                    <div id="komentar-dokumen-{{ $dokumen->id }}" class="collapse mt-3">
                        @if($komentarDokumen->isNotEmpty())
                        @foreach($komentarDokumen as $komentar)
                        <div class="bg-light rounded p-3 mb-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-person-circle fs-3 text-primary"></i>

                                <div>
                                    <div class="fw-semibold text-gray-800">
                                        Admin
                                    </div>

                                    <small class="text-muted">
                                        {{ $komentar->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>

                            <div class="text-gray-700"
                                style="white-space: pre-line; overflow-wrap: anywhere;">{{ $komentar->komentar }}</div>
                        </div>
                        @endforeach
                        @else
                        <div class="text-muted fs-7">
                            <i class="bi bi-chat-left me-1"></i>
                            Belum ada komentar.
                        </div>
                        @endif
                    </div>
                </div>

            </div>
            @endforeach

            @endif

            @endif

        </div>

        @if($dokumens->hasPages())
        <div class="card-footer border-0 bg-transparent pb-5 pt-0">
            <div class="d-flex justify-content-center">
                {{ $dokumens->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const requiresKelas = @json($isKelasRequired);
        const labelDokumen = @json($label);
        const successMessage = document.getElementById('success-message');

        if (successMessage && successMessage.dataset.message) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: successMessage.dataset.message,
                confirmButtonText: 'OK',
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false
            });
        }

        // ==========================================
        // FORM UPLOAD
        // ==========================================

        const formUpload = document.getElementById('form-upload-dokumen');
        const fileInput = document.getElementById('file-dokumen');
        const kelasInput = document.getElementById('kelas');
        const semesterInput = document.getElementById('semester');
        const mataPelajaranInput = document.getElementById('mata_pelajaran');

        if (formUpload && fileInput && mataPelajaranInput) {
            let uploadConfirmed = false;
            let confirmationOpen = false;

            function escapeHtml(value) {
                return String(value).replace(/[&<>"']/g, function(char) {
                    const entities = {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    };

                    return entities[char];
                });
            }

            function getMataPelajaran() {
                return mataPelajaranInput.value.trim();
            }

            function validateBeforeUpload(file) {
                const kelas = kelasInput ? kelasInput.value : '';
                const semester = semesterInput ? semesterInput.value : '';
                const mataPelajaran = getMataPelajaran();

                if (requiresKelas && !kelas) {
                    return 'Pilih kelas terlebih dahulu.';
                }

                if (!semester) {
                    return 'Pilih semester terlebih dahulu.';
                }

                if (!mataPelajaran) {
                    return 'Pilih mata pelajaran terlebih dahulu.';
                }

                if (!/\.pdf$/i.test(file.name)) {
                    return 'File harus berformat PDF.';
                }

                if (file.size > 50 * 1024 * 1024) {
                    return 'Ukuran PDF maksimal 50 MB.';
                }

                return null;
            }

            fileInput.addEventListener('change', async function() {
                const file = this.files && this.files[0];

                if (!file) return;

                uploadConfirmed = false;

                const validationMessage = validateBeforeUpload(file);

                if (validationMessage) {
                    await Swal.fire({
                        title: 'Data belum lengkap',
                        text: validationMessage,
                        icon: 'warning',
                        confirmButtonText: 'Mengerti'
                    });

                    this.value = '';
                    return;
                }

                if (confirmationOpen) return;

                confirmationOpen = true;

                const kelas = kelasInput ? kelasInput.value : '';
                const semester = semesterInput ? semesterInput.value : '';
                const mataPelajaran = getMataPelajaran();

                const detailKelas = requiresKelas ?
                    '<p class="mb-2"><strong>Kelas:</strong> ' +
                    escapeHtml(kelas) + '</p>' :
                    '';

                try {
                    const result = await Swal.fire({
                        title: 'Konfirmasi Upload',
                        html: '<div class="text-start">' +
                            '<p class="mb-1"><strong>Nama file:</strong></p>' +
                            '<p class="mb-3 text-break">' + escapeHtml(file.name) + '</p>' +
                            '<p class="mb-2"><strong>Jenis dokumen:</strong> ' +
                            escapeHtml(labelDokumen) + '</p>' +
                            detailKelas +
                            '<p class="mb-2"><strong>Semester:</strong> ' +
                            escapeHtml(semester) + '</p>' +
                            '<p class="mb-2"><strong>Mata pelajaran:</strong> ' +
                            escapeHtml(mataPelajaran) + '</p>' +
                            '<p class="mb-0 text-muted">Ukuran: ' +
                            (file.size / 1024 / 1024).toFixed(2) + ' MB</p>' +
                            '</div>',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Kirim',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        focusCancel: true
                    });

                    if (!result.isConfirmed) {
                        fileInput.value = '';
                        return;
                    }

                    if (!fileInput.files || fileInput.files[0] !== file) {
                        await Swal.fire(
                            'File berubah',
                            'Silakan pilih kembali file yang akan diunggah.',
                            'warning'
                        );

                        fileInput.value = '';
                        return;
                    }

                    const latestError = validateBeforeUpload(file);

                    if (latestError) {
                        await Swal.fire(
                            'Upload dibatalkan',
                            latestError,
                            'warning'
                        );

                        fileInput.value = '';
                        return;
                    }

                    uploadConfirmed = true;
                    formUpload.requestSubmit();

                } catch (error) {
                    console.error('Kesalahan konfirmasi upload:', error);

                    await Swal.fire(
                        'Terjadi kesalahan',
                        'Konfirmasi upload gagal ditampilkan. Silakan coba lagi.',
                        'error'
                    );

                    fileInput.value = '';
                } finally {
                    confirmationOpen = false;
                }
            });

            formUpload.addEventListener('submit', function(event) {
                if (!uploadConfirmed) {
                    event.preventDefault();
                    return;
                }

                const file = fileInput.files && fileInput.files[0];
                const error = file ?
                    validateBeforeUpload(file) :
                    'Silakan pilih file PDF terlebih dahulu.';

                if (error) {
                    event.preventDefault();
                    uploadConfirmed = false;

                    Swal.fire({
                        title: 'Data belum valid',
                        text: error,
                        icon: 'warning',
                        confirmButtonText: 'Mengerti'
                    });

                    return;
                }

                uploadConfirmed = false;
            });
        }

        // ==========================================
        // FILTER SEMESTER DAN MATA PELAJARAN
        // ==========================================

        const filterToggle = document.getElementById('filter-toggle');
        const filterPanel = document.getElementById('filter-panel');
        const filterClose = document.getElementById('filter-close');
        const filterForm = document.getElementById('filter-mata-pelajaran-form');
        const filterReset = document.getElementById('filter-reset');

        if (filterToggle && filterPanel && filterForm) {
            filterToggle.addEventListener('click', function() {
                const isOpen = filterPanel.style.display !== 'none';

                filterPanel.style.display = isOpen ? 'none' : 'block';
                filterToggle.setAttribute('aria-expanded', String(!isOpen));
            });

            if (filterClose) {
                filterClose.addEventListener('click', function() {
                    filterPanel.style.display = 'none';
                    filterToggle.setAttribute('aria-expanded', 'false');
                });
            }

            if (filterReset) {
                filterReset.addEventListener('click', function() {
                    window.location.href = filterForm.action;
                });
            }

            document.addEventListener('click', function(event) {
                const container = document.getElementById('document-filter');

                if (container && !container.contains(event.target)) {
                    filterPanel.style.display = 'none';
                    filterToggle.setAttribute('aria-expanded', 'false');
                }
            });
        }

        // ==========================================
        // KONFIRMASI HAPUS DOKUMEN
        // ==========================================

        document.querySelectorAll('.form-hapus-dokumen').forEach(function(formHapus) {
            formHapus.addEventListener('submit', async function(event) {
                event.preventDefault();

                const result = await Swal.fire({
                    title: 'Hapus dokumen?',
                    text: 'File yang dihapus tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                });

                if (result.isConfirmed) {
                    formHapus.submit();
                }
            });
        });

        // ==========================================
        // CUSTOM DROPDOWN MATA PELAJARAN
        // ==========================================

        const subjectOptions = @json($mataPelajaranList);

        function setupCustomSubjectPicker(root) {
            if (!root) return;

            const input = root.querySelector('.custom-subject-input');
            const suggestionBox = root.querySelector('.custom-subject-suggestions');
            const list = root.querySelector('.custom-subject-list');

            if (!input || !suggestionBox || !list) return;

            const renderOptions = (keyword = '') => {
                const query = keyword.trim().toLowerCase();
                const filtered = subjectOptions.filter(function(item) {
                    return !query || item.toLowerCase().includes(query);
                });

                list.innerHTML = '';

                if (filtered.length === 0) {
                    const empty = document.createElement('div');
                    empty.className = 'custom-subject-empty';
                    empty.textContent = 'Tidak ada mata pelajaran';
                    list.appendChild(empty);
                    return;
                }

                filtered.forEach(function(item) {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'custom-subject-option';
                    button.setAttribute('role', 'option');
                    button.dataset.value = item;
                    button.innerHTML = '<span class="subject-name">' + item + '</span>';

                    button.addEventListener('click', function() {
                        input.value = item;
                        suggestionBox.classList.add('d-none');
                        input.focus();
                    });

                    list.appendChild(button);
                });
            };

            input.addEventListener('input', function() {
                const keyword = input.value;
                if (keyword.trim().length === 0) {
                    suggestionBox.classList.add('d-none');
                    return;
                }

                renderOptions(keyword);
                suggestionBox.classList.remove('d-none');
            });

            input.addEventListener('focus', function() {
                if (input.value.trim().length > 0) {
                    renderOptions(input.value);
                    suggestionBox.classList.remove('d-none');
                    return;
                }

                renderOptions('');
                suggestionBox.classList.remove('d-none');
            });

            document.addEventListener('click', function(event) {
                if (!root.contains(event.target)) {
                    suggestionBox.classList.add('d-none');
                }
            });

            input.addEventListener('keydown', function(event) {
                const buttons = [...list.querySelectorAll('.custom-subject-option')];
                if (buttons.length === 0) return;

                const activeIndex = buttons.findIndex(function(button) {
                    return button.classList.contains('active');
                });

                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    const nextIndex = activeIndex >= 0 ? Math.min(activeIndex + 1, buttons.length - 1) : 0;
                    buttons.forEach(function(button, index) {
                        button.classList.toggle('active', index === nextIndex);
                    });
                    return;
                }

                if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    const prevIndex = activeIndex > 0 ? activeIndex - 1 : 0;
                    buttons.forEach(function(button, index) {
                        button.classList.toggle('active', index === prevIndex);
                    });
                    return;
                }

                if (event.key === 'Enter') {
                    const activeButton = buttons.find(function(button) {
                        return button.classList.contains('active');
                    });

                    if (activeButton) {
                        event.preventDefault();
                        input.value = activeButton.dataset.value;
                        suggestionBox.classList.add('d-none');
                    }
                }
            });

            renderOptions(input.value || '');
            suggestionBox.classList.add('d-none');
        }

        document.querySelectorAll('[data-custom-subject-picker]').forEach(setupCustomSubjectPicker);

        // ==========================================
        // CUSTOM DROPDOWN KELAS DAN SEMESTER
        // ==========================================

        document.querySelectorAll('[data-custom-select]').forEach(function(picker) {
            const trigger = picker.querySelector('.custom-select-trigger');
            const menu = picker.querySelector('.custom-select-menu');
            const hiddenInput = picker.querySelector('input[type="hidden"]');
            const valueLabel = picker.querySelector('.custom-select-value');
            const options = [...picker.querySelectorAll('.custom-select-option')];

            if (!trigger || !menu || !hiddenInput || !valueLabel) return;

            const setSelection = function(value, label) {
                hiddenInput.value = value;
                valueLabel.textContent = label || 'Pilih';
                trigger.setAttribute('aria-expanded', 'false');
                menu.classList.add('d-none');

                options.forEach(function(option) {
                    option.classList.toggle('selected', option.dataset.value === value);
                });
            };

            trigger.addEventListener('click', function() {
                const isOpen = !menu.classList.contains('d-none');
                menu.classList.toggle('d-none', isOpen);
                trigger.setAttribute('aria-expanded', String(!isOpen));
            });

            options.forEach(function(option) {
                option.addEventListener('click', function() {
                    if (option.disabled) return;
                    setSelection(option.dataset.value, option.dataset.label || option.textContent.trim());
                });
            });

            document.addEventListener('click', function(event) {
                if (!picker.contains(event.target)) {
                    menu.classList.add('d-none');
                    trigger.setAttribute('aria-expanded', 'false');
                }
            });

            const currentValue = hiddenInput.value;
            const currentOption = options.find(function(option) {
                return option.dataset.value === currentValue;
            });

            if (currentOption) {
                setSelection(currentOption.dataset.value, currentOption.dataset.label || currentOption.textContent.trim());
            }
        });
    });
</script>

<style>
    .custom-subject-picker {
        position: relative;
    }

    .custom-subject-input {
        width: 100%;
    }

    .custom-subject-suggestions {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        z-index: 1060;
        max-height: 240px;
        overflow-y: auto;
        padding: 8px;
        background: #fff;
        border: 1px solid #e4e6ef;
        border-radius: 10px;
        box-shadow: 0 12px 32px rgba(16, 24, 40, 0.12);
    }

    .custom-subject-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .custom-subject-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        width: 100%;
        padding: 10px 12px;
        border: 0;
        border-radius: 8px;
        background: transparent;
        color: #1f2a37;
        text-align: left;
        font-size: 0.92rem;
        cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .custom-subject-option:hover,
    .custom-subject-option.active {
        background: #eef5ff;
        color: #0d6efd;
    }

    .custom-subject-option .subject-name {
        flex: 1;
        min-width: 0;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .custom-subject-empty {
        padding: 10px 12px;
        color: #6b7280;
        font-size: 0.88rem;
    }

    .custom-select-picker {
        position: relative;
    }

    .custom-select-trigger {
        display: flex !important;
        align-items: center !important;
        width: 100%;
        gap: 12px;
        background: #fff;
        color: #1f2a37;
        cursor: pointer;
        text-align: left;
    }

    .custom-select-value {
        display: inline-block;
        min-width: 0;
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .custom-select-menu {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        z-index: 1060;
        max-height: 240px;
        overflow-y: auto;
        padding: 8px;
        background: #fff;
        border: 1px solid #e4e6ef;
        border-radius: 10px;
        box-shadow: 0 12px 32px rgba(16, 24, 40, 0.12);
    }

    .custom-select-option {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 10px 12px;
        border: 0;
        border-radius: 8px;
        background: transparent;
        color: #1f2a37;
        text-align: left;
        font-size: 0.92rem;
        cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .custom-select-option:hover,
    .custom-select-option.selected {
        background: #eef5ff;
        color: #0d6efd;
    }

    .custom-select-option:disabled,
    .custom-select-option[aria-disabled="true"] {
        opacity: 0.65;
        cursor: not-allowed;
        pointer-events: none;
        background: transparent !important;
        color: #7b8093 !important;
    }

    .custom-select-option:disabled:hover,
    .custom-select-option[aria-disabled="true"]:hover {
        background: transparent !important;
        color: #7b8093 !important;
    }
</style>
@endsection