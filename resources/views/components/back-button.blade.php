@props(['fallback'])

@php
    $previousUrl = url()->previous($fallback);
    $previousHost = parse_url($previousUrl, PHP_URL_HOST);
    $previousScheme = parse_url($previousUrl, PHP_URL_SCHEME);
    $previousPort = parse_url($previousUrl, PHP_URL_PORT);
    $previousOrigin = $previousScheme . '://' . $previousHost
        . ($previousPort ? ':' . $previousPort : '');
    $previousPath = parse_url($previousUrl, PHP_URL_PATH);
    $currentPath = parse_url(request()->url(), PHP_URL_PATH);
    $backUrl = $previousOrigin === request()->getSchemeAndHttpHost()
        && $previousPath !== $currentPath
        ? $previousUrl
        : $fallback;
@endphp

<a href="{{ $backUrl }}" class="btn btn-light d-inline-flex align-items-center gap-2">
    <i class="bi bi-arrow-left"></i>
    <span>Kembali</span>
</a>
