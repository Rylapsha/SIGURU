
<!DOCTYPE html>
<html lang="id" data-admin-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Admin') - SIGURU</title>

    <link rel="shortcut icon" href="{{ asset('asset/img/logos.png') }}">

    {{-- Font Metronic --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

    {{-- CSS Metronic Demo 1 --}}
    <link id="kt_plugins_stylesheet"
        rel="stylesheet"
        href="{{ asset('demo1/dist/assets/plugins/global/plugins.bundle.css') }}"
        data-light-href="{{ asset('demo1/dist/assets/plugins/global/plugins.bundle.css') }}"
        data-dark-href="{{ asset('demo1/dist/assets/plugins/global/plugins.dark.bundle.css') }}">

    <link id="kt_theme_stylesheet"
        rel="stylesheet"
        href="{{ asset('demo1/dist/assets/css/style.bundle.css') }}"
        data-light-href="{{ asset('demo1/dist/assets/css/style.bundle.css') }}"
        data-dark-href="{{ asset('demo1/dist/assets/css/style.dark.bundle.css') }}">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @include('layouts.partials.date-range-picker-styles')

    {{-- Tentukan tema sebelum halaman ditampilkan --}}
    <script>
        (function() {
            document.documentElement.dataset.adminTheme = 'light';
            document.documentElement.setAttribute('data-bs-theme', 'light');
            document.body.classList.remove('dark-mode');

            document.querySelectorAll('[data-light-href][data-dark-href]').forEach(function(stylesheet) {
                stylesheet.href = stylesheet.dataset.lightHref || stylesheet.href;
                stylesheet.media = 'all';
            });
        })();
    </script>

    <style>
        :root {
            --admin-bg: #e9eef5;
            --admin-card: #ffffff;
            --admin-header: #ffffff;
            --admin-text: #1f3c57;
            --admin-muted: #5b7c9c;
            --admin-border: #CFDCEB;
            --admin-soft: #EAF0F8;
        }

        html,
        body {
            min-height: 100%;
            width: 100%;
            margin: 0;
            overflow-x: hidden;
        }

        body#kt_body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #kt_body > .flex-root {
            min-height: 100vh;
            width: 100%;
            display: flex;
            flex: 1 0 auto;
            flex-direction: column;
        }

        #kt_body .page {
            min-height: 100vh;
            flex: 1 0 auto;
        }

        #kt_wrapper {
            min-height: 100vh;
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
        }

        #kt_page_content {
            flex: 1 0 auto;
        }

        #kt_footer {
            flex: 0 0 auto;
            margin-top: auto;
        }

        body {
            font-family: Poppins, sans-serif;
            background-color: var(--admin-bg);
            color: var(--admin-text);
        }

        /* Struktur utama */
        #kt_wrapper,
        #kt_content,
        #kt_post,
        #kt_page_content {
            min-width: 0;
        }

        #kt_wrapper,
        #kt_content,
        #kt_post {
            background-color: var(--admin-bg);
            color: var(--admin-text);
        }

        #kt_page_content {
            background-color: var(--admin-bg);
        }

        /* Header */
        #kt_header {
            background-color: var(--admin-header);
            border-bottom: 1px solid var(--admin-border);
        }

        /* Sidebar admin menggunakan latar putih dan teks gelap. */
        #kt_aside {
            background-color: #fff !important;
            border-right: 1px solid var(--admin-border);
        }

        #kt_aside .aside-logo {
            background-color: #2f6eea !important;
            border-bottom: 1px solid #2459c4;
        }

        #kt_aside .aside-logo .aside-toggle {
            color: #fff !important;
        }

        #kt_aside .menu-section,
        #kt_aside .menu-title,
        #kt_aside .menu-link,
        #kt_aside .menu-icon,
        #kt_aside .svg-icon {
            color: #1d3557 !important;
        }

        #kt_aside .aside-toggle svg [fill]:not(.permanent):not(g) {
            fill: #fff !important;
        }

        #kt_aside .separator {
            border-bottom-color: #dfeeff !important;
        }

        #kt_aside .menu-item:not(.here) .menu-link:hover:not(.disabled):not(.active):not(.here),
        #kt_aside .menu-item.show>.menu-link,
        #kt_aside .menu-item.here>.menu-link,
        #kt_aside .menu-item .menu-link.active {
            background-color: rgba(47, 110, 234, 0.12) !important;
            color: #3699ff !important;
        }

        #kt_aside .menu-item:not(.here) .menu-link:hover:not(.disabled):not(.active):not(.here) .menu-title,
        #kt_aside .menu-item.show>.menu-link .menu-title,
        #kt_aside .menu-item.here>.menu-link .menu-title,
        #kt_aside .menu-item .menu-link.active .menu-title {
            color: #3699ff !important;
        }

        #kt_aside .menu-item:not(.here) .menu-link:hover:not(.disabled):not(.active):not(.here) .menu-icon i,
        #kt_aside .menu-item.show>.menu-link .menu-icon i,
        #kt_aside .menu-item.here>.menu-link .menu-icon i,
        #kt_aside .menu-item .menu-link.active .menu-icon i,
        #kt_aside .menu-item:not(.here) .menu-link:hover:not(.disabled):not(.active):not(.here) .menu-icon .svg-icon svg [fill]:not(.permanent):not(g),
        #kt_aside .menu-item.show>.menu-link .menu-icon .svg-icon svg [fill]:not(.permanent):not(g),
        #kt_aside .menu-item.here>.menu-link .menu-icon .svg-icon svg [fill]:not(.permanent):not(g),
        #kt_aside .menu-item .menu-link.active .menu-icon .svg-icon svg [fill]:not(.permanent):not(g) {
            fill: #3699ff !important;
            color: #3699ff !important;
        }

        /* Area konten */
        #kt_content .card {
            color: var(--admin-text);
            background-color: var(--admin-card);
            border-color: var(--admin-border);
            border-radius: 12px;
        }

        #kt_content .card-header {
            background-color: transparent;
            border-bottom-color: var(--admin-border);
        }

        /* Tabel seluruh halaman admin */
        #kt_content .table {
            background-color: #ffffff !important;
            color: var(--admin-text);
            border-color: var(--admin-border);
        }

        #kt_content .table thead,
        #kt_content .table thead tr,
        #kt_content .table thead th {
            background-color: #ffffff !important;
            color: #1f3c57 !important;
            border-color: var(--admin-border) !important;
            font-weight: 700;
        }

        #kt_content .table tbody,
        #kt_content .table tbody tr,
        #kt_content .table tbody td {
            background-color: #ffffff !important;
            color: var(--admin-text);
            border-color: var(--admin-border) !important;
        }

        #kt_content .table tbody tr:hover,
        #kt_content .table tbody tr:hover td {
            background-color: #F3F6FA !important;
        }

        #kt_content .table-striped>tbody>tr:nth-of-type(odd)>* {
            background-color: #F8FAFC !important;
            color: var(--admin-text);
        }

        #kt_content .table thead th {
            font-weight: 700;
        }

        /* Tombol tema */
        #kt_admin_theme_toggle {
            display: inline-flex;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 10px;
            background-color: var(--admin-soft);
            color: #009ef7;
            cursor: pointer;
            transition: background-color .2s ease;
        }

        #kt_admin_theme_toggle:hover {
            background-color: var(--admin-border);
        }

        #kt_admin_theme_toggle i {
            font-size: 18px;
            line-height: 1;
        }

        /* Kartu statistik */
        .admin-stat-card {
            height: 100%;
            min-height: 110px;
        }

        .admin-page-heading {
            margin-bottom: 24px;
        }

        /* Footer */
        .footer {
            background-color: var(--admin-bg);
            border-top: 1px solid var(--admin-border);
        }

        /* Transisi tema */
        body.theme-transitioning,
        body.theme-transitioning * {
            transition:
                background-color .25s ease,
                color .25s ease,
                border-color .25s ease !important;
        }

        @media (prefers-reduced-motion: reduce) {

            body.theme-transitioning,
            body.theme-transitioning * {
                transition: none !important;
            }
        }

        /* Responsif */
        @media (max-width: 991.98px) {
            #kt_aside {
                z-index: 105;
            }
        }

        @media (max-width: 767.98px) {
            .admin-page-heading {
                margin-bottom: 16px;
            }

            #kt_content .container-fluid {
                padding-right: 16px !important;
                padding-left: 16px !important;
            }
        }


        /* Animasi pergantian tema admin */
        body,
        #kt_aside,
        #kt_header,
        #kt_content,
        #kt_content .card,
        #kt_content .card-header,
        #kt_content .card-body,
        #kt_content .card-footer,
        #kt_content .form-control,
        #kt_content .form-select,
        #kt_content .table,
        #kt_content .btn,
        #kt_content .text-gray-800,
        #kt_content .text-gray-600 {
            transition:
                background-color 0.35s ease,
                color 0.35s ease,
                border-color 0.35s ease,
                box-shadow 0.35s ease;
        }

        /* Animasi ikon tombol tema */
        #kt_admin_theme_icon {
            display: inline-block;
            transition: transform 0.35s ease;
        }

        #kt_admin_theme_toggle:hover #kt_admin_theme_icon {
            transform: rotate(20deg) scale(1.1);
        }

        /* Select konsisten dengan custom dropdown halaman dokumen */
        select.form-select,
        .form-select,
        select:not([multiple]):not([size]) {
            color-scheme: light;
            -webkit-appearance: none;
            appearance: none;
            background-color: #fff;
            border: 1px solid #e4e6ef;
            border-radius: 10px;
            color: #1f2a37;
            font-size: 0.95rem;
            font-weight: 500;
            line-height: 1.5;
            min-height: 46px;
            padding: 0.75rem 2.75rem 0.75rem 1rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none'%3E%3Cpath d='M4.5 6.5L8 10L11.5 6.5' stroke='%236b7280' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1rem;
            box-shadow: 0 0 0 rgba(0, 0, 0, 0);
            transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
        }

        select.form-select option,
        .form-select option,
        select option,
        select.form-select-solid option,
        .form-select-solid option,
        optgroup {
            background-color: #ffffff !important;
            color: #1f2a37 !important;
        }

        select.form-select option:checked,
        .form-select option:checked,
        select option:checked,
        select.form-select-solid option:checked,
        .form-select-solid option:checked {
            background: linear-gradient(0deg, #eef5ff 0%, #eef5ff 100%) !important;
            color: #0d6efd !important;
        }

        select.form-select option:hover,
        .form-select option:hover,
        select option:hover,
        select.form-select-solid option:hover,
        .form-select-solid option:hover,
        select.form-select option:focus,
        .form-select option:focus,
        select option:focus,
        select.form-select-solid option:focus,
        .form-select-solid option:focus {
            background: #eef5ff !important;
            color: #0d6efd !important;
        }

        select.form-select:focus,
        .form-select:focus,
        select:not([multiple]):not([size]):focus {
            border-color: #b7d8ff;
            box-shadow: 0 0 0 4px rgba(40, 120, 255, 0.08);
            outline: none;
        }

        select.form-select:hover,
        .form-select:hover,
        select:not([multiple]):not([size]):hover {
            border-color: #d4d9e8;
        }

        select.form-select option,
        .form-select option {
            color: #1f2a37;
        }

        .custom-select-trigger,
        .custom-select-trigger.form-control {
            min-height: 46px;
            border: 1px solid #e4e6ef;
            border-radius: 10px;
            background: #fff;
            color: #1f2a37;
            box-shadow: none;
            padding: 0.75rem 2.75rem 0.75rem 1rem;
        }

        .custom-select-trigger:focus,
        .custom-select-trigger.form-control:focus {
            border-color: #b7d8ff;
            box-shadow: 0 0 0 4px rgba(40, 120, 255, 0.08);
            outline: none;
        }

        select.form-select-solid,
        select.form-select-solid.form-select,
        .form-select-solid {
            -webkit-appearance: none;
            appearance: none;
            background-color: #fff !important;
            border: 1px solid #e4e6ef !important;
            border-radius: 10px !important;
            color: #1f2a37 !important;
            font-size: 0.95rem;
            font-weight: 500;
            line-height: 1.5;
            min-height: 46px;
            padding: 0.75rem 2.75rem 0.75rem 1rem !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none'%3E%3Cpath d='M4.5 6.5L8 10L11.5 6.5' stroke='%236b7280' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 1rem center !important;
            background-size: 1rem !important;
            box-shadow: none !important;
            transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
        }

        select.form-select-solid:focus,
        select.form-select-solid.form-select:focus,
        .form-select-solid:focus {
            border-color: #b7d8ff !important;
            box-shadow: 0 0 0 4px rgba(40, 120, 255, 0.08) !important;
            outline: none !important;
        }

        select.form-select-solid option,
        .form-select-solid option {
            color: #1f2a37 !important;
        }
    </style>

    @stack('styles')
</head>

<body id="kt_body"
    class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed">

    {{-- Terapkan class tema pada body --}}
    <script>
        document.documentElement.dataset.adminTheme = 'light';
        document.documentElement.setAttribute('data-bs-theme', 'light');
        document.body.classList.remove('dark-mode');

        document.querySelectorAll('[data-light-href][data-dark-href]').forEach(function(stylesheet) {
            stylesheet.href = stylesheet.dataset.lightHref || stylesheet.href;
            stylesheet.media = 'all';
        });
    </script>

    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">

            {{-- Sidebar Admin --}}
            @include('layouts.partials.aside-admin')

            {{-- Wrapper halaman --}}
            <div class="wrapper d-flex flex-column flex-row-fluid"
                id="kt_wrapper">

                {{-- Header Admin --}}
                @include('layouts.partials.header-admin')

                {{-- Konten halaman --}}
                <div class="flex-column-fluid" id="kt_page_content">
                    <div class="container-fluid px-5 px-lg-8 py-5">
                        @yield('content')
                    </div>
                </div>

                @include('layouts.partials.footer')

            </div>
        </div>
    </div>

    {{-- JavaScript Metronic --}}
    <script src="{{ asset('demo1/dist/assets/plugins/global/plugins.bundle.js') }}"></script>
    @include('layouts.partials.date-range-picker-scripts')
    <script src="{{ asset('demo1/dist/assets/js/scripts.bundle.js') }}"></script>

    {{-- SweetAlert2 untuk konfirmasi logout --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('layouts.partials.flash-alerts')

    {{-- Pengaturan tema admin: satu pengendali tema --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.getElementById('kt_admin_theme_toggle');
            const icon = document.getElementById('kt_admin_theme_icon');

            document.documentElement.dataset.adminTheme = 'light';
            document.documentElement.setAttribute('data-bs-theme', 'light');
            document.body.classList.remove('dark-mode');

            if (icon) {
                icon.className = 'bi bi-moon-stars-fill fs-3';
            }

            if (button) {
                button.disabled = true;
                button.style.display = 'none';
                button.title = 'Mode terang aktif';
                button.setAttribute('aria-label', 'Mode terang aktif');
                button.setAttribute('aria-pressed', 'false');
            }

            const logoutButton = document.getElementById('admin-logout-button');
            const logoutForm = document.getElementById('admin-logout-form');

            if (logoutButton && logoutForm) {
                logoutButton.addEventListener('click', function(event) {
                    event.preventDefault();

                    if (typeof Swal === 'undefined') {
                        logoutForm.submit();
                        return;
                    }

                    Swal.fire({
                        title: 'Yakin ingin logout?',
                        text: 'Kamu akan keluar dari sesi admin.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Logout',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            logoutForm.submit();
                        }
                    });
                });
            }
        });
    </script>

    @stack('scripts')
    @yield('scripts')
</body>

</html>
