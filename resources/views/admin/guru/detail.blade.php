@extends('layouts.admin')

@section('title', 'Detail Guru')
@section('page_title', 'Detail Guru')

@section('content')
<div class="container-fluid px-0">

    {{-- HEADER HALAMAN --}}
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

    {{-- NOTIFIKASI BERHASIL --}}
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center mb-5">
            <i class="bi bi-check-circle-fill me-3 fs-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- NOTIFIKASI VALIDASI --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-5">
            <div class="fw-bold mb-2">
                Komentar gagal dikirim:
            </div>

            <ul class="mb-0 ps-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- IDENTITAS GURU --}}
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

    {{-- DOKUMEN BERDASARKAN KATEGORI --}}
    @foreach ($dokumenPerKategori as $kategori => $daftarDokumen)
        <div class="card mb-5">

            {{-- HEADER KATEGORI --}}
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

            {{-- ISI KATEGORI --}}
            <div class="card-body pt-3">

                @forelse ($daftarDokumen as $dokumen)
                    <div class="py-5 border-bottom border-gray-200">

                        {{-- INFORMASI DOKUMEN --}}
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">

                            <div class="d-flex align-items-center flex-grow-1 min-w-0">

                                <div class="symbol symbol-45px me-4 flex-shrink-0">
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

                            {{-- TOMBOL LIHAT PDF --}}
                            <div class="flex-shrink-0">
                                <a
                                    href="{{ route('admin.guru.dokumen.lihat', $dokumen->id) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-sm btn-light-primary">

                                    <i class="bi bi-eye me-1"></i>
                                    Lihat PDF
                                </a>
                            </div>

                        </div>

                        {{-- FORM KOMENTAR ADMIN --}}
                        <div class="mt-5 p-4 rounded border border-gray-200">

                            <label
                                for="komentar-{{ $dokumen->id }}"
                                class="form-label fw-bold text-gray-800">

                                <i class="bi bi-chat-left-text me-2"></i>
                                Berikan Komentar
                            </label>

                            <form
                                action="{{ route('admin.guru.dokumen.komentar.store', $dokumen->id) }}"
                                method="POST">

                                @csrf

                                <textarea
                                    id="komentar-{{ $dokumen->id }}"
                                    name="komentar"
                                    class="form-control form-control-solid mb-3"
                                    rows="3"
                                    maxlength="5000"
                                    placeholder="Tulis catatan atau masukan untuk dokumen ini..."
                                    required>{{ old('komentar') }}</textarea>

                                <div class="d-flex justify-content-end">
                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-primary">

                                        <i class="bi bi-send me-1"></i>
                                        Kirim Komentar
                                    </button>
                                </div>

                            </form>
                        </div>

                        {{-- RIWAYAT KOMENTAR --}}
                        <div class="mt-5">

                            <div class="fw-bold text-gray-800 mb-3">
                                <i class="bi bi-chat-square-text me-2"></i>
                                Riwayat Komentar
                            </div>

                            @php
                                $riwayatKomentar = $komentarPerDokumen->get(
                                    $dokumen->id,
                                    collect()
                                );
                            @endphp

                            @forelse ($riwayatKomentar as $komentar)
                                <div class="rounded border border-gray-200 p-4 mb-3">

                                    {{-- HEADER KOMENTAR --}}
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">

                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-person-circle fs-2 text-primary"></i>

                                            <div>
                                                <div class="fw-semibold text-gray-800">
                                                    Admin
                                                </div>

                                                <div class="text-muted fs-8">
                                                    {{ $komentar->created_at?->format('d/m/Y H:i') ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- TOMBOL HAPUS KOMENTAR --}}
                                        <form
                                            action="{{ route('admin.guru.dokumen.komentar.destroy', $komentar->id) }}"
                                            method="POST"
                                            class="form-hapus-komentar">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-light-danger">

                                                <i class="bi bi-trash me-1"></i>
                                                Hapus
                                            </button>

                                        </form>

                                    </div>

                                    {{-- ISI KOMENTAR --}}
                                    <div
                                        class="text-gray-700"
                                        style="white-space: pre-line; overflow-wrap: anywhere;">{{ $komentar->komentar }}</div>

                                </div>

                            @empty
                                <div class="text-muted fs-7 py-2">
                                    <i class="bi bi-chat-left me-1"></i>
                                    Belum ada komentar untuk dokumen ini.
                                </div>
                            @endforelse

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

{{-- POPUP KONFIRMASI HAPUS KOMENTAR --}}
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.form-hapus-komentar').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            Swal.fire({
                title: 'Hapus Komentar?',
                text: 'Komentar yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-light ms-3'
                },
                reverseButtons: true,
                focusCancel: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
