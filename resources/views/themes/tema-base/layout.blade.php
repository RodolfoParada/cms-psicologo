<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('title')@yield('title') — @endif{{ $psicologa->nombre_completo }}</title>
    <meta name="description" content="@yield('meta_descripcion', $config->meta_descripcion ?? $psicologa->slogan ?? 'Psicóloga profesional')">
    <meta name="keywords" content="{{ $config->meta_keywords ?? 'psicología, terapia, bienestar' }}">
    <link href="{{ asset("themes/{$carpeta}/css/fonts.css") }}" rel="stylesheet">
    <link href="{{ asset("themes/{$carpeta}/fonts/fontawesome-free-6.1.2-web/css/all.min.css") }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset("themes/{$carpeta}/css/reset.css") }}">
    <link rel="stylesheet" href="{{ asset("themes/{$carpeta}/css/styles.css") }}">
    <link rel="stylesheet" href="{{ asset("themes/{$carpeta}/css/responsive.css") }}">
    @if($config?->favicon)
        <link rel="icon" href="{{ asset('storage/' . $config->favicon) }}">
    @endif
    @stack('styles')
</head>
<body>

    <div class="layout">
        <div class="layout__background"></div>
        <div class="layout__container-banner">
            @include("themes.{$carpeta}.partials.nav")
        </div>

        @yield('content')

        @include("themes.{$carpeta}.partials.footer")
    </div>

    <script src="{{ asset("themes/{$carpeta}/js/main.js") }}"></script>
    <script src="{{ asset("themes/{$carpeta}/js/navMobile.js") }}"></script>
    <script src="{{ asset("themes/{$carpeta}/js/scroll-top.js") }}"></script>
    <script src="{{ asset("themes/{$carpeta}/js/navFixed.js") }}"></script>
    @stack('scripts')
</body>
</html>
