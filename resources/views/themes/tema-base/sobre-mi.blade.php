@extends("themes.{$carpeta}.layout")

@push('styles')
<link rel="stylesheet" href="{{ asset("themes/{$carpeta}/css/subpage.css") }}">
@endpush

@section('content')
<div class="subpage">
    <div class="subpage__container">
        <div class="subpage__header">
            <h2>Sobre mí</h2>
            <p>{{ $psicologa->slogan ?? 'Conoce más sobre mi trayectoria profesional' }}</p>
        </div>
        <div class="subpage__content">
            <div class="subpage__image">
                @php $fotoWeb = \App\Models\ImagenWeb::where('psicologa_id', $psicologa->id)->where('clave', 'psicologa')->first(); @endphp
                <img src="{{ $fotoWeb ? asset('storage/' . $fotoWeb->archivo) : ($psicologa->foto ? asset('storage/' . $psicologa->foto) : asset("themes/{$carpeta}/img/psicologa.png")) }}" alt="{{ $psicologa->nombre_completo }}">
            </div>
            <div class="subpage__text">
                {!! $psicologa->sobre_mi ?: '<p>Psicóloga especializada en el bienestar emocional.</p>' !!}
                @if($psicologa->numero_colegiado)
                    <p><strong>Nº Colegiada:</strong> {{ $psicologa->numero_colegiado }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
