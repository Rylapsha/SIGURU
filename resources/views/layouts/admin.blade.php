```blade
<!DOCTYPE html>
<html lang="id" data-admin-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Admin') - SIGURU</title>

    <link rel="shortcut icon" href="{{ asset('asset/img/logos.png') }}">

    {{-- Metronic Demo 1 --}}
    <link rel="stylesheet"
          href="{{ asset('demo1/dist/assets/plugins/global/plugins.bundle.css') }}">
    <link rel="stylesheet"
          href="{{ asset('demo1/dist/assets/css/style.bundle.css') }}">

    {{-- Terapkan tema sebelum halaman ditampilkan --}}
    <script>
        (function () {
            try {
                document.documentElement.dataset.adminTheme =
                    localStorage.getItem('siguru-admin-theme') || 'dark';
            } catch (error) {
                document.documentElement.dataset.adminTheme = 'dark';
            }
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
            width: 100%;
            min-height: 100%;
            margin: 0;
            overflow-x: hidden;
        }

        body {
            font-family: inherit;
            background: var(--admin-bg);
            color: var(--admin-text);
        }

        #kt_wrapper,
        #kt_content,
        #kt_post {
            background: var(--admin-bg) !important;
            color: var(--admin-text);
        }

        /* Header */
        #kt_header {
            background: var(--admin-header) !important;
            border-bottom: 1px solid var(--admin-border);
        }

        html[data-admin-theme="dark"] #kt_header,
        html[data-admin-theme="dark"] #kt_header .text-dark,
        html[data-admin-theme="dark"] #kt_header .fw-bolder,
        html[data-admin-theme="dark"] #kt_header h1,
        html[data-admin-theme="dark"] #kt_header h2 {
            color: #ffffff !important;
        }

        /* Sidebar mengikuti warna Metronic */
        #kt_aside {
            background: #1e1e2d !important;
        }

        #kt_aside .menu-title,
        #kt_aside .menu-link,
        #kt_aside .menu-link .menu-title,
        #kt_aside .menu-link .menu-icon,
        #kt_aside .menu-link i {
            font-family: inherit;
        }

        #kt_aside .menu-link {
            font-size: 13px;
        }

        #kt_aside .menu-icon {
            display: flex;
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
            background: var(--admin-card) !important;
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            box-shadow: none;
        }

        #kt_content .card-header {
            background: transparent !important;
            border-color: var(--admin-border) !important;
        }

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

        html[data-admin-theme="dark"] #kt_content .form-control,
        html[data-admin-theme="dark"] #kt_content .form-select {
            color: var(--admin-text);
            background-color: var(--admin-card);
            border-color: var(--admin-border);
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
            background: var(--admin-soft);
            color: #009ef7;
            cursor: pointer;
            transition: background-color .2s ease;
        }

        #kt_admin_theme_toggle:hover {
            background: var(--admin-border);
        }

        #kt_admin_theme_toggle i {
            font-size: 18px;
            line-height: 1;
        }

        /* Statistik dashboard */
        .admin-stat-card {
            height: 100%;
            min-height: 110px;
        }

        .admin-page-heading {
            margin-bottom: 24px;
        }

        /* Footer */
        .footer {
            background: var(--admin-bg);
            border-top: 1px solid var(--admin-border);
        }

        /* Responsif */
        @media (max-width: 991px) {
            #kt_aside {
                z-index: 105;
            }
        }

        @media (max-width: 767px) {
            .admin-page-heading {
                margin-bottom: 16px;
            }

            #kt_content .container-fluid {
                padding-right: 16px !important;
                padding-left: 16px !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body id="kt_body"
      class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed">

    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid" id="kt_wrapper">

            {{-- Sidebar Admin --}}
            @include('layouts.partials.aside-admin')

            <div class="wrapper d-flex flex-column flex-row-fluid"
                 id="kt_content_wrapper">

                {{-- Header Admin --}}
                @include('layouts.partials.header-admin')

                {{-- Konten halaman --}}
                <div class="content d-flex flex-column flex-column-fluid"
                     id="kt_content">

                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <div class="container-fluid px-5 px-lg-8 py-5">
                            @yield('content')
                        </div>
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

    {{-- Pengaturan tema --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.getElementById('kt_admin_theme_toggle');
            const icon = document.getElementById('kt_admin_theme_icon');

            function applyTheme(theme) {
                document.documentElement.dataset.adminTheme = theme;

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

                    button.setAttribute(
                        'aria-label',
                        button.title
                    );
                }
            }

            let theme = 'dark';

            try {
                theme = localStorage.getItem('siguru-admin-theme') || 'dark';
            } catch (error) {}

            applyTheme(theme);

            if (button) {
                button.addEventListener('click', function () {
                    const current =
                        document.documentElement.dataset.adminTheme || 'dark';

                    applyTheme(current === 'dark' ? 'light' : 'dark');
                });
            }
        });
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
```