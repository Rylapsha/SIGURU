@extends('layouts.app')

@section('page_title', 'Dokumen')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="card mt-10 mb-xl-10">
        <div class="card-body py-6">

            <div class="d-flex align-items-center">

                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <i class="bi bi-folder-fill fs-2x text-primary"></i>
                        <span class="path1"></span>
                        <span class="path2"></span>
                        </i>
                    </div>
                </div>

                <div>
                    <h2 class="fw-bold text-gray-900 mb-1">
                        Dokumen Guru
                    </h2>

                    <span class="text-muted fs-6">
                        Upload dan kelola dokumen pembelajaran Anda.
                    </span>
                </div>

            </div>

        </div>
    </div>


    {{-- Notifikasi --}}
    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center mb-7">

        <i class="ki-duotone ki-check-circle fs-2x me-4">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>

        <div>
            {{ session('success') }}
        </div>

    </div>
    @endif


    @if($errors->any())
    <div class="alert alert-danger mb-7">

        <div class="fw-bold mb-2">
            Upload gagal:
        </div>

        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>

    </div>
    @endif


    {{-- Daftar Dokumen --}}
    <div class="row g-6">

        @foreach($jenisDokumen as $jenis)

        @php
        $dokumen = $dokumens->get($jenis);
        @endphp

        <div class="col-md-6 col-xl-4">

            <div class="card h-100">

                <div class="card-body d-flex flex-column">

                    {{-- Icon --}}
                    <div class="d-flex align-items-center mb-5">

                        <div class="symbol symbol-50px me-4">
                            <div class="symbol-label bg-light-danger">
                                <i class="bi bi-file-earmark-pdf-fill fs-2x text-danger"></i>
                            </div>
                        </div>

                        <div>
                            <h3 class="fw-bold text-gray-900 mb-1">
                                {{ $jenis }}
                            </h3>

                            @if($dokumen)
                            <span class="badge badge-light-success">
                                Sudah Upload
                            </span>
                            @else
                            <span class="badge badge-light-danger">
                                Belum Upload
                            </span>
                            @endif
                        </div>

                    </div>


                    {{-- Keterangan --}}
                    <div class="mb-6 flex-grow-1">

                        @if($jenis === 'Bank Soal')

                        <p class="text-muted fs-7 mb-0">
                            Dokumen bank soal dapat berisi
                            kisi-kisi, naskah soal, dan analisis
                            butir soal.
                        </p>

                        @else

                        <p class="text-muted fs-7 mb-0">
                            Upload dokumen {{ $jenis }}
                            dalam format PDF.
                        </p>

                        @endif

                    </div>


                    {{-- Jika sudah upload --}}
                    @if($dokumen)

                    <div class="border rounded p-4 mb-5">

                        <div class="d-flex align-items-center">

                            <i class="ki-duotone ki-file-added fs-2x text-danger me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>

                            <div class="min-w-0">

                                <div class="fw-semibold text-gray-800 text-truncate">
                                    {{ $dokumen->nama_file }}
                                </div>

                                <div class="text-muted fs-8">
                                    {{ $dokumen->created_at->format('d M Y H:i') }}
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Tombol --}}
                    <div class="d-flex gap-2">

                        <a
                            href="{{ route('dokumen.lihat', $dokumen->id) }}"
                            target="_blank"
                            class="btn btn-light-primary flex-grow-1">
                            <i class="ki-duotone ki-eye fs-4 me-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>

                            Lihat
                        </a>


                        <form
                            action="{{ route('dokumen.destroy', $dokumen->id) }}"
                            method="POST"
                            class="form-hapus-dokumen">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-light-danger"
                                title="Hapus">
                                <i class="bi bi-trash-fill p-0">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </button>

                        </form>

                    </div>

                    @else

                    {{-- Upload --}}
                    <form
                        action="{{ route('dokumen.store', ['jenis' => $jenis]) }}"
                        method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        <label class="btn btn-primary w-100">

                            <i class="ki-duotone ki-cloud-add fs-4 me-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>

                            Upload PDF

                            <input
                                type="file"
                                name="file"
                                accept="application/pdf"
                                class="d-none input-upload-dokumen"
                                required>

                        </label>

                    </form>

                    @endif

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>


{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Otomatis submit ketika PDF dipilih
        document.querySelectorAll('.input-upload-dokumen').forEach(function(input) {

            input.addEventListener('change', function() {

                if (this.files.length > 0) {

                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Upload dokumen?',
                        text: 'File PDF akan di-upload ke sistem.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Upload',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-primary ms-2',
                            cancelButton: 'btn btn-light'
                        }
                    }).then((result) => {

                        if (result.isConfirmed) {
                            form.submit();
                        } else {
                            input.value = '';
                        }

                    });

                }

            });

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
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-danger ms-2',
                        cancelButton: 'btn btn-light'
                    }
                }).then((result) => {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

        });

    });
</script>

@endsection