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
    <div class="alert alert-success mb-5">
        {{ session('success') }}
    </div>
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

        $mataPelajaranList = [
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Bahasa Sunda',
            'Bimbingan Konseling',
            'Ilmu Pengetahuan Alam',
            'Ilmu Pengetahuan Sosial',
            'Matematika',
            'Pendidikan Agama Islam',
            'Pendidikan Agama Kristen',
            'Pendidikan Jasmani dan Olahraga Kebugaran',
            'Pendidikan Pancasila',
            'Seni Budaya dan Keterampilan',
        ];

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

                    @if($isKelasRequired)
                    <div class="col-md-6 col-xl-3">
                        <label for="kelas" class="form-label fw-semibold">
                            Kelas
                        </label>

                        <select name="kelas" id="kelas" class="form-select" required>
                            <option value="">Pilih kelas</option>
                            @foreach($kelasList as $kelas)
                            <option
                                value="{{ $kelas }}"
                                {{ old('kelas') == $kelas ? 'selected' : '' }}>
                                Kelas {{ $kelas }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-6 col-xl-3">
                        <label for="semester" class="form-label fw-semibold">
                            Semester
                        </label>

                        <select name="semester" id="semester" class="form-select" required>
                            <option value="">Pilih semester</option>
                            <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>
                                Semester 1
                            </option>
                            <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>
                                Semester 2
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <label for="mata_pelajaran" class="form-label fw-semibold">
                            Mata Pelajaran
                        </label>

                        <select
                            name="mata_pelajaran"
                            id="mata_pelajaran"
                            class="form-select"
                            required>

                            <option value="">Pilih mata pelajaran</option>

                            @foreach($mataPelajaranList as $item)
                            <option
                                value="{{ $item }}"
                                {{ old('mata_pelajaran') == $item ? 'selected' : '' }}>
                                {{ $item }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 col-xl-3">
                        <div class="d-grid">
                            <label
                                for="file-dokumen"
                                class="btn btn-light-primary mb-0 w-100">

                                <i class="bi bi-cloud-arrow-up me-1"></i>
                                Pilih File PDF
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
                    {{ $dokumens->count() }}
                </span>
            </h3>

            <form
                action="{{ route('dokumen.kategori', ['jenis' => $jenis]) }}"
                method="GET"
                class="mb-0"
                id="filter-mata-pelajaran-form">

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

                            <select name="semester" id="filter-semester" class="form-select">
                                <option value="">Semua Semester</option>
                                <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>
                                    Semester 1
                                </option>
                                <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>
                                    Semester 2
                                </option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="filter-mapel" class="form-label fw-semibold">
                                Mata Pelajaran
                            </label>

                            <select
                                name="mata_pelajaran"
                                id="filter-mapel"
                                class="form-select">

                                <option value="">Semua Mata Pelajaran</option>

                                @foreach($mataPelajaranList as $item)
                                <option
                                    value="{{ $item }}"
                                    {{ request('mata_pelajaran') == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                                @endforeach
                            </select>
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
                        $dokumenKelas = $dokumens->where('kelas', (string) $kelas);
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
                                                Kelas {{ $dokumen->kelas ?? '-' }}
                                                <span class="mx-1">•</span>
                                                Semester {{ $dokumen->semester ?? '-' }}
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

                                    {{-- TAMBAHAN: KOMENTAR ADMIN --}}
                                    @php
                                        $komentarDokumen = $komentarPerDokumen->get(
                                            $dokumen->id,
                                            collect()
                                        );
                                    @endphp

                                    <div class="mt-4 pt-4 border-top">
                                        <div class="fw-bold text-gray-800 mb-3">
                                            <i class="bi bi-chat-left-text me-2"></i>
                                            Komentar Admin
                                        </div>

                                        @forelse($komentarDokumen as $komentar)
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
                                        @empty
                                            <div class="text-muted fs-7">
                                                <i class="bi bi-chat-left me-1"></i>
                                                Belum ada komentar dari admin untuk dokumen ini.
                                            </div>
                                        @endforelse
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

                            {{-- TAMBAHAN: KOMENTAR ADMIN --}}
                            @php
                                $komentarDokumen = $komentarPerDokumen->get(
                                    $dokumen->id,
                                    collect()
                                );
                            @endphp

                            <div class="mt-4 pt-4 border-top">
                                <div class="fw-bold text-gray-800 mb-3">
                                    <i class="bi bi-chat-left-text me-2"></i>
                                    Komentar Admin
                                </div>

                                @forelse($komentarDokumen as $komentar)
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
                                @empty
                                    <div class="text-muted fs-7">
                                        <i class="bi bi-chat-left me-1"></i>
                                        Belum ada komentar dari admin untuk dokumen ini.
                                    </div>
                                @endforelse
                            </div>

                        </div>
                    @endforeach

                @endif

            @endif

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const requiresKelas = @json($isKelasRequired);
        const labelDokumen = @json($label);

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

                if (file.size > 10 * 1024 * 1024) {
                    return 'Ukuran PDF maksimal 10 MB.';
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
    });
</script>
@endsection
