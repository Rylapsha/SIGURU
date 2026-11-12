@extends('layouts.admin')

@section('title', 'Jadwal Supervisi')

@section('content')
<div class="admin-supervisi">

    <div class="page-heading">

    </div>

    {{-- Pesan SweetAlert --}}
    @if(session('success'))
    <div id="supervisi-success-message"
        data-message="{{ session('success') }}"
        class="d-none"></div>
    @endif

    @if($errors->any())
    <div id="supervisi-error-message"
        data-message="{{ $errors->first() }}"
        class="d-none"></div>
    @endif

    {{-- STATUS SUPERVISI --}}
<div class="stats-container">
    <h4 class="stats-title">Status Supervisi</h4>

    <div class="stats-grid">
        <div class="stat-card">
            <span>Terjadwal</span>
            <strong>{{ $statistik['terjadwal'] }}</strong>
            <i class="ki-outline ki-calendar"></i>
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
</div>

    {{-- DAFTAR SUPERVISI --}}
    <section class="supervisi-panel">
        <div class="section-heading">
            <h3 class="m-0">Daftar Supervisi</h3>
        </div>

        <form action="{{ route('admin.supervisi.index') }}" method="GET" class="supervisi-table-toolbar">
            <a
                href="{{ route('admin.supervisi.create') }}"
                class="btn btn-light-primary dashboard-action-btn fw-semibold px-5 py-3 fs-6 supervisi-add-button"
                style="min-height: 43px;">
                <i class="bi bi-plus-lg me-2"></i>
                Tambah Jadwal
            </a>

            <div class="supervisi-search-filter">
                <div class="input-group supervisi-search">
                    <input
                        type="search"
                        name="cari"
                        class="form-control form-control-solid"
                        placeholder="Cari nama guru..."
                        aria-label="Cari nama guru"
                        value="{{ request('cari') }}">
                    <button type="submit" class="btn btn-light-primary" aria-label="Cari guru" title="Cari guru">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                <div class="dropdown">
                    <button
                        type="button"
                        class="btn btn-light btn-icon"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-label="Filter jadwal supervisi"
                        title="Filter jadwal supervisi">
                        <i class="bi bi-funnel fs-4"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end p-4 shadow-sm supervisi-filter-menu" id="supervisi-filter-menu">
                        <div class="fw-bold text-gray-800 mb-3">Filter Jadwal</div>

                        <div class="mb-3">
                            <label for="filter-mata-pelajaran" class="form-label fw-semibold">Mata Pelajaran</label>
                            <select id="filter-mata-pelajaran" name="mata_pelajaran" class="form-select form-select-solid">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach ($daftarMataPelajaran as $mapel)
                                <option value="{{ $mapel }}" @selected(request('mata_pelajaran')===$mapel)>
                                    {{ $mapel }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="supervisi-filter-date-range" class="form-label fw-semibold">Tanggal Pelaksanaan</label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="supervisi-filter-date-range"
                                    class="form-control supervisi-date-range"
                                    placeholder="Pilih rentang tanggal"
                                    aria-label="Pilih rentang tanggal pelaksanaan"
                                    autocomplete="off"
                                    readonly>
                                <span class="input-group-text"><i class="bi bi-calendar-range"></i></span>
                            </div>
                            <input type="hidden" name="tanggal_mulai" id="supervisi-filter-start" value="{{ request('tanggal_mulai') }}">
                            <input type="hidden" name="tanggal_akhir" id="supervisi-filter-end" value="{{ request('tanggal_akhir') }}">
                        </div>

                        <div class="d-flex gap-2">
                            <a
                                href="{{ route('admin.supervisi.index', ['cari' => request('cari')]) }}"
                                class="btn btn-light btn-sm flex-grow-1">
                                Reset Filter
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Terapkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-sm table-row-bordered table-row-gray-300 align-middle gy-5">
                <thead>
                    <tr class="fw-bold text-uppercase fs-7">
                        <th class="min-w-180px">Guru & Mata Pelajaran</th>
                        <th class="min-w-160px">Jadwal</th>
                        <th class="min-w-150px">Supervisor</th>
                        <th class="min-w-120px">Status</th>
                        <th class="text-end min-w-160px">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($jadwal as $item)
                    @php
                    $statusRiwayat = strtolower($item->status ?? '');
                    $badgeStatus = $statusRiwayat === 'terjadwal'
                    ? 'badge-light-primary'
                    : ($statusRiwayat === 'selesai'
                    ? 'badge-light-success'
                    : ($statusRiwayat === 'dibatalkan'
                    ? 'badge-light-danger'
                    : 'badge-light-secondary'));
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-bold text-gray-800">
                                {{ $item->nama_lengkap ?? 'Guru tidak ditemukan' }}
                            </div>

                            <div class="text-muted fs-7 mt-1">
                                {{ $item->mata_pelajaran ?: '-' }}
                            </div>

                            <div class="text-muted fs-7 mt-1">
                                Semester {{ $item->semester ?: '-' }}
                            </div>
                        </td>

                        <td>
                            <div class="fw-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d M Y') }}
                            </div>

                            <div class="text-muted fs-7 mt-1">
                                {{ $item->jam ? substr((string) $item->jam, 0, 5) . ' WIB' : '-' }}
                            </div>
                        </td>

                        <td>
                            <span class="text-gray-700">
                                {{ $item->nama_supervisor ?: '-' }}
                            </span>
                        </td>

                        <td>
                            <form action="{{ route('admin.supervisi.status', $item->id) }}" method="POST" class="inline-form status-form">
                                @csrf
                                @method('PATCH')

                                <select name="status"
                                    class="status-select badge {{ $badgeStatus }}"
                                    aria-label="Status supervisi"
                                    data-current-status="{{ $item->status }}"
                                    onchange="confirmStatusChange(this)">

                                    <option value="terjadwal" @selected($item->status === 'terjadwal')>Terjadwal</option>
                                    <option value="selesai" @selected($item->status === 'selesai')>Selesai</option>
                                    <option value="dibatalkan" @selected($item->status === 'dibatalkan')>Dibatalkan</option>
                                </select>
                            </form>
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                <a href="{{ route('admin.supervisi.detail', $item->id) }}"
                                    class="btn btn-sm btn-light-primary d-inline-flex align-items-center gap-2"
                                    title="Lihat Detail"
                                    aria-label="Lihat Detail">
                                    <i class="bi bi-eye fs-2"></i>
                                    <span class="fw-semibold">Detail</span>
                                </a>

                                @if ($statusRiwayat === 'selesai')
                                <a href="{{ route('admin.supervisi.feedback', $item->id) }}"
                                    class="btn btn-sm btn-light-success d-inline-flex align-items-center gap-2"
                                    title="Feedback"
                                    aria-label="Feedback">
                                    <i class="bi bi-chat-dots fs-2"></i>
                                    <span class="fw-semibold">Feedback</span>
                                </a>
                                @else
                                <button type="button"
                                    class="btn btn-sm btn-secondary d-inline-flex align-items-center gap-2"
                                    title="Feedback tersedia setelah supervisi selesai"
                                    aria-label="Feedback belum tersedia"
                                    aria-disabled="true"
                                    disabled>
                                    <i class="bi bi-chat-dots fs-2"></i>
                                    <span class="fw-semibold">Feedback</span>
                                </button>
                                @endif

                                <form action="{{ route('admin.supervisi.destroy', $item->id) }}" method="POST" class="inline-form delete-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                        class="btn btn-sm btn-light-danger d-inline-flex align-items-center gap-2"
                                        onclick="confirmDeleteSupervisi(this)"
                                        title="Hapus"
                                        aria-label="Hapus">
                                        <i class="bi bi-trash fs-2"></i>
                                        <span class="fw-semibold">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10">
                            <i class="bi bi-inbox fs-3x text-muted mb-4 d-block"></i>
                            <div class="text-muted fs-7">
                                Belum Ada Jadwal Supervisi
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>

{{-- SWEETALERT2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Tampilkan dan sembunyikan formulir tambah jadwal.
        const btnTambahJadwal = document.getElementById('btnTambahJadwal');
        const btnBatalTambah = document.getElementById('btnBatalTambah');
        const formTambah = document.getElementById('formTambah');

        if (btnTambahJadwal && formTambah) {
            btnTambahJadwal.addEventListener('click', function() {
                const sedangTersembunyi =
                    window.getComputedStyle(formTambah).display === 'none';

                if (sedangTersembunyi) {
                    formTambah.style.display = 'block';

                    formTambah.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    btnTambahJadwal.innerHTML =
                        '<i class="ki-outline ki-minus"></i> Tutup Formulir';
                } else {
                    formTambah.style.display = 'none';

                    btnTambahJadwal.innerHTML =
                        '<i class="ki-outline ki-plus"></i> Tambah Jadwal';
                }
            });
        }

        // Tombol batal menyembunyikan formulir.
        if (btnBatalTambah && formTambah) {
            btnBatalTambah.addEventListener('click', function() {
                formTambah.style.display = 'none';

                btnTambahJadwal.innerHTML =
                    '<i class="ki-outline ki-plus"></i> Tambah Jadwal';

                btnTambahJadwal.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            });
        }

        // Autofill mata pelajaran berdasarkan guru.
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

        // Jika validasi gagal, buka kembali formulir.
        const errorMessage = document.getElementById(
            'supervisi-error-message'
        );

        if (errorMessage && errorMessage.dataset.message) {
            formTambah.style.display = 'block';

            btnTambahJadwal.innerHTML =
                '<i class="ki-outline ki-minus"></i> Tutup Formulir';

            Swal.fire({
                icon: 'error',
                title: 'Data belum valid',
                text: errorMessage.dataset.message,
                confirmButtonText: 'Periksa kembali',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'swal-confirm-btn',
                    popup: 'supervisi-swal-popup'
                }
            });
        }

        // Notifikasi berhasil.
        const successMessage = document.getElementById(
            'supervisi-success-message'
        );

        if (successMessage && successMessage.dataset.message) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: successMessage.dataset.message,
                confirmButtonText: 'Oke',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'swal-confirm-btn',
                    popup: 'supervisi-swal-popup'
                }
            });
        }
    });


    // Konfirmasi perubahan status supervisi.
    function confirmStatusChange(select) {
        const form = select.form;
        const oldStatus = select.dataset.currentStatus;
        const newStatus = select.value;

        if (newStatus === oldStatus) {
            return;
        }

        const statusLabels = {
            terjadwal: 'Terjadwal',
            selesai: 'Selesai',
            dibatalkan: 'Dibatalkan'
        };

        Swal.fire({
            icon: 'question',
            title: 'Ubah status supervisi?',
            html: 'Status akan diubah menjadi <strong>' +
                statusLabels[newStatus] + '</strong>.',
            showCancelButton: true,
            confirmButtonText: 'Ya, ubah status',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn',
                popup: 'supervisi-swal-popup'
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                form.submit();
            } else {
                select.value = oldStatus;
            }
        });
    }


    // Konfirmasi penyimpanan komentar admin.
    function confirmCommentSave(button) {
        const form = button.closest('form');
        const textarea = form.querySelector(
            'textarea[name="komentar_admin"]'
        );
        const komentar = textarea.value.trim();

        if (komentar === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Komentar masih kosong',
                text: 'Tulis komentar terlebih dahulu sebelum menyimpan.',
                confirmButtonText: 'Mengerti',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'swal-confirm-btn',
                    popup: 'supervisi-swal-popup'
                }
            });

            textarea.focus();
            return;
        }

        Swal.fire({
            icon: 'question',
            title: 'Simpan komentar?',
            text: 'Komentar ini akan disimpan untuk jadwal supervisi guru.',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn',
                popup: 'supervisi-swal-popup'
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                button.disabled = true;
                button.innerHTML =
                    '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';

                form.submit();
            }
        });
    }


    // Konfirmasi penghapusan jadwal supervisi.
    function confirmDelete(form) {
        const row = form.closest('tr');
        const guru = row.querySelector('.teacher-name').innerText;
        const tanggal = row.querySelector('.date-text').innerText;

        Swal.fire({
            icon: 'warning',
            title: 'Hapus jadwal supervisi?',
            html: 'Anda akan menghapus jadwal supervisi untuk <strong>' +
                guru + '</strong> pada tanggal <strong>' + tanggal + '</strong>.',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus jadwal',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn',
                popup: 'supervisi-swal-popup'
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });

        return false; // Mencegah pengiriman formulir default
    }

    // Konfirmasi penghapusan jadwal supervisi (dari tombol Hapus di kolom komentar).
    function confirmDeleteSupervisi(button) {
        const form = button.closest('form');

        Swal.fire({
            icon: 'warning',
            title: 'Hapus jadwal supervisi?',
            text: 'Jadwal yang dihapus tidak dapat dikembalikan.',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn',
                popup: 'supervisi-swal-popup'
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghapus...';
                form.submit();
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!window.jQuery || !window.initSiguruDatePicker || !window.moment) {
            console.error('Komponen filter tanggal supervisi gagal dimuat.');
            return;
        }

        const $ = window.jQuery;
        const dateRangeInput = $('#supervisi-filter-date-range');
        const startInput = $('#supervisi-filter-start');
        const endInput = $('#supervisi-filter-end');

        if (!dateRangeInput.length) {
            return;
        }

        const startValue = startInput.val();
        const endValue = endInput.val();
        const pickerOptions = {
            autoUpdateInput: false,
            parentEl: '#supervisi-filter-menu'
        };

        if (startValue && endValue) {
            const startDate = window.moment(startValue, 'YYYY-MM-DD', true);
            const endDate = window.moment(endValue, 'YYYY-MM-DD', true);

            if (startDate.isValid() && endDate.isValid()) {
                pickerOptions.startDate = startDate;
                pickerOptions.endDate = endDate;
                dateRangeInput.val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
            }
        }

        if (!window.initSiguruDatePicker(dateRangeInput, pickerOptions)) {
            return;
        }

        dateRangeInput.on('apply.daterangepicker', function(event, picker) {
            dateRangeInput.val(
                picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY')
            );
            startInput.val(picker.startDate.format('YYYY-MM-DD'));
            endInput.val(picker.endDate.format('YYYY-MM-DD'));
        });

        dateRangeInput.on('cancel.daterangepicker', function() {
            dateRangeInput.val('');
            startInput.val('');
            endInput.val('');
        });
    });
