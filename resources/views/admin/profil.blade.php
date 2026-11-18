@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div class="container-fluid">
        <div class="card dashboard-panel mt-10 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap">
                    <div class="me-7 mb-4">
                        <div class="position-relative" style="width: 150px; height: 150px;">
                            <div class="symbol symbol-150px symbol-circle" style="overflow: hidden;">
                                @if ($admin->foto)
                                <img
                                    src="{{ route('admin.profil.foto.show') }}"
                                    alt="Foto profil admin"
                                    id="adminProfilePreview"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                <div class="symbol-label fs-1 fw-bold bg-light-primary text-primary"
                                    id="adminProfilePreview"
                                    style="width: 150px; height: 150px;">
                                    {{ strtoupper(substr($admin->nama_lengkap ?: $admin->username, 0, 1)) }}
                                </div>
                                @endif
                            </div>

                            <form
                                action="{{ route('admin.profil.foto.update') }}"
                                method="POST"
                                enctype="multipart/form-data"
                                id="adminFotoForm">
                                @csrf
                                @method('PUT')

                                <label
                                    for="adminFoto"
                                    class="position-absolute d-flex align-items-center justify-content-center"
                                    style="width: 38px; height: 38px; bottom: 5px; right: 5px; cursor: pointer; border: 3px solid #fff; border-radius: 50%; background: #52a8f8; box-shadow: 0 2px 6px rgba(0,0,0,0.15);"
                                    title="Ganti Foto">
                                    <i class="bi bi-camera-fill fs-5 text-dark"></i>
                                </label>
                                <input
                                    type="file"
                                    name="foto"
                                    id="adminFoto"
                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                    style="display: none;">
                            </form>
                        </div>
                    </div>

                    <div class="card mb-5 mb-xl-10 w-100">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="fw-bold m-0">Informasi Profil</h3>
                            </div>
                        </div>

                        <div class="card-body py-4">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                    <tbody class="text-gray-700 fw-semibold">
                                        <tr>
                                            <td class="text-muted w-50">
                                                <i class="bi bi-person-vcard fs-5 text-gray me-2"></i>
                                                Nama Lengkap
                                            </td>
                                            <td class="text-gray-800">{{ $admin->guru?->nama_lengkap ?: ($admin->nama_lengkap ?: $admin->username) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <i class="bi bi-card-text fs-5 text-gray me-2"></i>
                                                NIP
                                            </td>
                                            <td>{{ $admin->nip }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <i class="bi bi-calendar-event fs-5 text-gray me-2"></i>
                                                Tanggal Lahir
                                            </td>
                                            <td>
                                                {{ ($admin->guru?->tanggal_lahir ?: $admin->tanggal_lahir)?->format('d/m/Y') ?? 'Belum diisi' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <i class="bi bi-award fs-5 text-gray me-2"></i>
                                                Golongan
                                            </td>
                                            <td>{{ $admin->guru?->golongan ?: ($admin->golongan ?: 'Tidak ada') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <i class="bi bi-book fs-5 text-gray me-2"></i>
                                                Mata Pelajaran
                                            </td>
                                            <td>{{ $admin->guru?->mata_pelajaran ?: ($admin->mata_pelajaran ?: 'Tidak ada') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <i class="bi bi-shield-lock fs-5 text-gray me-2"></i>
                                                Peran
                                            </td>
                                            <td class="text-gray-800">Administrator</td>
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('adminFotoForm');
        const input = document.getElementById('adminFoto');

        if (!form || !input) {
            return;
        }

        input.addEventListener('change', function() {
            const file = input.files && input.files[0];

            if (!file) {
                return;
            }

            if (file.size > 20 * 1024 * 1024) {
                input.value = '';
                Swal.fire({
                    title: 'Data belum valid',
                    text: 'Ukuran foto maksimal 20 MB.',
                    icon: 'warning',
                    confirmButtonText: 'Mengerti'
                });

                return;
            }

            Swal.fire({
                title: 'Mengubah foto profil',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });

            form.submit();
        });
    });
</script>
@endsection
