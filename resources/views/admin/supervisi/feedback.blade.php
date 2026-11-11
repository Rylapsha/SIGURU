@extends('layouts.admin')

@section('title', 'Feedback Supervisi')
@section('page_title', 'Feedback Supervisi')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-6">
        <div>
            <h2 class="fw-bold text-gray-900 mb-1">Feedback Supervisi</h2>
            <div class="text-muted fs-6">Isi feedback, tindak lanjut, dan predikat untuk supervisi ini.</div>
        </div>

        <x-back-button :fallback="route('admin.supervisi.index')" />
    </div>

    <div class="row g-6">
        <div class="col-xl-6">
            <div class="card dashboard-panel h-100">
                <div class="card-header border-0 pb-3">
                    <h3 class="card-title fw-bold text-gray-800 mb-0">Tindak Lanjut</h3>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('admin.supervisi.hasil', $item->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Catatan Tindak Lanjut</label>
                            <textarea name="tindak_lanjut" rows="8" class="form-control" placeholder="Tuliskan tindak lanjut...">{{ old('tindak_lanjut', $item->tindak_lanjut) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan Tindak Lanjut</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card dashboard-panel h-100">
                <div class="card-header border-0 pb-3">
                    <h3 class="card-title fw-bold text-gray-800 mb-0">Feedback & Predikat</h3>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('admin.supervisi.penilaian', $item->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Feedback & Komentar</label>
                            <textarea name="feedback_admin" rows="8" class="form-control" placeholder="Tuliskan feedback untuk guru...">{{ old('feedback_admin', $item->feedback_admin) }}</textarea>
                            <div class="form-text text-muted">Feedback ini dapat ditampilkan ke guru sesuai kebutuhan.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Predikat & Skor Arsip</label>
                            <select name="predikat" class="form-select mb-2">
                                <option value="" {{ old('predikat', $item->predikat) === null || old('predikat', $item->predikat) === '' ? 'selected' : '' }}>Pilih predikat</option>
                                <option value="Kurang" {{ old('predikat', $item->predikat) === 'Kurang' ? 'selected' : '' }}>Kurang</option>
                                <option value="Cukup" {{ old('predikat', $item->predikat) === 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                <option value="Baik" {{ old('predikat', $item->predikat) === 'Baik' ? 'selected' : '' }}>Baik</option>
                                <option value="Sangat Baik" {{ old('predikat', $item->predikat) === 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                            </select>
                            <div class="border rounded bg-light p-3 text-muted small mb-0">
                                Skor predikat:
                                <strong class="text-gray-800">
                                    {{ $item->skor ?? '-' }}
                                </strong>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan Feedback</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection