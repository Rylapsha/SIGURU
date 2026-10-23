@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="siguru-dashboard">
    {{-- HEADER --}}
    <div class="dashboard-heading">
        <div>
            <span class="dashboard-eyebrow">PANEL ADMINISTRATOR</span>
            <h1>Dashboard Admin</h1>
            <p>Ringkasan data guru, kelengkapan dokumen, dan jadwal supervisi sekolah.</p>
        </div>
        <div class="dashboard-heading-mark"><i class="ki-outline ki-element-11"></i></div>
    </div>

    {{-- KARTU RINGKASAN --}}
    <div class="summary-grid">
        <div class="summary-card summary-guru">
            <div class="summary-icon"><i class="ki-outline ki-people"></i></div>
            <div class="summary-content">
                <span class="summary-label">Total Guru</span>
                <strong>{{ number_format($totalGuru, 0, ',', '.') }}</strong>
                <small>Guru terdaftar di SIGURU</small>
            </div>
            <span class="summary-card-accent"></span>
        </div>
    </div>

    {{-- GRAFIK DAN KELENGKAPAN DOKUMEN --}}
    <div class="dashboard-main-grid">
        <section class="dashboard-panel chart-panel">
            <div class="panel-heading">
                <div>
                    <span class="panel-kicker">STATISTIK SEKOLAH</span>
                    <h3>Jumlah Guru per Mata Pelajaran</h3>
                    <p>Persebaran guru berdasarkan mata pelajaran yang diampu.</p>
                </div>
                <div class="panel-heading-icon blue-icon"><i class="ki-outline ki-chart-simple"></i></div>
            </div>

            @if($statistikMapel->isNotEmpty())
                @php $jumlahMaksimum = max(1, (int) $statistikMapel->max('jumlah')); @endphp
                <div class="bar-chart">
                    @foreach($statistikMapel as $item)
                        <div class="bar-chart-item">
                            <span class="bar-chart-count">{{ $item->jumlah }}</span>
                            <div class="bar-chart-track">
                                <div class="bar-chart-bar" style="height: {{ max(8, ($item->jumlah / $jumlahMaksimum) * 100) }}%"></div>
                            </div>
                            <span class="bar-chart-label" title="{{ $item->mata_pelajaran }}">{{ $item->mata_pelajaran }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="chart-footer"><span class="chart-legend"></span>Jumlah guru per mata pelajaran</div>
            @else
                <div class="empty-chart">
                    <div class="empty-state-icon"><i class="ki-outline ki-chart-simple"></i></div>
                    <h4>Belum ada data mata pelajaran</h4>
                    <p>Grafik akan muncul setelah data mata pelajaran tersedia.</p>
                </div>
            @endif
        </section>

        <section class="dashboard-panel document-panel">
            <div class="panel-heading">
                <div>
                    <span class="panel-kicker">PEMANTAUAN ADMINISTRASI</span>
                    <h3>Kelengkapan Dokumen Guru</h3>
                    <p>Ringkasan pemenuhan 8 jenis dokumen wajib.</p>
                </div>
                <div class="panel-heading-icon green-icon"><i class="ki-outline ki-document"></i></div>
            </div>

            {{-- RINGKASAN KELENGKAPAN DOKUMEN: tetap di dalam panel, bukan kartu atas --}}
            <div class="document-stats">
                <div class="document-stat complete-stat">
                    <div class="document-stat-top">
                        <span class="document-stat-label">Dokumen Lengkap</span>
                        <i class="ki-outline ki-check-circle"></i>
                    </div>
                    <div class="document-stat-number">{{ $dokumenLengkap }} <small>dari {{ $totalGuru }} guru</small></div>
                    <div class="mini-progress"><span style="width: {{ $persentaseLengkap }}%"></span></div>
                    <span class="document-stat-percent">{{ $persentaseLengkap }}% guru</span>
                </div>
                <div class="document-stat incomplete-stat">
                    <div class="document-stat-top">
                        <span class="document-stat-label">Dokumen Belum Lengkap</span>
                        <i class="ki-outline ki-alert-circle"></i>
                    </div>
                    <div class="document-stat-number">{{ $dokumenBelumLengkap }} <small>dari {{ $totalGuru }} guru</small></div>
                    <div class="mini-progress"><span style="width: {{ $persentaseBelumLengkap }}%"></span></div>
                    <span class="document-stat-percent">{{ $persentaseBelumLengkap }}% guru</span>
                </div>
            </div>

            <div class="completion-heading">
                <span>Persentase Kelengkapan Dokumen</span>
                <strong>{{ $persentaseLengkap }}% lengkap</strong>
            </div>
            <div class="completion-bar" role="img" aria-label="{{ $persentaseLengkap }} persen guru lengkap dan {{ $persentaseBelumLengkap }} persen belum lengkap">
                @if($totalGuru > 0)
                    <div class="completion-done" style="width: {{ $persentaseLengkap }}%">@if($persentaseLengkap >= 12){{ $persentaseLengkap }}%@endif</div>
                    <div class="completion-pending" style="width: {{ $persentaseBelumLengkap }}%">@if($persentaseBelumLengkap >= 12){{ $persentaseBelumLengkap }}%@endif</div>
                @else
                    <div class="completion-empty">Belum ada data guru</div>
                @endif
            </div>
            <div class="completion-legend">
                <span><i class="legend-dot done-dot"></i>Dokumen lengkap</span>
                <span><i class="legend-dot pending-dot"></i>Belum lengkap</span>
            </div>

            <div class="document-info"><i class="ki-outline ki-information-3"></i><p>Dokumen dinilai lengkap jika seluruh 8 jenis tersedia pada tahun ajaran, mata pelajaran, semester, dan kelas yang sesuai. PROTA dan PROSEM tidak dibedakan berdasarkan kelas.</p></div>
        </section>
    </div>

    {{-- JADWAL SUPERVISI --}}
    <section class="dashboard-panel schedule-panel">
        <div class="panel-heading schedule-heading">
            <div>
                <span class="panel-kicker">AGENDA SEKOLAH</span>
                <h3>Jadwal Supervisi</h3>
                <p>Jadwal supervisi mendatang yang berstatus terjadwal.</p>
            </div>
            <a href="{{ route('admin.supervisi.index') }}" class="view-all-link">Lihat Semua <i class="ki-outline ki-arrow-right"></i></a>
        </div>

        <div class="schedule-list">
            @forelse($jadwalMendatang as $jadwal)
                <div class="schedule-item">
                    <div class="schedule-date">
                        <span class="schedule-day">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d') }}</span>
                        <span class="schedule-month">{{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->translatedFormat('M') }}</span>
                    </div>
                    <div class="schedule-detail">
                        <h4>{{ $jadwal->nama_lengkap ?? 'Guru belum ditentukan' }}</h4>
                        <p class="schedule-subject">{{ $jadwal->mata_pelajaran ?: 'Mata pelajaran belum ditentukan' }}</p>
                        <div class="schedule-time"><i class="ki-outline ki-time"></i><span>{{ $jadwal->jam ? \Carbon\Carbon::parse($jadwal->jam)->format('H:i') . ' WIB' : 'Jam belum ditentukan' }}</span></div>
                    </div>
                    <span class="schedule-status"><i class="ki-outline ki-check"></i> Terjadwal</span>
                </div>
            @empty
                <div class="empty-schedule">
                    <div class="empty-state-icon"><i class="ki-outline ki-calendar-tick"></i></div>
                    <h4>Belum ada jadwal supervisi</h4>
                    <p>Jadwal supervisi mendatang akan ditampilkan di sini.</p>
                    <a href="{{ route('admin.supervisi.index') }}">Kelola Jadwal</a>
                </div>
            @endforelse
        </div>
    </section>
</div>

<style>
    .siguru-dashboard {
        --surface: #111d30;
        --surface-soft: #15243a;
        --border: #263a55;
        --text: #edf4ff;
        --muted: #8fa4bf;
        --blue: #4d91f7;
        --green: #25c58a;
        --red: #f05b66;
        width: 100%; padding: 26px 30px 34px; box-sizing: border-box;
        color: var(--text); font-family: Inter, sans-serif;
    }
    .siguru-dashboard *, .siguru-dashboard *::before, .siguru-dashboard *::after { box-sizing: border-box; }
    .dashboard-heading { display:flex; align-items:center; justify-content:space-between; gap:18px; margin-bottom:23px; }
    .dashboard-eyebrow, .panel-kicker { display:block; margin-bottom:7px; color:var(--blue); font-size:10px; font-weight:750; letter-spacing:1.1px; text-transform:uppercase; }
    .dashboard-heading h1 { margin:0 0 7px; color:var(--text); font-size:27px; font-weight:750; letter-spacing:-.6px; }
    .dashboard-heading p { margin:0; color:var(--muted); font-size:12px; line-height:1.7; }
    .dashboard-heading-mark { width:45px; height:45px; display:flex; align-items:center; justify-content:center; flex:0 0 45px; border:1px solid var(--border); border-radius:13px; background:var(--surface); color:var(--blue); font-size:22px; }
    .summary-grid { display:grid; grid-template-columns:minmax(0, 360px); gap:16px; margin-bottom:18px; }
    .summary-card { position:relative; display:flex; align-items:center; gap:15px; min-width:0; min-height:122px; overflow:hidden; padding:20px; border:1px solid var(--border); border-radius:13px; background:var(--surface); box-shadow:0 5px 16px rgba(0,0,0,.08); }
    .summary-icon { display:flex; align-items:center; justify-content:center; width:48px; height:48px; flex:0 0 48px; border-radius:12px; background:#1d3859; color:var(--blue); font-size:23px; }
    .summary-content { display:flex; flex-direction:column; min-width:0; gap:5px; }
    .summary-label { color:var(--muted); font-size:11px; font-weight:600; }
    .summary-content strong { color:var(--text); font-size:28px; font-weight:750; line-height:1.15; }
    .summary-content strong small { font-size:11px; font-weight:550; }
    .summary-content > small { color:var(--muted); font-size:10px; }
    .summary-card-accent { position:absolute; right:0; bottom:0; left:0; height:3px; background:var(--blue); opacity:.85; }
    .dashboard-main-grid { display:grid; grid-template-columns:minmax(0,1.05fr) minmax(0,.95fr); align-items:stretch; gap:18px; margin-bottom:18px; }
    .dashboard-panel { min-width:0; padding:22px; border:1px solid var(--border); border-radius:14px; background:var(--surface); box-shadow:0 5px 16px rgba(0,0,0,.06); }
    .panel-heading { display:flex; align-items:flex-start; justify-content:space-between; gap:14px; padding-bottom:18px; border-bottom:1px solid var(--border); }
    .panel-heading h3 { margin:0 0 6px; color:var(--text); font-size:15px; font-weight:750; letter-spacing:-.2px; }
    .panel-heading p { margin:0; color:var(--muted); font-size:10px; line-height:1.7; }
    .panel-kicker { margin-bottom:6px; font-size:9px; letter-spacing:.9px; }
    .panel-heading-icon { display:flex; align-items:center; justify-content:center; width:38px; height:38px; flex:0 0 38px; border-radius:11px; font-size:19px; }
    .blue-icon { background:rgba(77,145,247,.14); color:var(--blue); }
    .green-icon { background:rgba(37,197,138,.13); color:var(--green); }
    .bar-chart { display:flex; align-items:flex-end; gap:clamp(12px,2vw,25px); min-height:220px; padding:23px 4px 4px; overflow-x:auto; }
    .bar-chart-item { display:flex; flex:1 0 54px; flex-direction:column; align-items:center; justify-content:flex-end; min-width:0; height:190px; }
    .bar-chart-count { margin-bottom:8px; color:var(--text); font-size:10px; font-weight:750; }
    .bar-chart-track { display:flex; align-items:flex-end; justify-content:center; width:100%; height:140px; border-bottom:1px solid var(--border); background:linear-gradient(to bottom,transparent 24%,rgba(143,164,191,.055) 25%,transparent 26%,transparent 49%,rgba(143,164,191,.055) 50%,transparent 51%,transparent 74%,rgba(143,164,191,.055) 75%,transparent 76%); }
    .bar-chart-bar { width:min(30px,60%); min-height:8px; border-radius:6px 6px 2px 2px; background:linear-gradient(180deg,#69b0ff 0%,#3478e5 100%); box-shadow:0 5px 13px rgba(52,120,229,.18); }
    .bar-chart-label { display:-webkit-box; width:100%; margin-top:9px; overflow:hidden; color:var(--muted); font-size:9px; line-height:1.5; text-align:center; overflow-wrap:anywhere; -webkit-line-clamp:2; -webkit-box-orient:vertical; }
    .chart-footer { display:flex; align-items:center; gap:8px; padding-top:14px; border-top:1px solid var(--border); color:var(--muted); font-size:10px; }
    .chart-legend { width:7px; height:7px; border-radius:50%; background:var(--blue); }
    .incomplete-stat .mini-progress span { background:var(--red); }
    .completion-done, .completion-pending { display:flex; align-items:center; justify-content:center; min-width:0; color:#fff; font-size:9px; font-weight:750; white-space:nowrap; transition:width .25s ease; }

    .document-stats { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px; margin-top:18px; }
    .document-stat { min-width:0; padding:14px; border:1px solid var(--border); border-radius:10px; background:var(--surface-soft); }
    .document-stat-top { display:flex; align-items:center; justify-content:space-between; gap:8px; color:var(--muted); font-size:10px; font-weight:650; }
    .document-stat-top i { font-size:16px; color:var(--green); }
    .incomplete-stat .document-stat-top i { color:var(--red); }
    .document-stat-number { margin:10px 0; color:var(--text); font-size:23px; font-weight:800; line-height:1.2; }
    .document-stat-number small { color:var(--muted); font-size:9px; font-weight:500; }
    .mini-progress { height:6px; overflow:hidden; border-radius:20px; background:rgba(143,164,191,.15); }
    .mini-progress span { display:block; height:100%; border-radius:20px; background:var(--green); }
    .incomplete-stat .mini-progress span { background:var(--red); }
    .document-stat-percent { display:block; margin-top:7px; color:var(--muted); font-size:9px; }
    .completion-heading { display:flex; justify-content:space-between; align-items:center; gap:10px; margin:22px 0 10px; color:var(--text); font-size:10px; }
    .completion-heading strong { color:var(--muted); font-size:9px; }
    .completion-bar { display:flex; width:100%; height:28px; overflow:hidden; border-radius:7px; background:rgba(143,164,191,.15); }
    .completion-done { background:var(--green); }
    .completion-pending { background:var(--red); }
    .completion-empty { display:flex; align-items:center; justify-content:center; width:100%; color:var(--muted); font-size:10px; }
    .completion-legend { display:flex; flex-wrap:wrap; gap:15px; margin-top:11px; }
    .completion-legend span { display:inline-flex; align-items:center; gap:6px; color:var(--muted); font-size:9px; }
    .legend-dot { width:7px; height:7px; border-radius:50%; }
    .done-dot { background:var(--green); }
    .pending-dot { background:var(--red); }

    .document-info { display:flex; align-items:flex-start; gap:9px; margin-top:18px; padding:12px; border:1px solid var(--border); border-radius:9px; background:rgba(77,145,247,.055); }
    .document-info i { flex:0 0 auto; margin-top:1px; color:var(--blue); font-size:14px; }
    .document-info p { margin:0; color:var(--muted); font-size:9px; line-height:1.8; }
    .schedule-panel { padding-bottom:8px; }
    .schedule-heading { align-items:center; }
    .view-all-link { display:inline-flex; align-items:center; gap:7px; flex:0 0 auto; padding:9px 12px; border:1px solid var(--border); border-radius:8px; color:var(--blue); font-size:10px; font-weight:700; text-decoration:none; transition:background .2s ease; }
    .view-all-link:hover { background:rgba(77,145,247,.1); color:#7db4ff; }
    .schedule-list { display:flex; flex-direction:column; }
    .schedule-item { display:flex; align-items:center; gap:15px; min-width:0; padding:16px 2px; border-bottom:1px solid var(--border); }
    .schedule-item:last-child { border-bottom:0; }
    .schedule-date { display:flex; flex:0 0 52px; flex-direction:column; align-items:center; justify-content:center; min-height:54px; border:1px solid rgba(77,145,247,.24); border-radius:10px; background:rgba(77,145,247,.1); }
    .schedule-day { color:var(--text); font-size:19px; font-weight:750; line-height:1.2; }
    .schedule-month { margin-top:3px; color:var(--blue); font-size:9px; font-weight:750; text-transform:uppercase; }
    .schedule-detail { flex:1; min-width:0; }
    .schedule-detail h4 { margin:0 0 5px; color:var(--text); font-size:11px; font-weight:750; }
    .schedule-subject { margin:0 0 7px; color:var(--muted); font-size:10px; }
    .schedule-time { display:flex; align-items:center; gap:6px; color:var(--muted); font-size:9px; }
    .schedule-time i { font-size:12px; }
    .schedule-status { display:inline-flex; align-items:center; gap:4px; flex:0 0 auto; padding:6px 9px; border:1px solid rgba(37,197,138,.23); border-radius:7px; background:rgba(37,197,138,.1); color:#5ce0ad; font-size:9px; font-weight:700; white-space:nowrap; }
    .empty-chart, .empty-schedule { display:flex; flex-direction:column; align-items:center; justify-content:center; min-height:220px; padding:25px 10px; text-align:center; }
    .empty-state-icon { display:flex; align-items:center; justify-content:center; width:44px; height:44px; margin-bottom:13px; border-radius:12px; background:rgba(77,145,247,.13); color:var(--blue); font-size:21px; }
    .empty-chart h4, .empty-schedule h4 { margin:0 0 7px; color:var(--text); font-size:12px; font-weight:700; }
    .empty-chart p, .empty-schedule p { max-width:300px; margin:0; color:var(--muted); font-size:10px; line-height:1.7; }
    .empty-schedule a { display:inline-block; margin-top:14px; padding:9px 13px; border-radius:7px; background:#245da5; color:#fff; font-size:10px; font-weight:700; text-decoration:none; }

    /* Tema terang mengikuti pengaturan tema admin */
    html[data-admin-theme="light"] .siguru-dashboard { --surface:#fff; --surface-soft:#f7faff; --border:#e3eaf3; --text:#17243a; --muted:#64748b; }
    html[data-admin-theme="light"] .dashboard-panel, html[data-admin-theme="light"] .summary-card { box-shadow:0 4px 16px rgba(15,23,42,.035); }
    html[data-admin-theme="light"] .summary-icon { background:#e8f1ff; }
    html[data-admin-theme="light"] .summary-complete .summary-icon { background:#e6f8f0; }
    html[data-admin-theme="light"] .summary-incomplete .summary-icon { background:#fff0f0; }
    html[data-admin-theme="light"] .document-stat { background:#f8faff; }
        html[data-admin-theme="light"] .bar-chart-track { background:linear-gradient(to bottom,transparent 24%,rgba(100,116,139,.07) 25%,transparent 26%,transparent 49%,rgba(100,116,139,.07) 50%,transparent 51%,transparent 74%,rgba(100,116,139,.07) 75%,transparent 76%); }
    html[data-admin-theme="light"] .document-info { background:#f6f9ff; }

    @media (max-width: 1100px) {
        .siguru-dashboard { padding:22px 18px 28px; }
        .dashboard-main-grid { grid-template-columns:minmax(0,1fr); }
        .summary-grid { gap:12px; }
        .summary-card { padding:16px; }
    }
    @media (max-width: 767px) {
        .siguru-dashboard { padding:16px; }
        .dashboard-heading h1 { font-size:23px; }
        .summary-grid { grid-template-columns:minmax(0,1fr); }
        .summary-card { min-height:100px; }
        .document-stats { grid-template-columns:minmax(0,1fr); }
        .dashboard-main-grid { gap:14px; }
        .dashboard-panel { padding:17px; }
        .bar-chart { gap:12px; min-height:195px; }
        .bar-chart-item { height:170px; }
        .bar-chart-track { height:120px; }
                    .schedule-item { align-items:flex-start; gap:10px; }
        .schedule-status { padding:5px 6px; font-size:8px; }
        .schedule-date { flex-basis:46px; }
        .view-all-link { padding:8px; font-size:9px; }
    }
    @media (max-width: 420px) {
            .schedule-heading { align-items:flex-start; }
        .schedule-status { max-width:78px; white-space:normal; }
    }
</style>
@endsection
