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
                        <div class="symbol symbol-150px symbol-circle" style="overflow: hidden;">
                            <div class="symbol-label fs-1 fw-bold bg-light-primary text-primary"
                                style="width: 150px; height: 150px;">
                                {{ strtoupper(substr($admin->nama_lengkap ?: $admin->username, 0, 1)) }}
                            </div>
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
                                            <td class="text-gray-800">{{ $admin->nama_lengkap ?: $admin->username }}</td>
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
                                                <i class="bi bi-award fs-5 text-gray me-2"></i>
                                                Golongan
                                            </td>
                                            <td>{{ $admin->golongan }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">
                                                <i class="bi bi-book fs-5 text-gray me-2"></i>
                                                Mata Pelajaran
                                            </td>
                                            <td>{{ $admin->mata_pelajaran }}</td>
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
