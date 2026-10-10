@extends('layouts.app')

@section('page_title', 'Dokumen Modul Ajar')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="card mt-5 mb-7">
        <div class="card-body py-6">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <i class="bi bi-file-earmark-pdf-fill fs-2x text-primary"></i>
                    </div>
                </div>

                <div class="flex-grow-1">
                    <h2 class="fw-bold text-gray-900 mb-1">Dokumen Modul Ajar</h2>
                    <span class="text-muted fs-6">
                        Kelola, lihat, unduh, dan unggah dokumen Modul Ajar.
                    </span>
                </div>

                <a href="{{ route('dokumen.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Notifikasi --}}
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

    {{-- Filter tanggal --}}
    <div class="card mb-7">
        <div class="card-header">
            <h3 class="card-title fw-bold">Filter Tanggal Upload</h3>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('dokumen.modul-ajar') }}">
                <div class="row g-4 align-items-end">
                    <div class="col-md-6 col-xl-5">
                        <label for="date_range" class="form-label fw-semibold">
                            Rentang tanggal
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-calendar3"></i>
                            </span>
                            <input
                                type="text"
                                id="date_range"
                                class="form-control"
                                placeholder="Pilih rentang tanggal"
                                autocomplete="off"
                                readonly>
                        </div>

                        <input
                            type="hidden"
                            name="start_date"
                            id="start_date"
                            value="{{ request('start_date') }}">

                        <input
                            type="hidden"
                            name="end_date"
                            id="end_date"
                            value="{{ request('end_date') }}">

                        <div class="form-text">
                            Filter menggunakan tanggal dokumen diunggah.
                        </div>
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i> Terapkan
                        </button>

                        <a
                            href="{{ route('dokumen.modul-ajar') }}"
                            class="btn btn-light ms-1">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Upload --}}
    <div class="card mb-7">
        <div class="card-body">
            <form
                action="{{ route('dokumen.store', ['jenis' => 'Modul Ajar']) }}"
                method="POST"
                enctype="multipart/form-data"
                id="form-upload-modul">
                @csrf

                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <h4 class="fw-bold mb-1">Upload Modul Ajar</h4>
                        <div class="text-muted fs-7">
                            Format PDF, maksimal 10 MB per file. File lama tidak akan ditimpa.
                        </div>
                    </div>

                    <label class="btn btn-primary mb-0">
                        <i class="bi bi-cloud-arrow-up me-1"></i>
                        Pilih PDF
                        <input
                            type="file"
                            name="file"
                            id="file-modul"
                            accept=".pdf,application/pdf"
                            class="d-none"
                            required>
                    </label>
                </div>
            </form>
        </div>
    </div>

    {{-- Daftar file --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title fw-bold">
                Daftar Modul Ajar
                <span class="badge badge-light-primary ms-2">{{ $dokumens->count() }}</span>
            </h3>
        </div>

        <div class="card-body">
            @forelse($dokumens as $dokumen)
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
            @empty
            <div class="text-center py-10">
                <i class="bi bi-folder2-open fs-3x text-muted"></i>
                <h4 class="fw-bold mt-4">Belum ada dokumen</h4>
                <p class="text-muted mb-0">
                    @if(request('start_date') || request('end_date'))
                    Tidak ada dokumen pada rentang tanggal yang dipilih.
                    Coba ubah tanggal atau tekan Reset.
                    @else
                    Silakan unggah file Modul Ajar pertama kamu.
                    @endif
                </p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Date Range Picker --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = $('#date_range');

        moment.locale('id');

        const startValue = $('#start_date').val();
        const endValue = $('#end_date').val();

        const options = {
            autoUpdateInput: false,
            opens: 'right',
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Terapkan',
                cancelLabel: 'Batal',
                fromLabel: 'Dari',
                toLabel: 'Sampai',
                customRangeLabel: 'Rentang khusus',
                weekLabel: 'Mg',
                daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: [
                    'Januari', 'Februari', 'Maret', 'April',
                    'Mei', 'Juni', 'Juli', 'Agustus',
                    'September', 'Oktober', 'November', 'Desember'
                ],
                firstDay: 1
            }
        };

        if (startValue && endValue) {
            options.startDate = moment(startValue, 'YYYY-MM-DD');
            options.endDate = moment(endValue, 'YYYY-MM-DD');
            dateInput.val(
                options.startDate.format('DD/MM/YYYY') +
                ' - ' +
                options.endDate.format('DD/MM/YYYY')
            );
        }

        dateInput.daterangepicker(options);

        dateInput.on('apply.daterangepicker', function(event, picker) {
            $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
            $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));

            $(this).val(
                picker.startDate.format('DD/MM/YYYY') +
                ' - ' +
                picker.endDate.format('DD/MM/YYYY')
            );
        });

        dateInput.on('cancel.daterangepicker', function() {
            $(this).val('');
            $('#start_date').val('');
            $('#end_date').val('');
        });

        // Konfirmasi upload
        $('#file-modul').on('change', function() {
            const form = document.getElementById('form-upload-modul');

            if (this.files.length > 0) {
                const file = this.files[0];

                if (file.size > 10 * 1024 * 1024) {
                    Swal.fire('Ukuran terlalu besar', 'Ukuran PDF maksimal 10 MB.', 'error');
                    this.value = '';
                    return;
                }

                Swal.fire({
                    title: 'Upload Modul Ajar?',
                    text: file.name,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Upload',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    } else {
                        document.getElementById('file-modul').value = '';
                    }
                });
            }
        });

        // Konfirmasi hapus
        document.querySelectorAll('.form-hapus-dokumen').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Hapus dokumen?',
                    text: 'File yang dihapus tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection