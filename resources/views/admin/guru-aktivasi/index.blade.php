@extends('layouts.admin')

@section('title', 'Aktivasi Akun Guru - SIGURU')
@section('page_title', 'Aktivasi Akun Guru')

@section('content')
<section class="card dashboard-panel">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h2 class="card-title m-5">Status Aktivasi Akun Guru</h2>
        </div>

        <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 ms-auto">
            <form method="GET" action="{{ route('admin.aktivasi-guru.index') }}" class="m-0">
                <label for="activation-guru-search" class="visually-hidden">Cari nama atau NIP guru</label>
                <div class="input-group" style="width: 320px; max-width: 100%;">
                    <input id="activation-guru-search" type="search" name="search" value="{{ $search }}"
                        class="form-control form-control-solid" placeholder="Cari nama atau NIP guru..."
                        aria-label="Cari nama atau NIP guru">
                    <button type="submit" class="btn btn-light-primary" aria-label="Cari guru" title="Cari guru">
                        <i class="bi bi-search"></i>
                    </button>
                    @if ($search !== '')
                    <a href="{{ route('admin.aktivasi-guru.index') }}" class="btn btn-light" aria-label="Reset pencarian" title="Reset pencarian">
                        <i class="bi bi-x-lg"></i>
                    </a>
                    @endif
                </div>
            </form>

            @if ($jumlahBelumAktif > 0)
            <form method="POST" action="{{ route('admin.aktivasi-guru.export') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>
                    Generate Semua & Ekspor Excel
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="card-body pt-0">
        @if (session('error'))
        <div class="alert alert-danger mb-5">{{ session('error') }}</div>
        @endif

        @if ($gurus->isEmpty())
        <div class="text-center text-muted py-10">
            {{ $search !== '' ? 'Tidak ada guru yang cocok dengan pencarian.' : 'Belum ada data guru.' }}
        </div>
        @else
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase">
                        <th>Nama Lengkap</th>
                        <th>NIP</th>
                        <th>Status Akun</th>
                        <th>Status Kode</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-700">
                    @foreach ($gurus as $guru)
                    @php
                        $adminRole = $adminRoleByNip[$guru->nip] ?? null;
                    @endphp
                    <tr>
                        <td>{{ $guru->nama_lengkap }}</td>
                        <td>{{ $guru->nip }}</td>
                        <td>
                            @if ($adminRole)
                            <span class="badge badge-light-primary">Akun Admin</span>
                            @elseif ($guru->is_active)
                            <span class="badge badge-light-success">Sudah diaktivasi</span>
                            @else
                            <span class="badge badge-light-warning">Belum diaktivasi</span>
                            @endif
                        </td>
                        <td>
                            @if ($adminRole)
                            <span class="text-muted">—</span>
                            @elseif ($guru->activation)
                            <span class="badge badge-light-success">Sudah dibuat</span>
                            <div class="text-muted fs-7 mt-1">
                                @if ($guru->activation->expires_at->isPast())
                                Kode kedaluwarsa
                                @else
                                Berlaku sampai {{ $guru->activation->expires_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                @endif
                                &middot; Percobaan {{ $guru->activation->attempts }}/5
                            </div>
                            @elseif ($guru->is_active)
                            <span class="badge badge-light-success">Sudah dibuat</span>
                            @else
                            <span class="badge badge-light-secondary">Belum dibuat</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if ($adminRole)
                            <span class="text-muted">—</span>
                            @elseif ($guru->is_active)
                            <form method="POST" action="{{ route('admin.aktivasi-guru.deactivate', $guru) }}"
                                class="d-inline" data-deactivate-guru-form>
                                @csrf
                                <button type="button" class="btn btn-sm btn-light-danger" data-deactivate-guru-button>
                                    <i class="bi bi-person-x me-1"></i>Nonaktifkan Akun
                                </button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('admin.aktivasi-guru.export-one', $guru) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-light-primary">
                                    <i class="bi bi-download me-1"></i>
                                    @if ($guru->activation && $guru->activation->expires_at->isPast())
                                    Reset Kode & Ekspor
                                    @elseif ($guru->activation)
                                    Buat Ulang & Ekspor
                                    @else
                                    Buat Kode & Ekspor
                                    @endif
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column align-items-center mt-5">
            @include('partials.table-pagination', ['paginator' => $gurus])
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-deactivate-guru-button]').forEach((button) => {
        button.addEventListener('click', () => {
            const form = button.closest('[data-deactivate-guru-form]');

            if (typeof window.Swal === 'undefined') {
                form.submit();
                return;
            }

            window.Swal.fire({
                title: 'Nonaktifkan akun guru?',
                text: 'Guru tidak dapat login sampai akun diaktifkan kembali. Dokumen dan data guru tetap tersimpan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, nonaktifkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
