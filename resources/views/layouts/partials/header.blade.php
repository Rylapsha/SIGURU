@php
$headerSearchEnabled = true;

if (\Illuminate\Support\Facades\View::hasSection('header_search')) {
$headerSearchValue = strtolower(trim((string) \Illuminate\Support\Facades\View::getSection('header_search')));
$headerSearchEnabled = ! in_array($headerSearchValue, ['false', '0', 'off', 'no'], true);
}
@endphp

<!--begin::Header-->
<div id="kt_header" class="header align-items-stretch mb-0">

    <!--begin::Container-->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">

        <!--begin::Aside mobile toggle-->
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
                            d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                            fill="currentColor" />
                    </svg>
                </span>
            </div>
        </div>
        <!--end::Aside mobile toggle-->

        <!--begin::Wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">

            <!--begin::Navbar-->
            <div class="d-flex align-items-stretch" id="kt_header_nav">
            </div>
            <!--end::Navbar-->

            <!--begin::Toolbar title-->
            <div id="kt_toolbar_container"
                class="container-fluid d-flex align-items-center flex-stack">

                <div class="page-title d-flex align-items-center flex-wrap
                            me-3 mb-5 mb-lg-0">

                    <h1 class="d-flex text-dark fw-bolder fs-3
                               align-items-center my-1">

                        @yield('page_title', 'Dashboard')

                        <span class="h-20px border-1 border-gray-200
                                     border-start ms-3 mx-2 me-1">
                        </span>
                    </h1>
                </div>

                @if ($headerSearchEnabled)
                <div class="d-flex align-items-center ms-auto" style="max-width: 420px; min-width: 220px; width: min(420px, 42vw);">
                    <form class="w-100 position-relative mb-0" method="GET" action="{{ url()->current() }}" id="headerSearchForm">
                        <span class="position-absolute top-50 translate-middle-y start-0 ms-4 text-muted">
                            <i class="bi bi-search fs-5"></i>
                        </span>
                        <input
                            type="search"
                            name="q"
                            id="headerGlobalSearch"
                            class="form-control form-control-solid ps-12"
                            placeholder="Cari..."
                            value="{{ request('q') }}"
                            aria-label="Cari di halaman"
                            autocomplete="off">
                    </form>
                </div>
                @endif
            </div>
            <!--end::Toolbar title-->

            <!--begin::Toolbar wrapper-->
            <div class="d-flex align-items-stretch flex-shrink-0">

                <!--begin::Theme mode-->
                <div class="d-flex align-items-center ms-1 ms-lg-3">
                    <button type="button"
                        id="kt_header_theme_mode_toggle"
                        class="btn btn-icon btn-icon-muted
                               btn-active-light btn-active-color-primary
                               w-30px h-30px w-md-40px h-md-40px"
                        aria-label="Aktifkan mode gelap"
                        aria-pressed="false">

                        <i class="bi bi-moon-stars fs-2"
                            aria-hidden="true"></i>
                    </button>
                </div>
                <!--end::Theme mode-->

                <!--begin::User menu-->
                <div class="d-flex align-items-center ms-1 ms-lg-3"
                    id="kt_header_user_menu_toggle">

                    <!--begin::Menu wrapper-->
                    <div class="cursor-pointer symbol symbol-30px symbol-md-40px"
                        data-kt-menu-trigger="click"
                        data-kt-menu-attach="parent"
                        data-kt-menu-placement="bottom-end">

                        @if (auth()->user()->foto)
                        <img
                            src="{{ asset('storage/' . auth()->user()->foto) }}"
                            alt="Foto profil"
                            class="rounded-circle"
                            style="width: 40px; height: 40px; object-fit: cover;">
                        @else
                        <i class="bi bi-person-circle"
                            style="font-size: 30px;"></i>
                        @endif
                    </div>

                    <!--begin::User account menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column
                                menu-rounded menu-gray-800 menu-state-bg
                                menu-state-primary fw-bold py-4 fs-6 w-275px"
                        data-kt-menu="true">

                        <!--begin::User information-->
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex flex-column
                                        px-5 py-3">

                                <span class="fw-bolder fs-5">
                                    {{ auth()->user()->nama_lengkap }}
                                </span>

                                <span class="text-muted fs-7 mt-1">
                                    NIP: {{ auth()->user()->nip }}
                                </span>
                            </div>
                        </div>
                        <!--end::User information-->

                        <div class="separator my-2"></div>

                        <!--begin::Profile-->
                        <div class="menu-item px-5">
                            <a href="{{ route('profil') }}"
                                class="menu-link px-5">
                                Profil Saya
                            </a>
                        </div>
                        <!--end::Profile-->

                        <!--begin::Logout-->
                        <div class="menu-item px-5">
                            <form method="POST"
                                action="{{ route('logout') }}"
                                id="header-logout-form">
                                @csrf

                                <button type="submit"
                                    class="menu-link px-5 border-0
                                           bg-transparent w-100 text-start">
                                    Keluar
                                </button>
                            </form>
                        </div>
                        <!--end::Logout-->

                    </div>
                    <!--end::User account menu-->

                </div>
                <!--end::User menu-->

                <!--begin::Header menu toggle-->
                <div class="d-flex align-items-center d-lg-none
                            ms-2 me-n3"
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
                                    d="M21 16H3C2.4 16 2 15.6 2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21.6 16 21 16ZM14 20V19C14 18.4 13.6 18 13 18H3C2.4 18 2 18.4 2 19V20C2 20.6 2.4 21 3 21H13C13.6 21 14 20.6 14 20Z"
                                    fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>
                <!--end::Header menu toggle-->

            </div>
            <!--end::Toolbar wrapper-->

        </div>
        <!--end::Wrapper-->

    </div>
    <!--end::Container-->

