
@extends('layouts.admin')

@section('title', 'Jadwal Supervisi')
@section('page_title', 'Jadwal Supervisi')

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold text-gray-900">Jadwal Supervisi Guru</h3>
        </div>

        <div class="card-toolbar">
            <button type="button" class="btn btn-primary" disabled>
                <i class="ki-duotone ki-plus fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Tambah Jadwal
            </button>
        </div>
    </div>

    <div class="card-body py-4">
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted">
                        <th>No.</th>
                        <th>Nama Guru</th>
                        <th>Supervisor</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-10">
                            Jadwal supervisi akan ditampilkan di sini.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
