@extends('layouts.admin')

@section('title', 'Detail Guru')
@section('page_title', 'Detail Guru')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-5">
        <a href="{{ route('admin.guru.index') }}"
           class="btn btn-light mb-5">
            <i class="bi bi-arrow-left me-2"></i>
            Kembali
        </a>

        <h2 class="fw-bold text-gray-900 mb-2">
            Detail Kelengkapan Dokumen
        </h2>
        <div class="text-muted">
            Lihat dokumen pembelajaran yang telah diunggah oleh guru.
        </div>
    </div>

    {{-- Identitas guru --}}
    <div class="card mb-6">
        <div class="card-body p-6">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-65px me-5">
                    @if ($guru->foto)
                        <img
                            src="{{ asset('storage/' . $guru->foto) }}"
                            alt="Foto {{ $guru->nama_lengkap }}"
                            class="object-fit-cover">
                    @else
                        <div class="symbol-label bg-light-primary text-primary fw-bold fs-2">
                            {{ strtoupper(substr($guru->nama_lengkap, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="flex-grow-1">
                    <h3 class="fw-bold text-gray-900 mb-2">
                        {{ $guru->nama_lengkap }}
                    </h3>
                    <div class="text-muted mb-1">
                        NIP: {{ $guru->nip }}
                    </div>
                    <div class="text-muted">
                        {{ $guru->mata_pelajaran ?: 'Mata pelajaran belum diisi' }}
                        @if ($guru->golongan)
                            <span class="mx-2">•</span>
                            Golongan {{ $guru->golongan }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dokumen berdasarkan kategori --}}
    @foreach ($dokumenPerKategori as $kategori => $daftarDokumen)
        <div class="card mb-5">
            <div class="card-header border-0 pt-5 pb-2">
                <div class="card-title d-flex flex-column">
                    <h3 class="fw-bold text-gray-900 mb-1">
                        {{ $kategori }}
                    </h3>
                    <span class="text-muted fs-7">
                        {{ $daftarDokumen->count() }} file tersedia
                    </span>
                </div>

                <div class="card-toolbar">
                    @if ($daftarDokumen->isNotEmpty())
                        <span class="badge badge-light-success">
                            Tersedia
                        </span>
                    @else
                        <span class="badge badge-light-danger">
                            Belum ada dokumen
                        </span>
                    @endif
                </div>
            </div>

            <div class="card-body pt-3">
                @forelse ($daftarDokumen as $dokumen)
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4 py-4 border-bottom border-gray-200">

                        <div class="d-flex align-items-center flex-grow-1 min-w-0">
                            <div class="symbol symbol-45px me-4">
                                <div class="symbol-label bg-light-danger">
                                    <i class="bi bi-file-earmark-pdf-fill text-danger fs-2"></i>
                                </div>
                            </div>

                            <div class="min-w-0">
                                <div class="fw-bold text-gray-800 text-break mb-1">
                                    {{ $dokumen->nama_file }}
                                </div>

                                <div class="text-muted fs-7">
                                    {{ $dokumen->jenis_dokumen }}
                                    @if ($dokumen->mata_pelajaran)
                                        <span class="mx-1">•</span>
                                        {{ $dokumen->mata_pelajaran }}
                                    @endif
                                </div>

                                <div class="text-muted fs-8 mt-1">
                                    @if ($dokumen->kelas)
                                        Kelas {{ $dokumen->kelas }}
                                        <span class="mx-1">•</span>
                                    @endif

                                    @if ($dokumen->semester)
                                        Semester {{ $dokumen->semester }}
                                        <span class="mx-1">•</span>
                                    @endif

                                    Diunggah:
                                    {{ $dokumen->created_at?->format('d/m/Y H:i') ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="flex-shrink-0">
                            <a href="{{ route('admin.guru.dokumen.lihat', $dokumen->id) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="btn btn-sm btn-light-primary">
                                <i class="bi bi-eye me-1"></i>
                                Lihat PDF
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="bi bi-folder2-open fs-2x text-muted mb-3"></i>
                        <div class="text-muted">
                            Belum ada dokumen pada kategori ini.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    @endforeach

</div>
@endsection
