@extends('layouts.app')

@section('title', 'Profile - SIGURU')

@section('page_title', 'Profil Saya')

@section('content')

<div class="post d-flex flex-column-fluid" id="kt_post">
    <div class="container-fluid">

        {{-- ========================================= --}}
        {{-- HEADER PROFILE --}}
        {{-- ========================================= --}}
        <div class="card mt-10 mb-xl-10">

            <div class="card-body pt-9 pb-0">

                <div class="d-flex flex-wrap flex-sm-nowrap">

                    {{-- FOTO PROFILE --}}
                    <div class="me-7 mb-4">

                        <div class="position-relative">

                            {{-- Foto --}}
                            <div
                                class="symbol symbol-150px symbol-circle"
                                style="overflow: hidden;">

                                @if($guru->foto)

                                <img
                                    src="{{ asset('storage/' . $guru->foto) }}"
                                    alt="Foto Profil"
                                    id="profilePreview"
                                    style="
                                            width:150px;
                                            height:150px;
                                            object-fit:cover;
                                        ">

                                @else

                                <div
                                    class="symbol-label fs-1 fw-bold bg-light-primary text-primary"
                                    id="profilePreview"
                                    style="
                                            width:150px;
                                            height:150px;
                                        ">
                                    {{ strtoupper(substr($guru->nama_lengkap, 0, 1)) }}
                                </div>

                                @endif

                            </div>


                            {{-- TOMBOL GANTI FOTO --}}
                            <form
                                action="{{ route('profil.foto.update') }}"
                                method="POST"
                                enctype="multipart/form-data"
                                id="fotoForm">

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
                                        background: #000000;
                                        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
                                    "
                                    title="Ganti Foto">
                                    <i class="bi bi-camera-fill fs-5 text-dark"></i>
                                </label>
                                <input
                                    type="file"
                                    name="foto"
                                    id="foto"
                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                    style="display:none;">

                            </form>

                        </div>

                    </div>

                    {{-- ========================================= --}}
                    {{-- INFORMASI PROFIL --}}
                    {{-- ========================================= --}}
                    <div class="card mb-5 mb-xl-10 w-100">

                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="fw-bold m-0">
                                    Informasi Profil
                                </h3>
                            </div>
                        </div>

                        <div class="card-body py-4">

                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">

                                    <tbody class="text-gray-700 fw-semibold">

                                        {{-- Nama Lengkap --}}
                                        <tr>
                                            <td class="text-muted w-50">
                                                <i class="bi bi-person-vcard fs-5 text-gray me-2"></i>
                                                Nama Lengkap
                                            </td>
                                            <td class="text-gray-800">
                                                {{ $guru->nama_lengkap ?? '-' }}
                                            </td>
                                        </tr>

                                        {{-- NIP --}}
                                        <tr>
                                            <td class="text-muted">
                                                <i class="bi bi-card-text fs-5 text-gray me-2"></i>
                                                NIP
                                            </td>
                                            <td class="text-gray-800">
                                                {{ $guru->nip ?? '-' }}
                                            </td>
                                        </tr>

                                        {{-- Golongan --}}
                                        <tr>
                                            <td class="text-muted">
                                                <i class="bi bi-award fs-5 text-gray me-2"></i>
                                                Golongan
                                            </td>
                                            <td class="text-gray-800">
                                                {{ $guru->golongan ?? '-' }}
                                            </td>
                                        </tr>

                                        {{-- Mata Pelajaran --}}
                                        <tr>
                                            <td class="text-muted">
                                                <i class="bi bi-book fs-5 text-gray me-2"></i>
                                                Mata Pelajaran
                                            </td>
                                            <td class="text-gray-800">
                                                {{ $guru->mata_pelajaran ?? '-' }}
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="separator"></div>

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