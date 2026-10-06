<!--begin::Aside-->
<div
    id="kt_aside"
    class="aside"
    data-kt-drawer="true"
    data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}"
    data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}"
    data-kt-drawer-direction="start">

    <!--begin::Aside Logo-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">

        <a href="{{ route('dashboard') }}" class="d-flex align-items-center">

            <img
                src="{{ asset('asset/img/logo.png') }}"
                alt="Logo SIGURU"
                class="h-45px">

            <div class="ms-3">
                <span class="fw-bold fs-5 text-gray-800">
                    SIGURU
                </span>

                <span class="text-muted fs-8 d-block">
                    SMP Negeri 2 Purwakarta
                </span>
            </div>

        </a>

    </div>
    <!--end::Aside Logo-->


    <!--begin::Aside Menu-->
    <div
        class="hover-scroll-overlay-y my-5 my-lg-5"
        id="kt_aside_menu_wrapper"
        data-kt-scroll="true"
        data-kt-scroll-activate="{default: false, lg: true}"
        data-kt-scroll-height="auto">

        <div
            class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary"
            id="kt_aside_menu"
            data-kt-menu="true">

            <!--begin::Dashboard-->
            <div class="menu-item">

                <a
                    class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">

                    <span class="menu-icon">
                        <i class="ki-duotone ki-element-11 fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>

                    <span class="menu-title">
                        Dashboard
                    </span>

                </a>

            </div>
            <!--end::Dashboard-->

            <!--begin::Separator-->
            <div
                class="mx-5 my-4"
                style="height: 3px; background-color: #e1e3ea;"></div>
            <!--end::Separator-->

            <!--begin::Profil-->
            <div class="menu-item">
                <a class="menu-link {{ request()->is('profil') ? 'active' : '' }}" href="{{ url('/profil') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-profile-user fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>
                    <span class="menu-title"> Profil Saya </span></a>
            </div>
            <!--end::Profil-->

            <!--begin::Dokumen-->
            <div class="menu-item">

                <a
                    class="menu-link"
                    href="#">

                    <span class="menu-icon">
                        <i class="ki-duotone ki-folder fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>

                    <span class="menu-title">
                        Dokumen
                    </span>

                </a>

            </div>
            <!--end::Dokumen-->


            <!--begin::Supervisi-->
            <div class="menu-item">

                <a
                    class="menu-link"
                    href="#">

                    <span class="menu-icon">
                        <i class="ki-duotone ki-chart-simple fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>

                    <span class="menu-title">
                        Supervisi
                    </span>

                </a>

            </div>
            <!--end::Supervisi-->

            <!--begin::Separator-->
            <div
                class="mx-5 my-4"
                style="height: 3px; background-color: #e1e3ea;"></div>
            <!--end::Separator-->

            <!--begin::Logout-->
            <div class="menu-item">

                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="w-100">

                    @csrf

                    <button
                        type="submit"
                        class="menu-link border-0 bg-transparent w-100 text-start">

                        <span class="menu-icon">
                            <i class="ki-duotone ki-exit-right fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>

                        <span class="menu-title">
                            Logout
                        </span>

                    </button>

                </form>

            </div>
            <!--end::Logout-->


        </div>

    </div>
    <!--end::Aside Menu-->

</div>
<!--end::Aside-->