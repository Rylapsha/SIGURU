@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold text-gray-900">Detail Supervisi</h1>
            <p class="text-muted mb-0">
                Informasi lengkap jadwal dan hasil supervisi.
            </p>
        </div>

        <a href="{{ route('guru.supervisi') }}"
           class="btn btn-light">
            Kembali
        </a>
    </div>

    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title fw-bold">Informasi Jadwal</h3>
        </div>

        <div class="card-body">
            <div class="row g-5">
                <div class="col-md-6">
                    <label class="text-muted fs-7">Nama Supervisor</label>
                    <div class="fw-semibold mt-1">
                        {{ $supervisi->nama_supervisor ?: '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="text-muted fs-7">Tanggal</label>
                    <div class="fw-semibold mt-1">
                        {{ $supervisi->tanggal
                            ? $supervisi->tanggal->format('d F Y')
                            : '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="text-muted fs-7">Jam</label>
                    <div class="fw-semibold mt-1">
                        {{ $supervisi->jam
                            ? substr($supervisi->jam, 0, 5)
                            : '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="text-muted fs-7">Mata Pelajaran</label>
                    <div class="fw-semibold mt-1">
                        {{ $supervisi->mata_pelajaran ?: '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="text-muted fs-7">Semester</label>
                    <div class="fw-semibold mt-1">
                        {{ $supervisi->semester ?: '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="text-muted fs-7">Status</label>
                    <div class="mt-1">
                        <span class="badge badge-light-primary">
                            {{ ucfirst(str_replace(
                                '_',
                                ' ',
                                $supervisi->status ?? 'Belum ada status'
                            )) }}
                        </span>
                    </div>
                </div>

                <div class="col-12">
                    <label class="text-muted fs-7">Keterangan</label>
                    <div class="mt-1">
                        {{ $supervisi->keterangan ?: 'Belum ada keterangan.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title fw-bold">Hasil Observasi</h3>
        </div>
        <div class="card-body">
            <p class="mb-0" style="white-space: pre-line">{{ $supervisi->hasil_observasi ?: 'Hasil observasi belum tersedia.' }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title fw-bold">Tindak Lanjut</h3>
        </div>
        <div class="card-body">
            <p class="mb-0" style="white-space: pre-line">{{ $supervisi->tindak_lanjut ?: 'Tindak lanjut belum tersedia.' }}</p>
        </div>
    </div>

</div>
@endsection
