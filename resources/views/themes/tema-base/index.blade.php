@extends("themes.{$carpeta}.layout")

@section('title', 'Inicio')

@section('content')
@php $fTitulo = fn($k, $d) => $frases[$k]->valor ?? $d; @endphp

@include("themes.{$carpeta}.partials.banner")
@include("themes.{$carpeta}.partials.therapies")
@include("themes.{$carpeta}.partials.about")

@if($config?->mostrar_servicios)
    @include("themes.{$carpeta}.partials.services")
@endif

@if($config?->mostrar_especialidades)
    @include("themes.{$carpeta}.partials.especialidades")
@endif

@include("themes.{$carpeta}.partials.pasos")
@include("themes.{$carpeta}.partials.prices")

@if($config?->mostrar_blog)
    @include("themes.{$carpeta}.partials.blog")
@endif

@include("themes.{$carpeta}.partials.cta")

<script src="{{ asset("themes/{$carpeta}/js/banner.js") }}"></script>
@endsection
