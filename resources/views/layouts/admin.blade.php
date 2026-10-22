<!DOCTYPE html>
<html lang="id" data-admin-theme="dark">
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

    {{-- Tentukan tema sebelum halaman ditampilkan --}}
    <script>
        (function () {
            let theme = 'dark';

            try {
                theme = localStorage.getItem('siguru-admin-theme') || 'dark';
            } catch (error) {}

            document.documentElement.dataset.adminTheme = theme;

            if (theme === 'dark') {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-bs-theme', 'light');
            }

            document.querySelectorAll('[data-light-href][data-dark-href]')
                .forEach(function (stylesheet) {
                    const href = theme === 'dark'
                        ? stylesheet.dataset.darkHref
                        : stylesheet.dataset.lightHref;

                    if (href) {
                        stylesheet.href = href;
                    }
                });
        })();
    </script>

    <style>
        :root {
            --admin-bg: #f5f8fa;
            --admin-card: #ffffff;
            --admin-header: #ffffff;
            --admin-text: #181c32;
            --admin-muted: #7e8299;
            --admin-border: #eff2f5;
            --admin-soft: #f1f6ff;
        }

        html[data-admin-theme="dark"] {
            --admin-bg: #151521;
            --admin-card: #1e1e2d;
            --admin-header: #1e1e2d;
            --admin-text: #f5f5fa;
            --admin-muted: #9295b5;
            --admin-border: #2b2b40;
            --admin-soft: #252f48;
        }

        html,
        body {
            min-height: 100%;
            width: 100%;
            margin: 0;
            overflow-x: hidden;
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

        /* Header */
        #kt_header {
            background-color: var(--admin-header);
            border-bottom: 1px solid var(--admin-border);
        }

        html[data-admin-theme="dark"] #kt_header .text-dark,
        html[data-admin-theme="dark"] #kt_header .fw-bolder,
        html[data-admin-theme="dark"] #kt_header h1,
        html[data-admin-theme="dark"] #kt_header h2,
        html[data-admin-theme="dark"] #kt_header .menu-title {
            color: #f5f5fa !important;
        }

        /* Sidebar tetap mengikuti warna Metronic */
        #kt_aside {
            background-color: #1e1e2d;
        }

        #kt_aside .menu-link {
            font-family: Poppins, sans-serif;
            font-size: 13px;
        }

        #kt_aside .menu-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        #kt_aside .menu-icon i,
        #kt_aside .menu-icon .bi {
            font-size: 18px;
            line-height: 1;
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

        /* Teks mode gelap */
        html[data-admin-theme="dark"] #kt_content h1,
        html[data-admin-theme="dark"] #kt_content h2,
        html[data-admin-theme="dark"] #kt_content h3,
        html[data-admin-theme="dark"] #kt_content h4,
        html[data-admin-theme="dark"] #kt_content .text-dark,
        html[data-admin-theme="dark"] #kt_content .text-gray-900,
        html[data-admin-theme="dark"] #kt_content .text-gray-700 {
            color: #f5f5fa !important;
        }

        html[data-admin-theme="dark"] #kt_content .text-muted,
        html[data-admin-theme="dark"] #kt_content .text-gray-500,
        html[data-admin-theme="dark"] #kt_content .text-gray-600,
        html[data-admin-theme="dark"] #kt_content .text-gray-400 {
            color: var(--admin-muted) !important;
        }

        /* Form mode gelap */
        html[data-admin-theme="dark"] #kt_content .form-control,
        html[data-admin-theme="dark"] #kt_content .form-select {
            color: var(--admin-text);
            background-color: var(--admin-card);
            border-color: var(--admin-border);
        }

        html[data-admin-theme="dark"] #kt_content .form-control::placeholder {
            color: var(--admin-muted);
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

    </style>

    @stack('styles')
</head>

<body id="kt_body"
      class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed">

    {{-- Terapkan class tema pada body --}}
    <script>
        if (document.documentElement.dataset.adminTheme === 'dark') {
            document.body.classList.add('dark-mode');
        }
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

                {{-- Footer --}}
                <div class="footer py-4 d-flex flex-lg-column">
                    <div class="container-fluid d-flex flex-column flex-md-row
                                align-items-center justify-content-between
                                px-5 px-lg-8">

                        <span class="text-muted fs-7">
                            {{ date('Y') }} © SIGURU - SMP Negeri 2 Purwakarta
                        </span>

                        <span class="text-muted fs-7">
                            Panel Administrator
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- JavaScript Metronic --}}
    <script src="{{ asset('demo1/dist/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('demo1/dist/assets/js/scripts.bundle.js') }}"></script>

    {{-- SweetAlert2 untuk konfirmasi logout --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Pengaturan tema admin: satu pengendali tema --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.getElementById('kt_admin_theme_toggle');
            const icon = document.getElementById('kt_admin_theme_icon');

            function applyTheme(theme) {
                document.documentElement.dataset.adminTheme = theme;

                document.documentElement.setAttribute(
                    'data-bs-theme',
                    theme === 'dark' ? 'dark' : 'light'
                );

                document.body.classList.toggle('dark-mode', theme === 'dark');

                document.querySelectorAll('[data-light-href][data-dark-href]')
                    .forEach(function (stylesheet) {
                        const href = theme === 'dark'
                            ? stylesheet.dataset.darkHref
                            : stylesheet.dataset.lightHref;

                        if (href && stylesheet.href !== new URL(href, window.location.href).href) {
                            stylesheet.href = href;
                        }
                    });

                try {
                    localStorage.setItem('siguru-admin-theme', theme);
                } catch (error) {}

                if (icon) {
                    icon.className = theme === 'dark'
                        ? 'bi bi-sun-fill fs-3'
                        : 'bi bi-moon-stars-fill fs-3';
                }

                if (button) {
                    button.title = theme === 'dark'
                        ? 'Ganti ke mode terang'
                        : 'Ganti ke mode gelap';

                    button.setAttribute('aria-label', button.title);
                    button.setAttribute(
                        'aria-pressed',
                        String(theme === 'dark')
                    );
                }
            }

            let currentTheme = 'dark';

            try {
                currentTheme = localStorage.getItem('siguru-admin-theme') || 'dark';
            } catch (error) {}

            applyTheme(currentTheme);

            if (button) {
                button.addEventListener('click', function () {
                    const current =
                        document.documentElement.dataset.adminTheme || 'dark';

                    applyTheme(current === 'dark' ? 'light' : 'dark');
                });
            }

            /* Konfirmasi logout dari sidebar admin */
            const logoutButton = document.getElementById('admin-logout-button');
            const logoutForm = document.getElementById('admin-logout-form');

            if (logoutButton && logoutForm) {
                logoutButton.addEventListener('click', function (event) {
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
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            logoutForm.submit();
                        }
                    });
                });
            }

            /* Konfirmasi logout dari header jika tombol tersedia */
            const headerLogoutButton =
                document.getElementById('header-logout-button');
            const headerLogoutForm =
                document.getElementById('header-logout-form');

            if (headerLogoutButton && headerLogoutForm) {
                headerLogoutButton.addEventListener('click', function (event) {
                    event.preventDefault();

                    if (typeof Swal === 'undefined') {
                        headerLogoutForm.submit();
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
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            headerLogoutForm.submit();
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