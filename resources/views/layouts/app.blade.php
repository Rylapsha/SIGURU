<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic - Bootstrap 5 HTML, VueJS, React, Angular & Laravel Admin Dashboard Theme
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
    <base href="">
    <title>@yield('title', 'SIGURU - Direktori Guru SMP Negeri 2 Purwakarta')</title>
    <meta charset="utf-8" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <link id="kt_fullcalendar_stylesheet" href="{{ asset('demo1/dist/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" data-light-href="{{ asset('demo1/dist/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" data-dark-href="{{ asset('demo1/dist/assets/plugins/custom/fullcalendar/fullcalendar.dark.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link id="kt_datatables_stylesheet" href="{{ asset('demo1/dist/assets/plugins/custom/datatables/datatables.bundle.css') }}" data-light-href="{{ asset('demo1/dist/assets/plugins/custom/datatables/datatables.bundle.css') }}" data-dark-href="{{ asset('demo1/dist/assets/plugins/custom/datatables/datatables.dark.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link id="kt_plugins_stylesheet" href="{{ asset('demo1/dist/assets/plugins/global/plugins.bundle.css') }}" data-light-href="{{ asset('demo1/dist/assets/plugins/global/plugins.bundle.css') }}" data-dark-href="{{ asset('demo1/dist/assets/plugins/global/plugins.dark.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link id="kt_theme_stylesheet" href="{{ asset('demo1/dist/assets/css/style.bundle.css') }}" data-light-href="{{ asset('demo1/dist/assets/css/style.bundle.css') }}" data-dark-href="{{ asset('demo1/dist/assets/css/style.dark.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    @include('layouts.partials.date-range-picker-styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--end::Global Stylesheets Bundle-->
    <script>
        if (localStorage.getItem('sikap-theme-mode') === 'dark') {
            document.querySelectorAll('[data-light-href][data-dark-href]').forEach((stylesheet) => {
                stylesheet.href = stylesheet.dataset.darkHref;
            });
        }
    </script>
    <style>
        html,
        body {
            min-height: 100%;
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
            background-color: var(--siguru-bg);
            border-top: 1px solid #CFDCEB;
        }

        body.theme-transitioning,
        body.theme-transitioning * {
            transition: background-color 280ms ease-in-out, color 280ms ease-in-out, border-color 280ms ease-in-out, box-shadow 280ms ease-in-out, fill 280ms ease-in-out, stroke 280ms ease-in-out !important;
        }

        @media (prefers-reduced-motion: reduce) {

            body.theme-transitioning,
            body.theme-transitioning * {
                transition-duration: 1ms !important;
            }
        }

        body.aside-minimize .aside-logo .d-flex.align-items-center.gap-3 {
            justify-content: center;
            gap: 0;
        }

        body.aside-minimize .sidebar-logo-text {
            display: none !important;
        }

        body.aside-minimize .sidebar-main-logo {
            display: block;
            margin: 0 auto;
        }
    </style>
    <style>
        .dashboard-container {
            padding-bottom: 2rem;
        }

        .dashboard-welcome {
            color: var(--bs-gray-900, #181c32);
            font-size: clamp(1.5rem, 2vw, 2rem);
            letter-spacing: -0.5px;

        }

        .dashboard-description {
            color: var(--bs-gray-600, #7e8299);
            line-height: 1.7;
            display: inline-block;
            max-width: 100%;
            width: 100%;
        }

        .dashboard-layout {
            display: grid;
            grid-template-columns: minmax(0, 7fr) minmax(0, 4.3fr);
            gap: 1.25rem;
            align-items: start;
        }

        .dashboard-main,
        .dashboard-agenda {
            min-width: 0;
        }

        .dashboard-panel {
            border: 1px solid var(--bs-border-color, #dce8f8);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(35, 75, 130, 0.025);
            background: var(--bs-card-bg, #fff);
            color: var(--bs-body-color, #181c32);
        }

        .dashboard-panel .card-header,
        .dashboard-panel .card-body,
        .dashboard-panel .card-footer {
            color: var(--bs-body-color, #181c32);
        }

        .dashboard-heading,
        .dashboard-agenda-title {
            color: var(--bs-gray-900, #181c32) !important;
        }

        .dashboard-muted {
            color: var(--bs-gray-600, #7e8299);
        }

        .dashboard-section-icon {
            width: 48px;
            height: 48px;
            border-radius: 15px;
            background: #e6f0ff;
            color: #2478ed;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .dashboard-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 34px;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.4;
            white-space: nowrap;
        }

        .dashboard-chart-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            padding: 1rem 0;
        }

        .dashboard-donut-chart {
            width: 100%;
            max-width: 230px;
            aspect-ratio: 1 / 1;
            position: relative;
            margin: 0 auto;
        }

        .dashboard-donut-chart canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .dashboard-donut-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            pointer-events: none;
            white-space: nowrap;
        }

        .dashboard-donut-percentage {
            color: var(--bs-gray-900, #181c32);
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1.25;
        }

        .dashboard-detail-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            border-radius: 22px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 600;
        }

        .dashboard-document-details[hidden] {
            display: none !important;
        }

        .dashboard-detail-panel {
            border: 1px solid var(--bs-border-color, #e0e9f6);
            border-radius: 10px;
            background: var(--bs-card-bg, #fff);
            color: var(--bs-body-color, #181c32);
            padding: 1.25rem;
            animation: dashboardDetailIn 0.2s ease;
        }

        .dashboard-detail-row {
            display: grid;
            grid-template-columns: minmax(110px, 0.8fr) minmax(80px, 1fr) 16px;
            align-items: center;
            gap: 1rem;
        }

        .dashboard-detail-label {
            color: var(--bs-gray-700, #5e6278);
            overflow-wrap: anywhere;
        }

        .dashboard-detail-row>div:first-child {
            min-width: 0;
        }

        .dashboard-detail-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .detail-dot-success {
            background: #10a779;
        }

        .detail-dot-danger {
            background: #f04452;
        }

        .dashboard-detail-progress {
            min-width: 0;
        }

        .dashboard-semester-card {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.15rem;
            border: 1px solid var(--bs-border-color, #e2edfc);
            border-radius: 10px;
            background: var(--bs-secondary-bg, #f5f9ff);
            color: var(--bs-body-color, #181c32);
        }

        .dashboard-semester-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #dfedff;
            color: #2478ed;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .dashboard-agenda-card .card-body {
            padding-top: 1rem;
        }

        .dashboard-agenda-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            min-width: 0;
            padding: 0.25rem;
            text-decoration: none;
            color: inherit;
            border-radius: 8px;
            transition: background 0.2s ease;
        }

        .dashboard-agenda-item:hover {
            color: inherit;
            background: var(--bs-secondary-bg, #f5f9ff);
        }

        .dashboard-agenda-date {
            width: 55px;
            min-width: 55px;
            min-height: 66px;
            border-radius: 10px;
            background: #edf5ff;
            color: #2478ed;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 7px 3px;
        }

        .dashboard-agenda-day {
            font-size: 1.25rem;
            font-weight: 800;
            line-height: 1.3;
        }

        .dashboard-agenda-month {
            font-size: 0.75rem;
            font-weight: 600;
        }

        .dashboard-agenda-arrow {
            color: #8ca2c2;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            flex-shrink: 0;
        }

        .dashboard-agenda-footer {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
            border-radius: 9px;
            background: var(--bs-secondary-bg, #f1f7ff);
        }

        #supervisi-status,
        #agenda-mendatang,
        #kelengkapan-statistik {
            scroll-margin-top: 100px;
        }

        /* Mode gelap */
        [data-bs-theme="dark"] .dashboard-welcome,
        [data-bs-theme="dark"] .dashboard-heading,
        [data-bs-theme="dark"] .dashboard-donut-percentage,
        [data-bs-theme="dark"] .dashboard-agenda-title {
            color: #f5f8ff !important;
        }

        [data-bs-theme="dark"] .dashboard-description,
        [data-bs-theme="dark"] .dashboard-muted,
        [data-bs-theme="dark"] .dashboard-detail-label {
            color: #b5b5c3 !important;
        }

        [data-bs-theme="dark"] .dashboard-panel,
        [data-bs-theme="dark"] .dashboard-detail-panel {
            background: #1e1e2d;
            border-color: #343448;
        }

        [data-bs-theme="dark"] .dashboard-semester-card {
            background: #252538;
            border-color: #343448;
        }

        [data-bs-theme="dark"] .dashboard-agenda-date,
        [data-bs-theme="dark"] .dashboard-agenda-footer {
            background: #252d41;
        }

        [data-bs-theme="dark"] .dashboard-agenda-item:hover {
            background: #252538;
        }

        [data-bs-theme="dark"] .dashboard-section-icon,
        [data-bs-theme="dark"] .dashboard-semester-icon {
            background: #252d41;
            color: #79aaff;
        }

        [data-bs-theme="dark"] .dashboard-agenda-arrow {
            color: #a1a5b7;
        }

        [data-bs-theme="dark"] .dashboard-panel .separator {
            border-color: #343448 !important;
        }

        [data-bs-theme="dark"] .dashboard-panel .progress {
            background-color: #343448;
        }

        [data-bs-theme="dark"] .dashboard-detail-panel .bg-gray-300 {
            background-color: #45455a !important;
        }

        @keyframes dashboardDetailIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1399.98px) {
            .dashboard-layout {
                grid-template-columns: minmax(0, 7fr) minmax(0, 4fr);
                gap: 1rem;
            }

            .dashboard-donut-chart {
                max-width: 210px;
            }
        }

        @media (max-width: 1199.98px) {
            .dashboard-layout {
                grid-template-columns: minmax(0, 1fr);
            }

            .dashboard-agenda {
                width: 100%;
            }

            .dashboard-donut-chart {
                max-width: 230px;
            }
        }

        @media (max-width: 767.98px) {
            .dashboard-container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .dashboard-welcome {
                font-size: 1.4rem;
            }

            .dashboard-description {
                font-size: 0.85rem !important;
            }

            .dashboard-panel .card-header {
                flex-wrap: wrap;
                gap: 1rem;
            }

            .dashboard-panel .card-toolbar {
                width: 100%;
            }

            .dashboard-panel .card-toolbar .dashboard-action-btn {
                width: 100%;
            }

            .dashboard-donut-chart {
                max-width: 195px;
            }

            .dashboard-detail-panel {
                padding: 1rem;
            }

            .dashboard-detail-row {
                grid-template-columns: minmax(95px, 0.9fr) minmax(50px, 1fr) 16px;
                gap: 0.65rem;
            }

            .dashboard-agenda-item {
                gap: 0.75rem;
            }

            .dashboard-agenda-date {
                width: 48px;
                min-width: 48px;
                min-height: 60px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .dashboard-detail-panel {
                animation: none;
                transition: none;
            }
        }

        /* Select konsisten dengan custom dropdown halaman dokumen */
        select.form-select,
        .form-select,
        select:not([multiple]):not([size]) {
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
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed">
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            @include('layouts.partials.aside')
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                @include('layouts.partials.header')

                <div class="flex-column-fluid" id="kt_page_content">
                    @yield('content')
                </div>

                @include('layouts.partials.footer')
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->
    @yield('modals')

    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>

    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{ asset('demo1/dist/assets/plugins/global/plugins.bundle.js') }}"></script>
    @include('layouts.partials.date-range-picker-scripts')
    <script src="{{ asset('demo1/dist/assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->

    <!--begin::Page Vendors Javascript(used by this page)-->
    <script src="{{ asset('demo1/dist/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
    <script src="{{ asset('demo1/dist/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!--end::Page Vendors Javascript-->

    <!--begin::Page Custom Javascript(used by this page)-->
    <script src="{{ asset('demo1/dist/assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('demo1/dist/assets/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('demo1/dist/assets/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('demo1/dist/assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('demo1/dist/assets/js/custom/utilities/modals/create-app.js') }}"></script>
    <script src="{{ asset('demo1/dist/assets/js/custom/utilities/modals/users-search.js') }}"></script>
    <!--end::Page Custom Javascript-->

    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
        document.body.classList.remove('dark-mode');
        document.querySelectorAll('[data-light-href][data-dark-href]').forEach((stylesheet) => {
            stylesheet.media = 'all';
            if (stylesheet.dataset.lightHref) {
                stylesheet.href = stylesheet.dataset.lightHref;
            }
        });
        try {
            localStorage.setItem('sikap-theme-mode', 'light');
        } catch (error) {
            // ignore storage errors
        }
    </script>

    {{-- Script khusus setiap halaman, termasuk Date Range Picker --}}
    @yield('scripts')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('layouts.partials.flash-alerts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutButton = document.getElementById('logout-button');
            const logoutForm = document.getElementById('logout-form');

            if (logoutButton && logoutForm) {
                logoutButton.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Yakin ingin logout?',
                        text: 'Kamu akan keluar dari sesi saat ini.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Logout',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            logoutForm.submit();
                        }
                    });
                });
            }
        });
    </script>
    <style>
        :root {
            --siguru-bg: #e9eef5;
            --siguru-surface: #ffffff;
            --siguru-surface-alt: #edf5ff;
            --siguru-border: #dfeeff;
            --siguru-text: #1d3557;
            --siguru-muted: #59749a;
            --siguru-primary: #2f6eea;
            --siguru-primary-soft: rgba(47, 110, 234, 0.12);
        }

        body {
            background: var(--siguru-bg);
            color: var(--siguru-text);
        }

        #kt_wrapper,
        #kt_page_content {
            background: var(--siguru-bg);
        }

        #kt_aside.aside-light {
            background: #fff !important;
            border-right: 1px solid var(--siguru-border) !important;
            color: var(--siguru-text) !important;
        }

        #kt_aside.aside-light .aside-logo {
            background: #fff !important;
        }

        #kt_aside.aside-light .menu-title,
        #kt_aside.aside-light .menu-section,
        #kt_aside.aside-light .menu-link,
        #kt_aside.aside-light .menu-icon,
        #kt_aside.aside-light .svg-icon {
            color: var(--siguru-text) !important;
        }

        #kt_aside.aside-light .menu-link {
            border-radius: 10px;
            color: var(--siguru-text) !important;
        }

        #kt_aside.aside-light .menu-link.active,
        #kt_aside.aside-light .menu-link:hover {
            background: var(--siguru-primary-soft) !important;
            color: var(--siguru-primary) !important;
            box-shadow: inset 0 0 0 1px rgba(47, 110, 234, 0.08);
        }

        #kt_aside.aside-light .menu-icon {
            color: var(--siguru-primary) !important;
        }

        #kt_aside.aside-light .separator {
            border-color: rgba(47, 110, 234, 0.15) !important;
        }

        #kt_page_content .table thead th {
            font-weight: 700;
        }

        #kt_page_content .guru-supervisi-table thead th {
            padding-top: 12px;
            padding-bottom: 12px;
            color: #5f728d;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        #kt_page_content .guru-supervisi-table tbody td {
            padding-top: 14px;
            padding-bottom: 14px;
            color: var(--siguru-text);
            font-size: 12px;
            font-weight: 500;
            vertical-align: middle;
        }

        #kt_page_content .guru-supervisi-table tbody td .fw-bold,
        #kt_page_content .guru-supervisi-table tbody td .fw-semibold {
            font-weight: 700 !important;
        }

        #kt_page_content .guru-supervisi-table tbody td .text-muted,
        #kt_page_content .guru-supervisi-table tbody td .text-gray-700,
        #kt_page_content .guru-supervisi-table tbody td .text-gray-800 {
            font-size: 12px;
        }


