<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'SIGURU - Direktori Guru SMP Negeri 2 Purwakarta')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Metronic Demo 1 --}}
    <link rel="stylesheet" href="{{ asset('demo1/plugins/global/plugins.bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('demo1/css/style.bundle.css') }}">

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

</body>

</html>