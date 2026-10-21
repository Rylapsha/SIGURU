
@extends('layouts.admin')

@section('title', 'Jadwal Supervisi')

@section('content')
<div class="admin-supervisi">

    <div class="page-heading">
        <div>
            <h1>Jadwal Supervisi</h1>
            <p>Kelola jadwal, hasil observasi, dan komentar supervisi guru.</p>
        </div>

        <button type="button" class="btn-primary"
            onclick="document.getElementById('formTambah').scrollIntoView({behavior:'smooth'})">
            <i class="ki-outline ki-plus"></i> Tambah Jadwal
        </button>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <strong>Data belum bisa disimpan.</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <span>Terjadwal</span>
            <strong>{{ $statistik['terjadwal'] }}</strong>
            <i class="ki-outline ki-calendar"></i>
        </div>

        <div class="stat-card">
            <span>Dalam Proses</span>
            <strong>{{ $statistik['dalam_proses'] }}</strong>
            <i class="ki-outline ki-time"></i>
        </div>

        <div class="stat-card">
            <span>Selesai</span>
            <strong>{{ $statistik['selesai'] }}</strong>
            <i class="ki-outline ki-check-circle"></i>
        </div>

        <div class="stat-card">
            <span>Dibatalkan</span>
            <strong>{{ $statistik['dibatalkan'] }}</strong>
            <i class="ki-outline ki-cross-circle"></i>
        </div>
    </div>

    <section class="supervisi-panel" id="formTambah">
        <div class="section-heading">
            <div>
                <h2>Tambah Jadwal Supervisi</h2>
                <p>Isi informasi jadwal supervisi yang akan dilaksanakan.</p>
            </div>
        </div>

        <form action="{{ route('admin.supervisi.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="guru_id">Nama Guru</label>
                    <select name="guru_id" id="guru_id" required>
                        <option value="">Pilih guru</option>
                        @foreach($guru as $item)
                            <option value="{{ $item->id }}"
                                data-mapel="{{ $item->mata_pelajaran }}"
                                @selected(old('guru_id') == $item->id)>
                                {{ $item->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="nama_supervisor">Nama Supervisor</label>
                    <input type="text" name="nama_supervisor"
                        id="nama_supervisor" value="{{ old('nama_supervisor') }}"
                        placeholder="Nama supervisor" required>
                </div>

                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal"
                        value="{{ old('tanggal') }}" required>
                </div>

                <div class="form-group">
                    <label for="jam">Jam</label>
                    <input type="time" name="jam" id="jam"
                        value="{{ old('jam') }}" required>
                </div>

                <div class="form-group">
                    <label for="mata_pelajaran">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran"
                        id="mata_pelajaran" value="{{ old('mata_pelajaran') }}"
                        placeholder="Mata pelajaran" required>
                </div>

                <div class="form-group">
                    <label for="semester">Semester</label>
                    <select name="semester" id="semester" required>
                        <option value="">Pilih semester</option>
                        <option value="Ganjil" @selected(old('semester') === 'Ganjil')>Ganjil</option>
                        <option value="Genap" @selected(old('semester') === 'Genap')>Genap</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" id="keterangan"
                        rows="3" placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </section>

    <section class="supervisi-panel">
        <div class="section-heading">
            <div>
                <h2>Daftar Supervisi</h2>
                <p>Perbarui status dan kelola komentar untuk setiap jadwal.</p>
            </div>
            <span class="total-badge">{{ $jadwal->count() }} jadwal</span>
        </div>

        <div class="table-wrapper">
            <table class="supervisi-table">
                <thead>
                    <tr>
                        <th>Guru dan Mata Pelajaran</th>
                        <th>Jadwal</th>
                        <th>Supervisor</th>
                        <th>Status</th>
                        <th>Komentar Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwal as $item)
                        <tr>
                            <td>
                                <div class="teacher-name">
                                    {{ $item->nama_lengkap ?? 'Guru tidak ditemukan' }}
                                </div>
                                <div class="muted-text">{{ $item->mata_pelajaran }}</div>
                                <div class="muted-text">Semester {{ $item->semester }}</div>
                            </td>

                            <td>
                                <div class="date-text">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d M Y') }}
                                </div>
                                <div class="muted-text">
                                    {{ $item->jam ? substr((string) $item->jam, 0, 5) . ' WIB' : '-' }}
                                </div>
                            </td>

                            <td>{{ $item->nama_supervisor ?: '-' }}</td>

                            <td>
                                <form action="{{ route('admin.supervisi.status', $item->id) }}"
                                    method="POST" class="inline-form">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="status-select"
                                        aria-label="Status supervisi"
                                        onchange="this.form.submit()">
                                        <option value="terjadwal" @selected($item->status === 'terjadwal')>Terjadwal</option>
                                        <option value="dalam_proses" @selected($item->status === 'dalam_proses')>Dalam Proses</option>
                                        <option value="selesai" @selected($item->status === 'selesai')>Selesai</option>
                                        <option value="dibatalkan" @selected($item->status === 'dibatalkan')>Dibatalkan</option>
                                    </select>
                                </form>
                            </td>

                            <td class="comment-cell">
                                <form action="{{ route('admin.supervisi.komentar', $item->id) }}"
                                    method="POST" class="comment-form">
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="komentar_admin" rows="3"
                                        placeholder="Tulis komentar untuk guru...">{{ $item->komentar_admin }}</textarea>
                                    <button type="submit" class="btn-small">
                                        Simpan Komentar
                                    </button>
                                </form>

                                @if($item->komentar_admin)
                                    <span class="comment-saved">
                                        <i class="ki-outline ki-check-circle"></i>
                                        Komentar tersimpan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-row">
                                <i class="ki-outline ki-calendar"></i>
                                <strong>Belum ada jadwal supervisi</strong>
                                <span>Tambahkan jadwal melalui formulir di atas.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const guruSelect = document.getElementById('guru_id');
    const mapelInput = document.getElementById('mata_pelajaran');

    if (guruSelect && mapelInput) {
        guruSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            if (selected && selected.dataset.mapel) {
                mapelInput.value = selected.dataset.mapel;
            }
        });
    }
});
</script>

