<!--begin::Header-->
<div id="kt_header" class="header align-items-stretch">

    <!--begin::Container-->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">

        <!--begin::Mobile aside toggle-->
        <div class="d-flex align-items-center d-lg-none ms-n3 me-1">
            <button
                type="button"
                class="btn btn-icon btn-active-light-primary"
                id="kt_aside_mobile_toggle">
                <i class="ki-duotone ki-menu fs-2x">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
            </button>
        </div>
        <!--end::Mobile aside toggle-->


        <!--begin::Mobile logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="{{ url('/') }}" class="d-lg-none">
                <img
                    src="{{ asset('asset/img/logo.png') }}"
                    alt="Logo"
                    class="h-35px">
            </a>
        </div>
        <!--end::Mobile logo-->


        <!--begin::Wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">

            <!--begin::Header title-->
            <div class="d-flex align-items-center">
                <div class="page-title">
                    <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
                        {{ $pageTitle ?? 'Dashboard' }}
                    </h1>
                </div>
            </div>
            <!--end::Header title-->


            <!--begin::Topbar-->
            <div class="d-flex align-items-stretch flex-shrink-0">

                <!--begin::User-->
                <div class="d-flex align-items-center ms-1 ms-lg-3">

                    <div class="symbol symbol-35px symbol-circle">
                        @if(Auth::user()->foto)
                        <img
                            src="{{ asset('storage/' . Auth::user()->foto) }}"
                            alt="Foto Profil">
                        @else
                        <div class="symbol-label fs-3 fw-bold bg-light-primary text-primary">
                            {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
                        </div>
                        @endif
                    </div>

                    <div class="d-none d-lg-flex flex-column ms-3">
                        <span class="fw-bold text-gray-900 fs-7">
                            {{ Auth::user()->nama_lengkap }}
                        </span>

                        <span class="text-muted fs-8">
                            Guru
                        </span>
                    </div>

                </div>
                <!--end::User-->

            </div>
            <!--end::Topbar-->

        </div>
        <!--end::Wrapper-->

    </div>
    <!--end::Container-->

</div>
<!--end::Header-->