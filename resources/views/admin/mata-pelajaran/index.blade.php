@extends('layouts.admin')

@section('title', 'Master Mata Pelajaran')
@section('page_title', 'Master Mata Pelajaran')

@section('content')
<div class="container-fluid px-0">

    {{-- Card Daftar Mata Pelajaran --}}
    <div class="card dashboard-panel overflow-hidden">
        <div class="card-body p-5">

            {{-- Judul --}}
            <div class="mb-4 px-2">
                <h3 class="m-5">Daftar Mata Pelajaran</h3>
            </div>

            {{-- Tombol Tambah dan Search --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5 px-2">

                {{-- Tombol Tambah --}}
                <button
                    type="button"
                    class="btn btn-light-primary dashboard-action-btn fw-semibold px-5 py-3 fs-6"
                    style="min-height: 43px;"
                    data-bs-toggle="modal"
                    data-bs-target="#tambahMapelModal">
                    <i class="bi bi-plus-lg me-2"></i>
                    Tambah Mata Pelajaran
                </button>

                {{-- Search --}}
                <form
                    action="{{ route('admin.mata-pelajaran.index') }}"
                    method="GET"
                    class="d-flex align-items-center"
                    style="width: 100%; max-width: 350px;">

                    <div class="input-group">
                        <input
                            type="search"
                            name="cari"
                            class="form-control form-control-solid"
                            placeholder="Cari mata pelajaran..."
                            value="{{ request('cari') }}">

                        <button
                            type="submit"
                            class="btn btn-light-primary"
                            aria-label="Cari mata pelajaran"
                            title="Cari mata pelajaran">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-sm table-row-bordered table-row-gray-300 align-middle gy-5">

                    <thead>
                        <tr class="fw-bold text-uppercase fs-7">
                            <th class="min-w-100px text-start">No</th>
                            <th class="min-w-250px text-start">Nama Mata Pelajaran</th>
                            <th class="min-w-150px text-start">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($mataPelajarans as $index => $mapel)
                        <tr>
                            <td class="text-start">
                                {{ $mataPelajarans->firstItem() + $index }}
                            </td>

                            <td class="text-start">
                                <span class="fw-semibold text-gray-800">
                                    {{ $mapel->nama }}
                                </span>
                            </td>

                            <td class="text-start">
                                <div class="d-flex justify-content-start gap-2">

                                    {{-- Tombol Edit --}}
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editMapelModal{{ $mapel->id }}"
                                        title="Edit mata pelajaran">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteMapelModal{{ $mapel->id }}"
                                        title="Hapus mata pelajaran">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-10 text-muted">
                                <i class="bi bi-journal-x fs-2x d-block mb-3"></i>

                                <div class="fw-semibold text-gray-800 mb-1">
                                    @if (request('cari'))
                                    Mata pelajaran tidak ditemukan
                                    @else
                                    Belum ada data mata pelajaran
                                    @endif
                                </div>

                                <div class="text-muted">
                                    @if (request('cari'))
                                    Coba gunakan kata pencarian lainnya.
                                    @else
                                    Silakan tambahkan mata pelajaran baru.
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                @include('partials.table-pagination', ['paginator' => $mataPelajarans])
            </div>
        </div>
    </div>

</div>


{{-- Modal Tambah --}}
<div
    class="modal fade"
    id="tambahMapelModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('admin.mata-pelajaran.store') }}">

                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mata Pelajaran</h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">
                    <label for="nama-mapel" class="form-label">
                        Nama Mata Pelajaran
                    </label>

                    <input
                        type="text"
                        id="nama-mapel"
                        name="nama"
                        class="form-control"
                        placeholder="Contoh: Informatika"
                        value="{{ old('nama') }}"
                        required>
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


{{-- Modal Edit dan Hapus --}}
@foreach ($mataPelajarans as $mapel)

{{-- Modal Edit --}}
<div
    class="modal fade"
    id="editMapelModal{{ $mapel->id }}"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('admin.mata-pelajaran.update', $mapel->id) }}">

                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Mata Pelajaran</h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">
                    <label
                        for="edit-nama-{{ $mapel->id }}"
                        class="form-label">
                        Nama Mata Pelajaran
                    </label>

                    <input
                        type="text"
                        id="edit-nama-{{ $mapel->id }}"
                        name="nama"
                        class="form-control"
                        value="{{ $mapel->nama }}"
                        required>
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


{{-- Modal Hapus --}}
<div
    class="modal fade"
    id="deleteMapelModal{{ $mapel->id }}"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('admin.mata-pelajaran.destroy', $mapel->id) }}">

                @csrf
                @method('DELETE')

                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        Hapus Mata Pelajaran
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus mata pelajaran
                    <strong>{{ $mapel->nama }}</strong>?
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-danger">
                        Hapus
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endforeach

@endsection