<style>
.admin-supervisi {
    --sup-bg: #fff;
    --sup-border: #e3e8f0;
    --sup-text: #17243a;
    --sup-muted: #64748b;
    padding: 26px 30px;
    color: var(--sup-text);
    font-family: Inter, sans-serif;
}

.admin-supervisi * {
    box-sizing: border-box;
}

.admin-supervisi .page-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}

.admin-supervisi .page-heading h1 {
    margin: 0 0 7px;
    color: var(--sup-text);
    font-size: 25px;
    font-weight: 750;
}

.admin-supervisi .page-heading p,
.admin-supervisi .section-heading p {
    margin: 0;
    color: var(--sup-muted);
    font-size: 12px;
    line-height: 1.7;
}

.admin-supervisi .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 22px;
}

.admin-supervisi .stat-card {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 20px;
    background: var(--sup-bg);
    border: 1px solid var(--sup-border);
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .03);
}

.admin-supervisi .stat-card span {
    color: var(--sup-muted);
    font-size: 12px;
}

.admin-supervisi .stat-card strong {
    color: var(--sup-text);
    font-size: 28px;
    line-height: 1.2;
}

.admin-supervisi .stat-card > i {
    position: absolute;
    top: 20px;
    right: 18px;
    color: #438cf5;
    font-size: 22px;
}

.admin-supervisi .supervisi-panel {
    margin-bottom: 22px;
    padding: 23px;
    background: var(--sup-bg);
    border: 1px solid var(--sup-border);
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .03);
}

.admin-supervisi .section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    margin-bottom: 22px;
}

.admin-supervisi .section-heading h2 {
    margin: 0 0 6px;
    color: var(--sup-text);
    font-size: 16px;
    font-weight: 700;
}

.admin-supervisi .total-badge {
    padding: 7px 10px;
    color: #2563eb;
    background: #e8f1ff;
    border-radius: 7px;
    font-size: 11px;
    white-space: nowrap;
}

.admin-supervisi .form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.admin-supervisi .form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}

.admin-supervisi .form-group label {
    color: var(--sup-text);
    font-size: 12px;
    font-weight: 650;
}

.admin-supervisi .form-group input,
.admin-supervisi .form-group select,
.admin-supervisi .form-group textarea,
.admin-supervisi .comment-form textarea {
    width: 100%;
    min-width: 0;
    padding: 11px 12px;
    color: var(--sup-text);
    background: var(--sup-bg);
    border: 1px solid var(--sup-border);
    border-radius: 7px;
    font: inherit;
    font-size: 12px;
    outline: none;
}

.admin-supervisi .form-group input:focus,
.admin-supervisi .form-group select:focus,
.admin-supervisi .form-group textarea:focus,
.admin-supervisi .comment-form textarea:focus {
    border-color: #438cf5;
    box-shadow: 0 0 0 3px rgba(67, 140, 245, .1);
}

.admin-supervisi .full-width {
    grid-column: 1 / -1;
}

