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
                    <h2 class="fw-bold text-gray-900 mb-1">Dokumen {{ $label }}</h2>
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
    $isKelasRequired = !in_array($jenis, ['prota-prosem', 'prota', 'prosem'], true);
    $mataPelajaranList = [
    'Bahasa Indonesia',
    'Bahasa Inggris',
    'Bahasa Sunda',
    'Bimbingan Konseling',
    'Ilmu Pengetahuan Alam',
    'Ilmu Pengathaun Sosial',
    'Matematika',
    'Pendidikan Agama Islam',
    'Pendidikan Agama Kristen',
    'Pendidikan Jasmani dan Olahraga Kebugaran',
    'Pendidikan Pancasila',
    'Seni Budaya dan Keterampilan',
    ];
    $selectedMataPelajaran = request('mata_pelajaran');
    @endphp


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
                    <div class="col-md-6 col-xl-4">
                        <label for="kelas" class="form-label fw-semibold">Kelas</label>
                        <select name="kelas" id="kelas" class="form-select" required>
                            <option value="">Pilih kelas</option>
                            <option value="10">Kelas 10</option>
                            <option value="11">Kelas 11</option>
                            <option value="12">Kelas 12</option>
                        </select>
                    </div>
                    @endif

                    <div class="col-md-{{ $isKelasRequired ? '6' : '12' }} col-xl-{{ $isKelasRequired ? '5' : '8' }}">
                        <label for="mata_pelajaran" class="form-label fw-semibold">
                            Mata Pelajaran
                        </label>

                        <div class="position-relative">
                            <input
                                type="text"
                                id="mata_pelajaran"
                                class="form-control"
                                placeholder="Pilih mata pelajaran"
                                autocomplete="off"
                                required>

                            <input
                                type="hidden"
                                name="mata_pelajaran"
                                id="mata_pelajaran_value">

                            <div id="mata_pelajaran_options"
                                class="list-group position-absolute w-100 shadow-lg rounded-2 overflow-hidden mt-1"
                                style="display: none; z-index: 1050; max-height: 220px; overflow-y: auto; background-color: #1e1e2d; border: 1px solid #2b2b40;">
                                @foreach($mataPelajaranList as $item)
                                <button type="button"
                                    class="list-group-item list-group-item-action text-start border-0 bg-transparent text-gray-400 py-3 px-4 btn-active-primary text-active-white option-item"
                                    data-value="{{ $item }}">
                                    {{ $item }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-xl-{{ $isKelasRequired ? '3' : '4' }}">
                        <div class="d-grid">
                            <label for="file-dokumen" class="btn btn-success mb-0 w-100">
                                <i class="bi bi-cloud-arrow-up me-1"></i>
                                Upload Dokumen
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

    @php
    $kelasList = ['10', '11', '12'];
    @endphp

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h3 class="card-title fw-bold mb-0">
                Daftar {{ $label }}
                <span class="badge badge-light-primary ms-2">{{ $dokumens->count() }}</span>
            </h3>

            <form action="{{ route('dokumen.kategori', ['jenis' => $jenis]) }}" method="GET" class="d-flex align-items-center gap-2 mb-0" id="filter-mata-pelajaran-form">
                <div class="position-relative">
                    <button type="button" class="btn btn-light-primary btn-sm d-flex align-items-center gap-2" id="filter-mata-pelajaran-toggle">
                        <i class="bi bi-funnel"></i>
                        <span>{{ $selectedMataPelajaran ?: 'Semua Mata Pelajaran' }}</span>
                        <i class="bi bi-chevron-down fs-8"></i>
                    </button>

                    <div id="filter-mata-pelajaran-menu" class="dropdown-menu shadow-sm border-0 mt-2 p-2" style="display: none; min-width: 240px; max-height: 270px; overflow-y: auto;">
                        <button type="button" class="btn btn-sm w-100 text-start rounded mb-1 {{ empty($selectedMataPelajaran) ? 'btn-primary' : 'btn-light' }} filter-subject-option" data-subject="">
                            Semua Mata Pelajaran
                        </button>

                        @foreach($mataPelajaranList as $item)
                        <button type="button" class="btn btn-sm w-100 text-start rounded mb-1 {{ $selectedMataPelajaran === $item ? 'btn-primary' : 'btn-light' }} filter-subject-option" data-subject="{{ $item }}">
                            {{ $item }}
                        </button>
                        @endforeach
                    </div>

                    <input type="hidden" name="mata_pelajaran" id="filter-subject-value" value="{{ $selectedMataPelajaran ?? '' }}">
                </div>

                @if($selectedMataPelajaran)
                <a href="{{ route('dokumen.kategori', ['jenis' => $jenis]) }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                </a>
                @endif
            </form>
        </div>

        <div class="card-body">
            @if($isKelasRequired)
            @foreach($kelasList as $kelas)
            @php
            $dokumenKelas = $dokumens->where('kelas', (string) $kelas);
            @endphp

            <div class="mb-7">
                <h4 class="fw-bold text-gray-800 mb-4">Kelas {{ $kelas }}</h4>

                @if($dokumenKelas->isEmpty())
                <div class="border rounded p-5 text-center text-muted">
                    Belum ada dokumen untuk kelas {{ $kelas }}.
                </div>
                @else
                @foreach($dokumenKelas as $dokumen)
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-4 border rounded p-4 mb-4">
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
                        <a href="{{ route('dokumen.lihat', $dokumen->id) }}" target="_blank" class="btn btn-sm btn-light-primary">
                            <i class="bi bi-eye me-1"></i> Lihat
                        </a>

                        <a href="{{ route('dokumen.unduh', $dokumen->id) }}" class="btn btn-sm btn-light-success">
                            <i class="bi bi-download me-1"></i> Unduh
                        </a>

                        <form action="{{ route('dokumen.destroy', $dokumen->id) }}" method="POST" class="form-hapus-dokumen">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-sm btn-light-danger">
                                <i class="bi bi-trash me-1"></i> Hapus
                            </button>
                        </form>
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
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-4 border rounded p-4 mb-4">
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
                    <a href="{{ route('dokumen.lihat', $dokumen->id) }}" target="_blank" class="btn btn-sm btn-light-primary">
                        <i class="bi bi-eye me-1"></i> Lihat
                    </a>

                    <a href="{{ route('dokumen.unduh', $dokumen->id) }}" class="btn btn-sm btn-light-success">
                        <i class="bi bi-download me-1"></i> Unduh
                    </a>

                    <form action="{{ route('dokumen.destroy', $dokumen->id) }}" method="POST" class="form-hapus-dokumen">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-sm btn-light-danger">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                    </form>
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

        // Elemen form upload
        const form = document.getElementById('form-upload-dokumen');
        const fileInput = document.getElementById('file-dokumen');
        const kelasInput = document.getElementById('kelas');

        // Elemen pencarian mata pelajaran
        const mataPelajaranInput = document.getElementById('mata_pelajaran');
        const mataPelajaranValue = document.getElementById('mata_pelajaran_value');
        const mataPelajaranOptions = document.getElementById('mata_pelajaran_options');

        if (!form || !fileInput || !mataPelajaranInput) {
            console.error('Elemen form upload dokumen tidak ditemukan.');
            return;
        }

        // Status konfirmasi upload
        let uploadConfirmed = false;
        let confirmationOpen = false;

        // Mengamankan teks sebelum ditampilkan sebagai HTML
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

        // Mengambil mata pelajaran yang sudah dipilih dari daftar
        function getMataPelajaran() {
            return mataPelajaranValue ?
                mataPelajaranValue.value :
                '';
        }

        // Validasi data sebelum upload
        function validateBeforeUpload(file) {
            const kelas = kelasInput ? kelasInput.value : '';
            const mataPelajaran = getMataPelajaran();

            // 1. Validasi kelas
            if (requiresKelas && !kelas) {
                return 'Pilih kelas terlebih dahulu.';
            }

            // 2. Validasi mata pelajaran
            if (!mataPelajaran) {
                return 'Pilih mata pelajaran dari daftar terlebih dahulu.';
            }

            // 3. Validasi format PDF
            if (!/\.pdf$/i.test(file.name)) {
                return 'File harus berformat PDF.';
            }

            // 4. Validasi ukuran maksimal 10 MB
            if (file.size > 10 * 1024 * 1024) {
                return 'Ukuran PDF maksimal 10 MB.';
            }

            return null;
        }

        // ==========================================
        // PENCARIAN DAN PEMILIHAN MATA PELAJARAN
        // ==========================================

        if (mataPelajaranInput && mataPelajaranValue && mataPelajaranOptions) {
            const options = Array.from(
                mataPelajaranOptions.querySelectorAll('[data-value]')
            );

            function filterMataPelajaran() {
                const keyword = mataPelajaranInput.value.trim().toLowerCase();

                // Jika teks berubah, pengguna harus memilih ulang
                mataPelajaranValue.value = '';

                let jumlahCocok = 0;

                options.forEach(function(option) {
                    const nama = option.dataset.value.toLowerCase();
                    const cocok = keyword !== '' && nama.includes(keyword);

                    option.style.display = cocok ? '' : 'none';

                    if (cocok) {
                            jumlahCocok++;
                        }
                });

                mataPelajaranOptions.style.display =
                    keyword !== '' && jumlahCocok > 0 ?
                    'block' :
                    'none';
            }

            // Tampilkan opsi sesuai teks yang diketik
            mataPelajaranInput.addEventListener('input', function() {
                filterMataPelajaran();

                // Jika teks diketik ulang, batalkan izin upload sebelumnya
                uploadConfirmed = false;
            });

            // Simpan mata pelajaran ketika opsi dipilih
            options.forEach(function(option) {
                option.addEventListener('click', function() {
                    const selectedValue = this.dataset.value;

                    mataPelajaranInput.value = selectedValue;
                    mataPelajaranValue.value = selectedValue;

                    mataPelajaranOptions.style.display = 'none';
                    mataPelajaranInput.setCustomValidity('');
                });
            });

            // Tutup daftar ketika pengguna mengeklik area lain
            document.addEventListener('click', function(event) {
                if (
                    !mataPelajaranInput.contains(event.target) &&
                    !mataPelajaranOptions.contains(event.target)
                ) {
                    mataPelajaranOptions.style.display = 'none';
                }
            });

            // Tampilkan daftar kembali ketika input difokuskan
            mataPelajaranInput.addEventListener('focus', function() {
                filterMataPelajaran();
            });
        }

        // ==========================================
        // KONFIRMASI DAN PROSES UPLOAD DOKUMEN
        // ==========================================

        fileInput.addEventListener('change', async function() {
            const file = this.files && this.files[0];

            if (!file) {
                return;
            }

            uploadConfirmed = false;

            // Validasi awal
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

            if (confirmationOpen) {
                return;
            }

            confirmationOpen = true;

            const kelas = kelasInput ? kelasInput.value : '';
            const mataPelajaran = getMataPelajaran();

            const detailKelas = requiresKelas ?
                '<p class="mb-2"><strong>Kelas:</strong> ' +
                escapeHtml(kelas) +
                '</p>' :
                '';

            try {
                const result = await Swal.fire({
                    title: 'Konfirmasi Upload',

                    html: '<div class="text-start">' +
                        '<p class="mb-1"><strong>Nama file:</strong></p>' +
                        '<p class="mb-3 text-break">' +
                        escapeHtml(file.name) +
                        '</p>' +
                        '<p class="mb-2"><strong>Jenis dokumen:</strong> ' +
                        escapeHtml(labelDokumen) +
                        '</p>' +
                        detailKelas +
                        '<p class="mb-2"><strong>Mata pelajaran:</strong> ' +
                        escapeHtml(mataPelajaran) +
                        '</p>' +
                        '<p class="mb-0 text-muted">Ukuran: ' +
                        (file.size / 1024 / 1024).toFixed(2) +
                        ' MB</p>' +
                        '</div>',

                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Kirim',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true
                });

                if (result.isConfirmed) {
                    // Pastikan file masih tersedia
                    if (!fileInput.files || fileInput.files[0] !== file) {
                        await Swal.fire(
                            'File berubah',
                            'Silakan pilih kembali file yang akan diunggah.',
                            'warning'
                        );

                        fileInput.value = '';
                        return;
                    }

                    // Validasi ulang sebelum pengiriman
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

                    // Kirim form melalui mekanisme normal browser
                    form.requestSubmit();
                } else {
                    // Pengguna membatalkan upload
                    fileInput.value = '';
                    uploadConfirmed = false;
                }
            } catch (error) {
                console.error('Kesalahan konfirmasi upload:', error);

                await Swal.fire(
                    'Terjadi kesalahan',
                    'Konfirmasi upload gagal ditampilkan. Silakan coba lagi.',
                    'error'
                );

                fileInput.value = '';
                uploadConfirmed = false;
            } finally {
                confirmationOpen = false;
            }
        });

        // ==========================================
        // PENGAMAN PENGIRIMAN FORM
        // ==========================================

        form.addEventListener('submit', function(event) {
            if (!uploadConfirmed) {
                event.preventDefault();
                return;
            }

            // Validasi terakhir sebelum form dikirim
            const file = fileInput.files && fileInput.files[0];
            const validationMessage = file ?
                validateBeforeUpload(file) :
                'Silakan pilih file PDF terlebih dahulu.';

            if (validationMessage) {
                event.preventDefault();
                uploadConfirmed = false;

                Swal.fire({
                    title: 'Data belum valid',
                    text: validationMessage,
                    icon: 'warning',
                    confirmButtonText: 'Mengerti'
                });

                return;
            }

            // Izin konfirmasi hanya berlaku untuk satu kali pengiriman
            uploadConfirmed = false;
        });

        // ==========================================
        // KONFIRMASI PENGHAPUSAN DOKUMEN
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
                    // Submit langsung agar tidak memicu konfirmasi berulang
                    formHapus.submit();
                }
            });
        });

        // ==========================================
        // DROPDOWN FILTER MATA PELAJARAN DOKUMEN
        // ==========================================

        const filterToggle = document.getElementById(
            'filter-mata-pelajaran-toggle'
        );

        const filterMenu = document.getElementById(
            'filter-mata-pelajaran-menu'
        );

        const filterForm = document.getElementById(
            'filter-mata-pelajaran-form'
        );

        const filterValue = document.getElementById(
            'filter-subject-value'
        );

        if (filterToggle && filterMenu) {
            filterToggle.addEventListener('click', function() {
                filterMenu.style.display =
                    filterMenu.style.display === 'block' ?
                    'none' :
                    'block';
            });

            document.querySelectorAll('.filter-subject-option').forEach(
                function(option) {
                    option.addEventListener('click', function() {
                        if (!filterForm || !filterValue) {
                            return;
                        }

                        filterValue.value = this.dataset.subject || '';
                        filterForm.submit();
                    });
                }
            );

            document.addEventListener('click', function(event) {
                if (
                    !filterToggle.contains(event.target) &&
                    !filterMenu.contains(event.target)
                ) {
                    filterMenu.style.display = 'none';
                }
            });
        }
    });
</script>
@endsection