/* Jarak menu sidebar guru */
#kt_aside .menu {
    padding-left: 10px;
    padding-right: 10px;
}

#kt_aside .menu-item > .menu-link {
    border-radius: 8px !important;
    padding-left: 12px;
    padding-right: 12px;
}

/* Menu aktif dan hover */
#kt_aside .menu-item .menu-link.active,
#kt_aside .menu-item .menu-link:hover,
#kt_aside .menu-item.show > .menu-link,
#kt_aside .menu-item.here > .menu-link {
    border-radius: 8px !important;
}

/* Sidebar Guru - Jarak dan tampilan menu */
#kt_aside {
    background-color: #ffffff !important;
    border-right: 1px solid #CFDCEB;
}

#kt_aside.aside-light .aside-logo {
    background-color: #2f6eea !important;
    border-bottom: 1px solid #2459c4;
}

#kt_aside .aside-logo .aside-toggle {
    color: #fff !important;
}

#kt_aside .aside-logo .aside-toggle svg [fill]:not(.permanent):not(g) {
    fill: #fff !important;
}

/* Jarak kiri dan kanan seluruh menu */
#kt_aside .menu {
    padding-left: 10px;
    padding-right: 10px;
}

/* Jarak antar pilihan menu */
#kt_aside .menu-item {
    margin-bottom: 5px;
}

/* Tampilan setiap pilihan */
#kt_aside .menu-item > .menu-link {
    min-height: 44px;
    padding: 10px 12px !important;
    border-radius: 8px !important;
}

/* Menu aktif */
#kt_aside .menu-item .menu-link.active {
    background-color: rgba(47, 110, 234, 0.12) !important;
    border-radius: 8px !important;
}

/* Menu saat diarahkan */
#kt_aside .menu-item .menu-link:hover {
    background-color: rgba(47, 110, 234, 0.12) !important;
    border-radius: 8px !important;
}

/* Jarak pembatas antarkelompok menu */
#kt_aside .separator {
    margin-top: 12px !important;
    margin-bottom: 12px !important;
}

    </style>
</body>
<!--end::Body-->

</html>
<!--end::Javascript-->
