
<!--begin::Header-->
<div id="kt_header" class="header align-items-stretch mb-0">

    <!--begin::Container-->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">

        {{-- Toggle sidebar mobile --}}
        <div class="d-flex align-items-center d-lg-none ms-n2 me-2"
            title="Tampilkan menu sidebar">

            <div class="btn btn-icon btn-active-light-primary
                        w-30px h-30px w-md-40px h-md-40px"
                id="kt_aside_mobile_toggle">

                <span class="svg-icon svg-icon-1">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24"
                        viewBox="0 0 24 24" fill="none">
                        <path
                            d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                            fill="currentColor" />
                        <path opacity="0.3"
                            d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                            fill="currentColor" />
                    </svg>
                </span>
            </div>
        </div>
        {{--end::Toggle sidebar mobile--}}

        <!--begin::Wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">

            {{-- Navbar --}}
            <div class="d-flex align-items-stretch" id="kt_header_nav">
            </div>

            {{-- Judul halaman --}}
            <div id="kt_toolbar_container"
                class="container-fluid d-flex align-items-center flex-stack">

                <div class="page-title d-flex align-items-center flex-wrap
                            me-3 mb-5 mb-lg-0">

                    <h1 class="d-flex text-dark fw-bolder fs-3
                               align-items-center my-1">

                        @if (request()->routeIs('admin.supervisi.*'))
                            Jadwal Supervisi
                        @elseif (request()->routeIs('admin.guru.*'))
                            Data Guru
                        @elseif (request()->routeIs('admin.mata-pelajaran.*'))
                            Mata Pelajaran
                        @elseif (request()->routeIs('admin.agenda.*'))
                            Agenda Kegiatan
                        @else
                            @yield('page_title', 'Dashboard')
                        @endif

                        <span class="h-20px border-1 border-gray-200
                                     border-start ms-3 mx-2 me-1">
                        </span>

                    </h1>
                </div>
            </div>

            {{-- Toolbar --}}
            <div class="d-flex align-items-stretch flex-shrink-0">

                {{-- Toggle tema --}}
                <div class="d-flex align-items-center ms-1 ms-lg-3"
                    style="display: none !important;">

                    <button type="button"
                        id="kt_admin_theme_toggle"
                        class="btn btn-icon btn-icon-muted
                               btn-active-light btn-active-color-primary
                               w-30px h-30px w-md-40px h-md-40px"
                        aria-label="Mode terang aktif"
                        aria-pressed="false"
                        title="Mode terang aktif"
                        disabled>

                        <i id="kt_admin_theme_icon"
                            class="bi bi-moon-stars-fill fs-3"
                            aria-hidden="true"></i>
                    </button>
                </div>

                {{-- Menu profil admin --}}
                <div class="d-flex align-items-center ms-1 ms-lg-3"
                    id="kt_header_user_menu_toggle">

                    {{-- Foto / ikon profil --}}
                    <div class="cursor-pointer symbol symbol-30px symbol-md-40px"
                        data-kt-menu-trigger="click"
                        data-kt-menu-attach="parent"
                        data-kt-menu-placement="bottom-end"
                        aria-label="Menu profil admin">

                        <i class="bi bi-person-circle"
                            style="font-size: 30px;"></i>
                    </div>

                    {{-- Dropdown profil admin --}}
                    <div class="menu menu-sub menu-sub-dropdown menu-column
                                menu-rounded menu-gray-800 menu-state-bg
                                menu-state-primary fw-bold py-4 fs-6 w-275px"
                        data-kt-menu="true">

                        {{-- Informasi admin --}}
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex flex-column px-5 py-3">

                                <span class="fw-bolder fs-5">
                                    {{ session('admin_username', 'Admin') }}
                                </span>

                                <span class="text-muted fs-7 mt-1">
                                    Administrator
                                </span>
                            </div>
                        </div>

                        <div class="separator my-2"></div>

                        {{-- Dashboard --}}
                        <div class="menu-item px-5">
                            <a href="{{ route('admin.dashboard') }}"
                                class="menu-link px-5">
                                Dashboard
                            </a>
                        </div>

                        {{-- Data Guru --}}
                        <div class="menu-item px-5">
                            <a href="{{ route('admin.guru.index') }}"
                                class="menu-link px-5">
                                Data Guru
                            </a>
                        </div>

                        {{-- Mata Pelajaran --}}
                        <div class="menu-item px-5">
                            <a href="{{ route('admin.mata-pelajaran.index') }}"
                                class="menu-link px-5">
                                Mata Pelajaran
                            </a>
                        </div>

                        {{-- Jadwal Supervisi --}}
                        <div class="menu-item px-5">
                            <a href="{{ route('admin.supervisi.index') }}"
                                class="menu-link px-5">
                                Jadwal Supervisi
                            </a>
                        </div>

                        {{-- Agenda Kegiatan --}}
                        <div class="menu-item px-5">
                            <a href="{{ route('admin.agenda.index') }}"
                                class="menu-link px-5">
                                Agenda Kegiatan
                            </a>
                        </div>

                        <div class="separator my-2"></div>

                        {{-- Logout --}}
                        <div class="menu-item px-5">
                            <form method="POST"
                                action="{{ route('logout') }}"
                                id="header-logout-form">

                                @csrf

                                <button type="button"
                                    id="header-logout-button"
                                    class="menu-link px-5 border-0
                                           bg-transparent w-100 text-start">
                                    Keluar
                                </button>
                            </form>
                        </div>

                    </div>
                    {{--end::Dropdown profil admin--}}

                </div>

                {{-- Toggle menu header mobile --}}
                <div class="d-flex align-items-center d-lg-none ms-2 me-n3"
                    title="Tampilkan menu header">

                    <div class="btn btn-icon btn-active-light-primary
                                w-30px h-30px w-md-40px h-md-40px"
                        id="kt_header_menu_mobile_toggle">

                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24"
                                viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M13 11H3C2.4 11 2 10.6 2 10V9C2 8.4 2.4 8 3 8H13C13.6 8 14 8.4 14 9V10C14 10.6 13.6 11 13 11ZM22 5V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4V5C2 5.6 2.4 6 3 6H21C21.6 6 22 5.6 22 5Z"
                                    fill="currentColor" />
                                <path opacity="0.3"
                                    d="M21 16H3C2.4 16 2 15.6 2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21 16 21 16ZM14 20V19C14 18.4 13.6 18 13 18H3C2.4 18 2 18.4 2 19V20C2 20.6 2.4 21 3 21H13C13.6 21 14 20.6 14 20Z"
                                    fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>

            </div>
            {{--end::Toolbar--}}

        </div>
        <!--end::Wrapper-->

    </div>
    <!--end::Container-->

