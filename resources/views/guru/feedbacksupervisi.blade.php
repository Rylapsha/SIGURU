@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold text-gray-900">Feedback Supervisi</h1>
            <p class="text-muted mb-0">
                Komentar dan tindak lanjut dari supervisor.
            </p>
        </div>

        <x-back-button :fallback="route('guru.supervisi')" />
    </div>

    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title fw-bold">Feedback Supervisor</h3>
        </div>
        <div class="card-body">
            <p class="mb-0" style="white-space: pre-line">{{ $supervisi->feedback_admin ?: 'Feedback supervisor belum tersedia.' }}</p>
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