</div>
<!--end::Header-->

{{-- Theme mode toggle SIGURU --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('kt_header_theme_mode_toggle');
        const themeKey = 'siguru-theme-mode';

        if (!themeToggle) return;

        function applyTheme(mode) {
            const dark = mode === 'dark';

            document.documentElement.setAttribute(
                'data-bs-theme',
                dark ? 'dark' : 'light'
            );

            document.body.classList.toggle('dark-mode', dark);

            themeToggle.innerHTML = dark ?
                '<i class="bi bi-sun fs-2" aria-hidden="true"></i>' :
                '<i class="bi bi-moon-stars fs-2" aria-hidden="true"></i>';

            themeToggle.setAttribute(
                'aria-label',
                dark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'
            );

            themeToggle.setAttribute('aria-pressed', String(dark));

            document.querySelectorAll('[data-light-href][data-dark-href]')
                .forEach(function(stylesheet) {
                    stylesheet.href = dark ?
                        stylesheet.dataset.darkHref :
                        stylesheet.dataset.lightHref;
                });
        }

        let savedTheme = 'light';

        try {
            savedTheme = localStorage.getItem(themeKey) || 'light';
        } catch (error) {
            // Tetap gunakan mode terang jika localStorage tidak tersedia.
        }

        applyTheme(savedTheme);

        themeToggle.addEventListener('click', function() {
            const isDark = document.body.classList.contains('dark-mode');
            const nextMode = isDark ? 'light' : 'dark';

            try {
                localStorage.setItem(themeKey, nextMode);
            } catch (error) {
                // Abaikan error storage.
            }

            applyTheme(nextMode);
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('headerSearchForm');
        const searchInput = document.getElementById('headerGlobalSearch');

        if (!searchForm || !searchInput) {
            return;
        }

        const searchableNodes = Array.from(
            document.querySelectorAll('[data-searchable]')
        );

        const applySearch = () => {
            const value = searchInput.value.trim().toLowerCase();

            if (!value) {
                searchableNodes.forEach((node) => {
                    node.style.display = '';
                });
                return;
            }

            searchableNodes.forEach((node) => {
                const text = (node.dataset.searchable || node.textContent || '')
                    .replace(/\s+/g, ' ')
                    .trim()
                    .toLowerCase();

                node.style.display = text.includes(value) ? '' : 'none';
            });
        };

        if (searchableNodes.length) {
            searchInput.addEventListener('input', applySearch);
        }

        searchForm.addEventListener('submit', function(event) {
            if (!searchableNodes.length) {
                return;
            }

            event.preventDefault();
            applySearch();
        });
    });
</script>