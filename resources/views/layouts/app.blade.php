<!DOCTYPE html>
<html lang="id">

<head>
    <style> 
    /* =========================
       sider bayangan 
    ========================= */
    #kt_aside,
    #kt_app_sidebar,
    .aside {
        border-right: 2px solid #ffffff !important;
        box-shadow: 4px 0 12px rgba(69, 86, 112, 0.88) !important;
        z-index: 100 !important;
    }
    </style>
    <style>
    /* =========================
       HEADER
    ========================= */

    #kt_header {
        background-color: #1E3A5F !important;
    }

    /* Judul Dashboard / Profile */
    #kt_header .page-heading {
        color: #FFFFFF !important;
        font-family: 'Poppins', sans-serif !important;
        font-weight: 600 !important;
    }

    /* Nama guru */
    #kt_header .text-gray-900 {
        color: #FFFFFF !important;
    }

    /* Tulisan Guru */
    #kt_header .text-muted {
        color: #D9E4F0 !important;
    }

    /* Tombol menu mobile */
    #kt_header #kt_aside_mobile_toggle {
        color: #FFFFFF !important;
    }

    #kt_header #kt_aside_mobile_toggle:hover {
        background-color: rgba(8, 47, 107, 0.88) !important;
    }

    
</style>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'SIGURU - Direktori Guru SMP Negeri 2 Purwakarta')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Metronic Demo 1 --}}
    <link rel="stylesheet" href="{{ asset('demo1/plugins/global/plugins.bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('demo1/css/style.bundle.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">\
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @yield('styles')
    
</head>

<body
    id="kt_body"
    class="header-fixed header-tablet-and-mobile-fixed aside-fixed aside-enabled">

    {{-- Page --}}
    <div class="d-flex flex-column flex-root">

        {{-- Page --}}
        <div class="page d-flex flex-row flex-column-fluid">

            {{-- Sidebar --}}
            @include('layouts.partials.aside')

            {{-- Wrapper --}}
            <div
                class="wrapper d-flex flex-column flex-row-fluid"
                id="kt_wrapper">

                {{-- Header --}}
                @include('layouts.partials.header')

                {{-- Main Content --}}
                <div
                    class="content d-flex flex-column flex-column-fluid"
                    id="kt_content">
                    @yield('content')
                </div>

                {{-- Footer --}}
                @include('layouts.partials.footer')

            </div>
            {{-- End Wrapper --}}

        </div>
        {{-- End Page --}}

    </div>
    {{-- End Page --}}


    {{-- Scroll Top --}}
    <div
        id="kt_scrolltop"
        class="scrolltop"
        data-kt-scrolltop="true">
        <i class="ki-duotone ki-arrow-up">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>


    {{-- Metronic JS --}}
    <script src="{{ asset('demo1/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('demo1/js/scripts.bundle.js') }}"></script>

    @yield('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const logoutButton = document.getElementById('logout-button');
        const logoutForm = document.getElementById('logout-form');

        if (logoutButton && logoutForm) {

            logoutButton.addEventListener('click', function () {

                Swal.fire({
                    title: 'Yakin ingin logout?',
                    text: 'Kamu akan keluar dari akun SIGURU.',
                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal',

                    reverseButtons: true,
                    buttonsStyling: false,

                    customClass: {
                        confirmButton: 'btn btn-danger ms-2',
                        cancelButton: 'btn btn-light'
                    }

                }).then((result) => {

                    if (result.isConfirmed) {
                        logoutForm.submit();
                    }

                });

            });

        }

    });
</script>
</body>

</html>