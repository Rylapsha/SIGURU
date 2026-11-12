<div class="border rounded p-4 mb-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">
        <div class="symbol symbol-45px">
            <div class="symbol-label bg-light-danger">
                <i class="bi bi-file-earmark-pdf-fill fs-2 text-danger"></i>
            </div>
        </div>

        <div class="flex-grow-1 min-w-0">
            <div class="fw-bold text-gray-800 text-break">{{ $dokumen->nama_file }}</div>
            <div class="text-muted fs-7 mt-1">
                {{ $dokumen->mata_pelajaran ?? '-' }}
                <span class="mx-1">&bull;</span>
                @if($dokumen->jenis_dokumen === 'PROSEM')
                Semester {{ $dokumen->semester ?? '-' }}
                <span class="mx-1">&bull;</span>
                @endif
                Tahun Ajaran {{ $dokumen->tahun_ajaran ?? 'Belum ditentukan' }}
                <span class="mx-1">&bull;</span>
                PDF
                <span class="mx-1">&bull;</span>
                @if(\Illuminate\Support\Facades\Storage::disk('public')->exists($dokumen->file_path))
                {{ number_format(\Illuminate\Support\Facades\Storage::disk('public')->size($dokumen->file_path) / 1024, 1, ',', '.') }} KB
                @else
                File tidak ditemukan
                @endif
                <span class="mx-1">&bull;</span>
                Diunggah {{ $dokumen->created_at->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('dokumen.lihat', $dokumen->id) }}" target="_blank" rel="noopener" class="btn btn-sm btn-light-primary">
                <i class="bi bi-eye me-1"></i> Lihat
            </a>
            <a href="{{ route('dokumen.unduh', $dokumen->id) }}" class="btn btn-sm btn-light-success">
                <i class="bi bi-download me-1"></i> Unduh
            </a>
            <form action="{{ route('dokumen.destroy', $dokumen->id) }}" method="POST" class="form-hapus-dokumen">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-light-danger">
                    <i class="bi bi-trash me-1"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    @php
        $komentarDokumen = $komentarPerDokumen->get($dokumen->id, collect());
    @endphp

    <div class="mt-4 pt-4 border-top">
        <button class="btn btn-link text-gray-800 fw-bold px-0 d-flex align-items-center gap-2"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#komentar-dokumen-{{ $dokumen->id }}"
            aria-expanded="false"
            aria-controls="komentar-dokumen-{{ $dokumen->id }}">
            <i class="bi bi-chat-left-text me-1"></i>
            Komentar
            <i class="bi bi-chevron-down fs-7"></i>
        </button>

        <div id="komentar-dokumen-{{ $dokumen->id }}" class="collapse mt-3">
            @if($komentarDokumen->isNotEmpty())
            @foreach($komentarDokumen as $komentar)
            <div class="bg-light rounded p-3 mb-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-person-circle fs-3 text-primary"></i>
                    <div>
                        <div class="fw-semibold text-gray-800">Admin</div>
                        <small class="text-muted">{{ $komentar->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
                <div class="text-gray-700" style="white-space: pre-line; overflow-wrap: anywhere;">{{ $komentar->komentar }}</div>
            </div>
            @endforeach
            @else
            <div class="text-muted fs-7">
                <i class="bi bi-chat-left me-1"></i>
                Belum ada komentar.
            </div>
            @endif
        </div>
    </div>
</div>
