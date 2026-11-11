<!--begin::Footer-->
<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">

    <!--begin::Container-->
    <div class="container-fluid d-flex flex-column flex-md-row
                align-items-center justify-content-between">

        <!--begin::Copyright-->
        <div class="text-dark order-2 order-md-1">
            <span class="text-muted fw-bold me-1">
                {{ date('Y') }}©
            </span>

            <span class="text-gray-800 text-hover-primary">
                SIGURU - SMP Negeri 2 Purwakarta
            </span>
        </div>
        <!--end::Copyright-->

        <!--begin::Menu-->
        <ul class="menu menu-gray-600 menu-hover-primary fw-bold order-1 flex-wrap justify-content-center">
            @if (($footerRole ?? 'guru') === 'admin')
                <li class="menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link px-2">Dashboard</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.guru.index') }}" class="menu-link px-2">Data Guru</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.mata-pelajaran.index') }}" class="menu-link px-2">Mata Pelajaran</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.supervisi.index') }}" class="menu-link px-2">Jadwal Supervisi</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.agenda.index') }}" class="menu-link px-2">Agenda Kegiatan</a>
                </li>
            @else
                <li class="menu-item">
                    <a href="{{ route('dashboard') }}" class="menu-link px-2">Dashboard</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('profil') }}" class="menu-link px-2">Profil</a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('dokumen.index') }}" class="menu-link px-2">Dokumen</a>
                </li>
            @endif
        </ul>
        <!--end::Menu-->

    </div>
    <!--end::Container-->

</div>
<!--end::Footer-->