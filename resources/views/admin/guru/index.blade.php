
@extends('layouts.admin')

@section('title', 'Data Guru')
@section('page_title', 'Data Guru')

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold text-gray-900">Data Guru</h3>
        </div>
    </div>

    <div class="card-body py-4">
        <div class="alert alert-primary d-flex align-items-center p-5 mb-5">
            <i class="ki-duotone ki-information-5 fs-2hx text-primary me-4">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
            <div class="d-flex flex-column">
                <span class="fw-bold">Informasi</span>
                <span>
                    Halaman ini digunakan untuk melihat data guru
                    yang terdaftar di SIGURU.
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted">
                        <th class="min-w-50px">No.</th>
                        <th class="min-w-200px">Nama Guru</th>
                        <th class="min-w-150px">NIP</th>
                        <th class="min-w-150px">Golongan</th>
                        <th class="min-w-200px">Mata Pelajaran</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-10">
                            Data guru akan ditampilkan di sini.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
