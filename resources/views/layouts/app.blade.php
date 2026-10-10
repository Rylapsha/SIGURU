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
    <meta name="description" content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta name="keywords" content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Keenthemes | Metronic" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="shortcut icon" href="{{ asset('metronic_v8.0.37/html/demo1/dist/assets/media/logos/Logo_SPENDA.png') }}" />
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
    <!--end::Global Stylesheets Bundle-->
    <script>
        if (localStorage.getItem('sikap-theme-mode') === 'dark') {
            document.querySelectorAll('[data-light-href][data-dark-href]').forEach((stylesheet) => {
                stylesheet.href = stylesheet.dataset.darkHref;
            });
        }
    </script>
    <style>
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
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed">
    <script>
        if (localStorage.getItem('sikap-theme-mode') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>
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
        (() => {
            const themeStylesheets = document.querySelectorAll('[data-light-href][data-dark-href]');
            const themeModeToggle = document.getElementById('kt_header_theme_mode_toggle');
            const darkModeToggle = document.getElementById('kt_user_menu_dark_mode_toggle');
            const baseThemeMode = localStorage.getItem('sikap-theme-mode') === 'dark' ? 'dark' : 'light';
            const alternateThemeStylesheets = new Map();
            let themeMode = baseThemeMode;
            let requestedThemeMode = themeMode;
            let isChangingTheme = false;

            const loadAlternateThemeStylesheets = async (mode) => {
                if (alternateThemeStylesheets.has(mode)) {
                    return alternateThemeStylesheets.get(mode);
                }

                const stylesheets = Array.from(themeStylesheets, (stylesheet) => {
                    const alternateStylesheet = document.createElement('link');
                    alternateStylesheet.rel = 'stylesheet';
                    alternateStylesheet.media = 'not all';
                    alternateStylesheet.href = stylesheet.dataset[`${mode}Href`];
                    return alternateStylesheet;
                });

                try {
                    await Promise.all(stylesheets.map((stylesheet) => new Promise((resolve, reject) => {
                        stylesheet.addEventListener('load', resolve, {
                            once: true
                        });
                        stylesheet.addEventListener('error', () => reject(new Error(`Gagal memuat stylesheet tema: ${stylesheet.href}`)), {
                            once: true
                        });
                        document.head.append(stylesheet);
                    })));
                } catch (error) {
                    stylesheets.forEach((stylesheet) => stylesheet.remove());
                    throw error;
                }

                alternateThemeStylesheets.set(mode, stylesheets);
                return stylesheets;
            };

            const applyThemeMode = async (mode) => {
                requestedThemeMode = mode;
                if (isChangingTheme) {
                    return;
                }
                isChangingTheme = true;
                try {
                    while (themeMode !== requestedThemeMode) {
                        const nextMode = requestedThemeMode;
                        const nextStylesheets = nextMode === baseThemeMode ?
                            themeStylesheets :
                            await loadAlternateThemeStylesheets(nextMode);

                        if (nextMode !== requestedThemeMode) {
                            continue;
                        }

                        document.body.classList.add('theme-transitioning');
                        await new Promise((resolve) => window.requestAnimationFrame(resolve));

                        const currentStylesheets = themeMode === baseThemeMode ?
                            themeStylesheets :
                            alternateThemeStylesheets.get(themeMode);

                        currentStylesheets.forEach((stylesheet) => {
                            stylesheet.media = 'not all';
                        });
                        nextStylesheets.forEach((stylesheet) => {
                            stylesheet.media = 'all';
                        });

                        themeMode = nextMode;
                        document.body.classList.toggle('dark-mode', themeMode === 'dark');
                        darkModeToggle.checked = themeMode === 'dark';
                        themeModeToggle.setAttribute('aria-pressed', String(themeMode === 'dark'));
                        themeModeToggle.setAttribute('aria-label', themeMode === 'dark' ? 'Aktifkan mode terang' : 'Aktifkan mode gelap');
                        themeModeToggle.querySelector('i').className = themeMode === 'dark' ?
                            'bi bi-sun fs-2' :
                            'bi bi-moon-stars fs-2';
                        localStorage.setItem('sikap-theme-mode', themeMode);

                        await new Promise((resolve) => window.setTimeout(resolve, 300));
                    }
                } catch (error) {
                    console.error('Gagal mengganti mode tema.', error);
                } finally {
                    document.body.classList.remove('theme-transitioning');
                    darkModeToggle.checked = themeMode === 'dark';
                    isChangingTheme = false;
                }
            };

            themeModeToggle.addEventListener('click', () => {
                applyThemeMode(requestedThemeMode === 'dark' ? 'light' : 'dark');
            });

            darkModeToggle.checked = themeMode === 'dark';
            darkModeToggle.addEventListener('change', () => {
                applyThemeMode(darkModeToggle.checked ? 'dark' : 'light');
            });
        })();
    </script>
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>