</div>
<!--end::Header-->

<style>
    #kt_header {
        min-height: 70px;
        border-bottom: 1px solid #d6e6f2;
        box-shadow: none;
    }

    #kt_header > .container-fluid {
        min-height: inherit;
        padding-right: 1.5rem;
        padding-left: 1.5rem;
    }

    #kt_header .page-title h1 {
        color: #1d3557 !important;
        font-family: Poppins, sans-serif;
        font-size: 1.125rem !important;
        letter-spacing: 0;
    }

    #kt_header #kt_header_user_menu_toggle > .symbol {
        display: inline-flex;
        width: 40px;
        height: 40px;
        align-items: center;
        justify-content: center;
        border-radius: 0.475rem;
        color: #1d3557;
        background-color: #eaf5fb;
    }

    #kt_header #kt_header_user_menu_toggle > .symbol i {
        font-size: 1.375rem !important;
    }

    #kt_header .menu-sub-dropdown .menu-link {
        min-height: 40px;
        border-radius: 0.375rem;
    }

    @media (max-width: 991.98px) {
        #kt_header {
            min-height: 64px;
        }

        #kt_header > .container-fluid {
            padding-right: 1rem;
            padding-left: 1rem;
        }
    }

    @media (max-width: 575.98px) {
        #kt_header .page-title h1 {
            font-size: 1rem !important;
        }
    }
</style>

{{-- Konfirmasi Logout dengan SweetAlert2 --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutButton = document.getElementById('header-logout-button');
        const logoutForm = document.getElementById('header-logout-form');

        if (!logoutButton || !logoutForm) {
            return;
        }

        logoutButton.addEventListener('click', function() {
            if (typeof Swal === 'undefined') {
                if (confirm('Yakin ingin logout?')) {
                    logoutForm.submit();
                }
                return;
            }

            Swal.fire({
                title: 'Yakin ingin logout?',
                text: 'Kamu akan keluar dari sesi saat ini.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    logoutForm.submit();
                }
            });
        });
    });
</script>