</script>

<style>
    .admin-supervisi {
        --sup-bg: #f4f9ff;
        --sup-border: #dfeeff;
        --sup-text: #1d3557;
        --sup-muted: #59749a;

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

    /* STATISTIK SUPERVISI */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    width: 100%;
    margin-bottom: 20px;
}

.stat-card {
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 0;
    min-height: 100px;
    padding: 18px 20px;
    background: #ffffff;
    border: 1px solid #dce5f0;
    border-radius: 12px;
    box-sizing: border-box;
}

.stat-card span {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 8px;
}

.stat-card strong {
    font-size: 24px;
    font-weight: 700;
    line-height: 1.2;
    color: #17365d;
}

.stat-card i {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 26px;
    color: #b7c9df;
}


/* RESPONSIVE */
@media (max-width: 767px) {
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}

    .admin-supervisi .stat-card strong {
        color: var(--sup-text);
        font-size: 28px;
        line-height: 1.2;
    }

    .admin-supervisi .stat-card>i {
        position: absolute;
        top: 20px;
        right: 18px;
        color: #438cf5;
        font-size: 22px;
    }

    .admin-supervisi .supervisi-panel {
        margin-bottom: 22px;
        padding: 23px;
        background: #fff;
        border: 1px solid var(--admin-border, #CFDCEB);
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(32, 96, 196, 0.06);
    }

    .admin-supervisi .section-heading {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 12px;
    }

    .admin-supervisi .supervisi-table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }

    .admin-supervisi .supervisi-search-filter {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-left: auto;
    }

    .admin-supervisi .supervisi-search {
        width: min(100%, 360px);
    }

    .admin-supervisi .supervisi-filter-menu {
        width: 340px;
        max-width: calc(100vw - 32px);
    }

    .admin-supervisi .supervisi-date-range {
        cursor: pointer;
        background-color: #fff;
    }

    .admin-supervisi .supervisi-add-button {
        margin-right: auto;
    }

    .admin-supervisi .section-heading h2 {
        margin: 0 0 6px;
        color: var(--sup-text);
        font-size: 16px;
        font-weight: 700;
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
        transition: border-color .2s ease, box-shadow .2s ease;
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
        gap: 10px;
        margin-top: 20px;
    }

    .admin-supervisi .btn-primary,
    .admin-supervisi .btn-small,
    .admin-supervisi .btn-secondary {
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
        transition: background .2s ease, transform .2s ease;
    }

    .admin-supervisi .btn-primary:hover,
    .admin-supervisi .btn-small:hover {
        color: #fff;
        background: #1d4f96;
    }

    .admin-supervisi .btn-secondary {
        color: #1d3557;
        background: #eaf2ff;
    }

    .admin-supervisi .btn-secondary:hover {
        background: #dfeeff;
    }

    .admin-supervisi .btn-primary:active,
    .admin-supervisi .btn-small:active {
        transform: scale(.98);
    }

    .admin-supervisi .btn-primary:focus-visible,
    .admin-supervisi .btn-small:focus-visible,
    .admin-supervisi .btn-secondary:focus-visible {
        outline: 3px solid rgba(67, 140, 245, .3);
        outline-offset: 2px;
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
        min-width: 0;
        width: auto;
        max-width: 150px;
        min-height: 0;
        padding: 0.5em 1.5rem 0.5em 0.75em;
        border: 0;
        line-height: 1;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: linear-gradient(45deg, transparent 50%, currentColor 50%), linear-gradient(135deg, currentColor 50%, transparent 50%);
        background-position: calc(100% - 10px) 50%, calc(100% - 5px) 50%;
        background-size: 5px 5px, 5px 5px;
        background-repeat: no-repeat;
        box-shadow: none;
        transition: box-shadow .2s ease;
    }

    .admin-supervisi .status-select:focus {
        box-shadow: 0 0 0 3px rgba(40, 120, 255, 0.12);
        outline: none;
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

    .admin-supervisi .btn-delete {
        color: #fff;
        background: #dc2626;
    }

    .admin-supervisi .btn-delete:hover {
        background: #b91c1c;
    }

    .admin-supervisi .btn-small:disabled {
        opacity: .7;
        cursor: wait;
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

    /* Tombol SweetAlert2 */
    .swal-confirm-btn,
    .swal-cancel-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 5px;
        padding: 10px 17px;
        border: 0;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 650;
        cursor: pointer;
    }

    .swal-confirm-btn {
        color: #fff;
        background: #2563b9;
    }

    .swal-confirm-btn:hover {
        background: #1d4f96;
    }

    .swal-cancel-btn {
        color: #334155;
        background: #e2e8f0;
    }

    .swal-cancel-btn:hover {
        background: #cbd5e1;
    }

    .supervisi-swal-popup {
        border-radius: 14px;
        font-family: Inter, sans-serif;
        color: #1d3557;
        background: #ffffff;
    }

    .supervisi-swal-popup .swal2-html-container {
        color: #4b5c7a;
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

        .admin-supervisi .supervisi-table-toolbar {
            align-items: center;
        }

        .admin-supervisi .supervisi-search-filter {
            width: 100%;
            margin-left: 0;
        }

        .admin-supervisi .supervisi-search {
            flex: 1;
            width: auto;
            min-width: 0;
        }

        .admin-supervisi .stats-grid {
            gap: 10px;
        }

        .admin-supervisi .stat-card {
            padding: 15px;
        }

        .admin-supervisi .stat-card strong {
            font-size: 24px;
        }
    }

    .admin-supervisi .action-stack {
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }

    .admin-supervisi .btn-detail {
        color: #1d4f96;
        background: #eaf2ff;
    }

    .admin-supervisi .btn-detail:hover {
        background: #dfeeff;
    }

    .admin-supervisi .detail-modal-card {
        padding: 12px 14px;
        border: 1px solid var(--sup-border);
        border-radius: 10px;
        background: #f9fbff;
    }

    .admin-supervisi .detail-label {
        margin-bottom: 6px;
        color: var(--sup-muted);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .admin-supervisi .detail-value {
        color: var(--sup-text);
        font-size: 12px;
        font-weight: 600;
        line-height: 1.6;
    }

    .admin-supervisi .modal-content {
        border-radius: 16px;
        border: 1px solid var(--sup-border);
    }

    .admin-supervisi .modal-header,
    .admin-supervisi .modal-body {
        background: #fff;
    }

    .admin-supervisi .modal-body .form-control,
    .admin-supervisi .modal-body .form-select {
        border: 1px solid var(--sup-border);
        border-radius: 10px;
        background: #fff;
        color: var(--sup-text);
        font-size: 12px;
        padding: 10px 12px;
    }

    .admin-supervisi .modal-body .form-control:focus,
    .admin-supervisi .modal-body .form-select:focus {
        border-color: #438cf5;
        box-shadow: 0 0 0 3px rgba(67, 140, 245, .1);
    }

    .admin-supervisi .section-heading-tools {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        width: 100%;
        margin-bottom: 18px;
    }

    .admin-supervisi .table {
        font-family: Poppins, sans-serif;
    }

    .admin-supervisi .table thead th {
        color: #1f3c57;
        font-size: inherit;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .admin-supervisi .table tbody td {
        color: var(--sup-text);
        vertical-align: middle;
    }
</style>
@endsection