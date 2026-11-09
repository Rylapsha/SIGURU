@extends('layouts.admin')

@section('title', 'Tambah Jadwal Supervisi')
@section('page_title', 'Tambah Jadwal Supervisi')

@section('content')
<div class="container-fluid px-0">

    @if ($errors->any())
    <div class="alert alert-danger mb-5">
        <div class="fw-semibold mb-2">Periksa kembali input Anda:</div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <div class="card-body p-5">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">
                <div>
                    <h2 class="fw-bold text-gray-900 mb-1">Tambah Jadwal Supervisi</h2>
                    <div class="text-muted">Isi informasi jadwal supervisi yang akan dilaksanakan.</div>
                </div>

                <a href="{{ route('admin.supervisi.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-2"></i>
                    Kembali
                </a>
            </div>

            <form action="{{ route('admin.supervisi.store') }}" method="POST">
                @csrf

                <div class="row g-5">
                    <div class="col-md-6">
                        <label for="guru_id" class="form-label fw-semibold">Nama Guru</label>
                        <select name="guru_id" id="guru_id" class="form-select form-select-solid" required>
                            <option value="">Pilih guru</option>

                            @foreach($guru as $item)
                            <option value="{{ $item->id }}"
                                data-mapel="{{ $item->mata_pelajaran }}"
                                @selected(old('guru_id')==$item->id)>
                                {{ $item->nama_lengkap }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="nama_supervisor" class="form-label fw-semibold">Nama Supervisor</label>
                        <input type="text"
                            name="nama_supervisor"
                            id="nama_supervisor"
                            class="form-control form-control-solid"
                            value="{{ old('nama_supervisor') }}"
                            placeholder="Nama supervisor"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal" class="form-label fw-semibold">Tanggal</label>
                        <input type="date"
                            name="tanggal"
                            id="tanggal"
                            class="form-control form-control-solid"
                            value="{{ old('tanggal') }}"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label for="jam" class="form-label fw-semibold">Jam</label>
                        <input type="time"
                            name="jam"
                            id="jam"
                            class="form-control form-control-solid"
                            value="{{ old('jam') }}"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label for="mata_pelajaran" class="form-label fw-semibold">Mata Pelajaran</label>
                        <input type="text"
                            name="mata_pelajaran"
                            id="mata_pelajaran"
                            class="form-control form-control-solid"
                            value="{{ old('mata_pelajaran') }}"
                            placeholder="Mata pelajaran"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label for="semester" class="form-label fw-semibold">Semester</label>
                        <select name="semester" id="semester" class="form-select form-select-solid" required>
                            <option value="">Pilih semester</option>
                            <option value="Ganjil" @selected(old('semester')==='Ganjil' )>Ganjil</option>
                            <option value="Genap" @selected(old('semester')==='Genap' )>Genap</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan"
                            id="keterangan"
                            rows="4"
                            class="form-control form-control-solid"
                            placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-8">
                    <a href="{{ route('admin.supervisi.index') }}" class="btn btn-light">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-check me-2"></i>
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const guruSelect = document.getElementById('guru_id');
        const mapelInput = document.getElementById('mata_pelajaran');

        if (guruSelect && mapelInput) {
            guruSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                if (selected && selected.dataset.mapel) {
                    mapelInput.value = selected.dataset.mapel;
                }
            });
        }
    });
</script>
@endsection