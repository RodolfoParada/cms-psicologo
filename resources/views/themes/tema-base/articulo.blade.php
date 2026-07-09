@extends("themes.{$carpeta}.layout")

@push('styles')
<link rel="stylesheet" href="{{ asset("themes/{$carpeta}/css/subpage.css") }}">
@endpush

@section('content')
<div class="subpage">
    <div class="subpage__container">
        <div class="subpage__header">
            <h2>{{ $articulo->titulo }}</h2>
            <p>{{ $articulo->created_at->format('d/m/Y') }} · {{ $articulo->categoria->nombre ?? 'Blog' }}</p>
        </div>
        <div class="subpage__content">
            @if($articulo->imagen)
                <div class="subpage__image">
                    <img src="{{ asset('storage/' . $articulo->imagen) }}" alt="{{ $articulo->titulo }}">
                </div>
            @endif
            <div class="subpage__text">
                {!! $articulo->contenido !!}
            </div>
        </div>
        <a href="{{ route('web.blog') }}" class="subpage__back">← Volver al blog</a>
    </div>
</div>
@endsection
