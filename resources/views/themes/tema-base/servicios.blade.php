@extends("themes.{$carpeta}.layout")

@push('styles')
<link rel="stylesheet" href="{{ asset("themes/{$carpeta}/css/subpage.css") }}">
@endpush

@section('content')
<div class="subpage">
    <div class="subpage__container">
        <div class="subpage__header">
            <h2>Servicios</h2>
        </div>
        <div class="subpage__services">
            @foreach($servicios as $s)
            <div class="subpage__service">
                <h3>{{ $s->nombre }}</h3>
                <p>{{ $s->descripcion }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
