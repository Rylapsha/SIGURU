@extends('layouts.app')

@section('title', 'Profile - SIGURU')

@section('page_title', 'Profile')

@section('content')

<div class="post d-flex flex-column-fluid" id="kt_post">
    <div class="container-fluid">

        {{-- ========================================= --}}
        {{-- HEADER PROFILE --}}
        {{-- ========================================= --}}
        <div class="card mb-5 mb-xl-10">

            <div class="card-body pt-9 pb-0">

                <div class="d-flex flex-wrap flex-sm-nowrap">

                    {{-- FOTO PROFILE --}}
                    <div class="me-7 mb-4">

                        <div class="position-relative">

                            {{-- Foto --}}
                            <div
                                class="symbol symbol-150px symbol-circle"
                                style="overflow: hidden;"
                            >

                                @if($guru->foto)

                                    <img
                                        src="{{ asset('storage/' . $guru->foto) }}"
                                        alt="Foto Profil"
                                        id="profilePreview"
                                        style="
                                            width:150px;
                                            height:150px;
                                            object-fit:cover;
                                        "
                                    >

                                @else

                                    <div
                                        class="symbol-label fs-1 fw-bold bg-light-primary text-primary"
                                        id="profilePreview"
                                        style="
                                            width:150px;
                                            height:150px;
                                        "
                                    >
                                        {{ strtoupper(substr($guru->nama_lengkap, 0, 1)) }}
                                    </div>

                                @endif

                            </div>


                            {{-- TOMBOL GANTI FOTO --}}
                            <form
                                action="{{ route('profil.foto.update') }}"
                                method="POST"
                                enctype="multipart/form-data"
                                id="fotoForm"
                            >

                                @csrf
                                @method('PUT')

                               <label
                                    for="foto"
                                    class="position-absolute d-flex align-items-center justify-content-center"
                                    style="
                                        width: 38px;
                                        height: 38px;
                                        bottom: 5px;
                                        right: 5px;
                                        cursor: pointer;
                                        border: 3px solid #fff;
                                        border-radius: 50%;
                                        background: #fff;
                                        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
                                    "
                                    title="Ganti Foto"
                                >
                                    <i class="bi bi-camera-fill fs-5 text-dark"></i>
                                </label>
                                <input
                                    type="file"
                                    name="foto"
                                    id="foto"
                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                    style="display:none;"
                                >

                            </form>

                        </div>

                    </div>


                    {{-- ========================================= --}}
                    {{-- INFORMASI SINGKAT GURU --}}
                    {{-- ========================================= --}}
                    <div class="flex-grow-1">

                        <div class="d-flex flex-column">

                            {{-- Nama --}}
                            <div class="d-flex align-items-center mb-3">

                                <span class="text-gray-900 fs-2 fw-bold">
                                    {{ $guru->nama_lengkap }}
                                </span>

                            </div>


                            {{-- NIP + Mata Pelajaran --}}
                            <div class="d-flex flex-wrap fw-semibold fs-6">

                                @if($guru->nip)

                                    <span class="d-flex align-items-center text-gray-500 me-7 mb-2">

                                        <i class="ki-duotone ki-profile-circle fs-4 me-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>

                                        NIP: {{ $guru->nip }}

                                    </span>

                                @endif


                                @if($guru->mata_pelajaran)

                                    <span class="d-flex align-items-center text-gray-500 me-7 mb-2">

                                        <i class="ki-duotone ki-book fs-4 me-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>

                                        {{ $guru->mata_pelajaran }}

                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

                <div class="separator"></div>

            </div>

        </div>


        {{-- ========================================= --}}
        {{-- INFORMASI PROFILE --}}
        {{-- ========================================= --}}
        <div class="card mb-5 mb-xl-10">

            <div class="card-header">

                <div class="card-title">

                    <h3 class="fw-bold m-0">
                        Informasi Profil
                    </h3>

                </div>

            </div>


            <div class="card-body">

                {{-- BARIS PERTAMA --}}
                <div class="row mb-10">

                    {{-- Nama Lengkap --}}
                    <div class="col-md-6 mb-7 mb-md-0">

                        <div class="fw-semibold text-muted mb-2">
                            Nama Lengkap
                        </div>

                        <div class="fw-bold fs-6 text-gray-800">
                            {{ $guru->nama_lengkap }}
                        </div>

                    </div>


                    {{-- NIP --}}
                    <div class="col-md-6">

                        <div class="fw-semibold text-muted mb-2">
                            NIP
                        </div>

                        <div class="fw-bold fs-6 text-gray-800">
                            {{ $guru->nip ?? '-' }}
                        </div>

                    </div>

                </div>


                {{-- BARIS KEDUA --}}
                <div class="row mb-7">

                    {{-- Golongan --}}
                    <div class="col-md-6 mb-7 mb-md-0">

                        <div class="fw-semibold text-muted mb-2">
                            Golongan
                        </div>

                        <div class="fw-bold fs-6 text-gray-800">
                            {{ $guru->golongan ?? '-' }}
                        </div>

                    </div>


                    {{-- Mata Pelajaran --}}
                    <div class="col-md-6">

                        <div class="fw-semibold text-muted mb-2">
                            Mata Pelajaran
                        </div>

                        <div class="fw-bold fs-6 text-gray-800">
                            {{ $guru->mata_pelajaran ?? '-' }}
                        </div>

                    </div>

                </div>


                {{-- KETERANGAN --}}
                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mt-5">

                    <i class="ki-duotone ki-information-5 fs-2tx text-primary me-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>

                    <div class="fw-semibold text-gray-700">

                        Data profil guru ditampilkan berdasarkan data yang tersimpan pada sistem SIGURU.

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>


{{-- ========================================= --}}
{{-- SCRIPT GANTI FOTO --}}
{{-- ========================================= --}}

@section('scripts')

<script>

document.getElementById('foto').addEventListener('change', function(event) {

    const file = event.target.files[0];

    if (!file) {
        return;
    }


    // Validasi ukuran maksimal 2 MB
    if (file.size > 2 * 1024 * 1024) {

        alert('Ukuran foto maksimal 2 MB.');

        event.target.value = '';

        return;
    }


    // Preview foto
    const reader = new FileReader();

    reader.onload = function(e) {

        const preview = document.getElementById('profilePreview');


        if (preview.tagName.toLowerCase() === 'img') {

            preview.src = e.target.result;

        } else {

            const img = document.createElement('img');

            img.src = e.target.result;

            img.id = 'profilePreview';

            img.alt = 'Foto Profil';

            img.style.width = '150px';
            img.style.height = '150px';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '50%';

            preview.parentNode.replaceChild(img, preview);

        }

    };


    reader.readAsDataURL(file);


    // Upload otomatis
    document.getElementById('fotoForm').submit();

});

</script>

@endsection

@endsection