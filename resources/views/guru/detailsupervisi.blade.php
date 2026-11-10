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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold">Surat Pemberitahuan</h3>
            @if ($supervisi->surat_pemberitahuan_path)
            <a href="{{ route('guru.supervisi.surat', $supervisi->id) }}" class="btn btn-sm btn-primary" target="_blank" rel="noopener">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>
                Lihat Surat
            </a>
            @endif
        </div>
        <div class="card-body">
            @if ($supervisi->surat_pemberitahuan_path)
            <div class="d-flex align-items-center justify-content-between gap-3 p-3 border rounded bg-light">
                <div>
                    <div class="fw-semibold text-gray-800">{{ $supervisi->nama_surat_pemberitahuan ?: 'Surat pemberitahuan supervisi' }}</div>
                    <div class="text-muted fs-7">File PDF telah dilampirkan oleh admin.</div>
                </div>
                <a href="{{ route('guru.supervisi.surat', $supervisi->id) }}" class="btn btn-light-primary" target="_blank" rel="noopener">
                    <i class="bi bi-download me-2"></i>
                    Buka
                </a>
            </div>
            @else
            <p class="mb-0 text-muted">Surat pemberitahuan belum tersedia.</p>
            @endif
        </div>
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