.admin-supervisi .form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}

.admin-supervisi .btn-primary,
.admin-supervisi .btn-small {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 11px 15px;
    color: #fff;
    background: #2563b9;
    border: 0;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 650;
    text-decoration: none;
    cursor: pointer;
}

.admin-supervisi .btn-primary:hover,
.admin-supervisi .btn-small:hover {
    background: #1d4f96;
}

.admin-supervisi .table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.admin-supervisi .supervisi-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.admin-supervisi .supervisi-table th {
    padding: 13px 12px;
    color: var(--sup-muted);
    background: rgba(148, 163, 184, .08);
    border-bottom: 1px solid var(--sup-border);
    text-align: left;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    white-space: nowrap;
}

.admin-supervisi .supervisi-table td {
    min-width: 125px;
    padding: 16px 12px;
    color: var(--sup-text);
    border-bottom: 1px solid var(--sup-border);
    vertical-align: top;
}

.admin-supervisi .supervisi-table tbody tr:last-child td {
    border-bottom: 0;
}

.admin-supervisi .teacher-name {
    margin-bottom: 6px;
    font-weight: 700;
}

.admin-supervisi .muted-text {
    margin-top: 4px;
    color: var(--sup-muted);
    font-size: 11px;
    line-height: 1.6;
}

.admin-supervisi .date-text {
    white-space: nowrap;
    font-weight: 600;
}

.admin-supervisi .status-select {
    width: 130px;
    padding: 8px;
    color: var(--sup-text);
    background: var(--sup-bg);
    border: 1px solid var(--sup-border);
    border-radius: 7px;
    font-size: 11px;
}

.admin-supervisi .inline-form {
    margin: 0;
}

.admin-supervisi .comment-cell {
    min-width: 230px;
    width: 260px;
}

.admin-supervisi .comment-form {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
}

.admin-supervisi .comment-form textarea {
    min-height: 75px;
    resize: vertical;
    line-height: 1.6;
}

.admin-supervisi .btn-small {
    padding: 8px 10px;
    font-size: 10px;
}

.admin-supervisi .comment-saved {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-top: 8px;
    color: #15803d;
    font-size: 10px;
}

.admin-supervisi .empty-row {
    padding: 45px 15px !important;
    text-align: center;
}

.admin-supervisi .empty-row i {
    display: block;
    margin-bottom: 10px;
    color: #438cf5;
    font-size: 28px;
}

.admin-supervisi .empty-row strong,
.admin-supervisi .empty-row span {
    display: block;
}

.admin-supervisi .empty-row span {
    margin-top: 7px;
    color: var(--sup-muted);
}

.admin-supervisi .alert-success,
.admin-supervisi .alert-error {
    margin-bottom: 18px;
    padding: 13px 15px;
    border-radius: 8px;
    font-size: 12px;
}

.admin-supervisi .alert-success {
    color: #166534;
    background: #dcfce7;
    border: 1px solid #bbf7d0;
}

.admin-supervisi .alert-error {
    color: #991b1b;
    background: #fee2e2;
    border: 1px solid #fecaca;
}

.admin-supervisi .alert-error ul {
    margin: 8px 0 0;
    padding-left: 18px;
}

html[data-admin-theme="dark"] .admin-supervisi {
    --sup-bg: #111d30;
    --sup-border: #26364e;
    --sup-text: #edf3fc;
    --sup-muted: #91a1b8;
}

html[data-admin-theme="dark"] .admin-supervisi .stat-card,
html[data-admin-theme="dark"] .admin-supervisi .supervisi-panel {
    box-shadow: none;
}

html[data-admin-theme="dark"] .admin-supervisi .total-badge {
    color: #b5d6ff;
    background: #1b3557;
}

html[data-admin-theme="dark"] .admin-supervisi .form-group input,
html[data-admin-theme="dark"] .admin-supervisi .form-group select,
html[data-admin-theme="dark"] .admin-supervisi .form-group textarea,
html[data-admin-theme="dark"] .admin-supervisi .comment-form textarea,
html[data-admin-theme="dark"] .admin-supervisi .status-select {
    color-scheme: dark;
}

@media (max-width: 1000px) {
    .admin-supervisi .stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 700px) {
    .admin-supervisi {
        padding: 16px;
    }

    .admin-supervisi .page-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .admin-supervisi .form-grid {
        grid-template-columns: minmax(0, 1fr);
    }

    .admin-supervisi .full-width {
        grid-column: auto;
    }

    .admin-supervisi .supervisi-panel {
        padding: 16px;
    }
}
</style>
@endsection
