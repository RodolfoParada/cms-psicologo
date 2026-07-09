@extends("themes.{$carpeta}.layout")

@push('styles')
<link rel="stylesheet" href="{{ asset("themes/{$carpeta}/css/subpage.css") }}">
@endpush

@section('content')
<div class="subpage">
    <div class="subpage__container">
        <div class="subpage__header">
            <h2>Contacto</h2>
            <p>Estoy aquí para ayudarte</p>
        </div>
        <div class="subpage__contact">
            <div class="subpage__contact-info">
                <p><strong>Teléfono:</strong> {{ $psicologa->telefono_citas ?? $psicologa->telefono }}</p>
                <p><strong>Email:</strong> {{ $psicologa->email_citas ?? $psicologa->email }}</p>
                @if($psicologa->direccion)
                    <p><strong>Dirección:</strong> {{ $psicologa->direccion }}{{ $psicologa->ciudad ? ', ' . $psicologa->ciudad : '' }}</p>
                @endif
                <a href="tel:{{ $psicologa->telefono_citas ?? $psicologa->telefono }}" class="subpage__contact-btn">Llamar ahora</a>
            </div>
            @if($psicologa->direccion)
            <div class="subpage__contact-map">
                <iframe
                    width="100%" height="300" style="border:0;border-radius:8px;"
                    src="https://maps.google.com/maps?q={{ urlencode($psicologa->direccion . ' ' . ($psicologa->ciudad ?? '')) }}&output=embed"
                    allowfullscreen loading="lazy">
                </iframe>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
