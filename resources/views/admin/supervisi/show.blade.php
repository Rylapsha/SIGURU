@extends('layouts.admin')

@section('title', 'Detail Supervisi')
@section('page_title', 'Detail Supervisi')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-5">
        <x-back-button :fallback="route('admin.supervisi.index')" />
    </div>

    <div class="mb-6">
        <div>
            <h2 class="fw-bold text-gray-900 mb-1">Detail Supervisi</h2>
            <div class="text-muted fs-6">Catatan tindak lanjut dan feedback supervisor.</div>
        </div>
    </div>

    <div class="card dashboard-panel mb-6">
        <div class="card-body p-5">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="border rounded-3 bg-light p-4 h-100">
                        <div class="text-muted fs-7 mb-2">Guru</div>
                        <div class="fw-semibold text-gray-800">{{ $item->nama_lengkap ?? 'Guru tidak ditemukan' }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 bg-light p-4 h-100">
                        <div class="text-muted fs-7 mb-2">Mata Pelajaran</div>
                        <div class="fw-semibold text-gray-800">{{ $item->mata_pelajaran ?: '-' }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 bg-light p-4 h-100">
                        <div class="text-muted fs-7 mb-2">Tanggal</div>
                        <div class="fw-semibold text-gray-800">{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d M Y') }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 bg-light p-4 h-100">
                        <div class="text-muted fs-7 mb-2">Semester</div>
                        <div class="fw-semibold text-gray-800">{{ $item->semester ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-6">
        <div class="col-12">
            <div class="card dashboard-panel h-100">
                <div class="card-header border-0 pb-3">
                    <h3 class="card-title fw-bold text-gray-800 mb-0">Skor Predikat</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="border rounded bg-light p-4 text-center">
                        <div class="display-6 fw-bold text-gray-900">{{ $item->skor ?? '-' }}</div>
                        <div class="text-muted mt-2">Skor arsip supervisi</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
