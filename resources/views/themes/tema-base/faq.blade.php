@extends("themes.{$carpeta}.layout")

@push('styles')
<link rel="stylesheet" href="{{ asset("themes/{$carpeta}/css/subpage.css") }}">
@endpush

@section('content')
<div class="subpage">
    <div class="subpage__container">
        <div class="subpage__header">
            <h2>Preguntas frecuentes</h2>
        </div>
        <div class="subpage__faq">
            @foreach($faqs as $f)
            <details class="subpage__faq-item">
                <summary>{{ $f->pregunta }}</summary>
                <p>{{ $f->respuesta }}</p>
            </details>
            @endforeach
        </div>
    </div>
</div>
@endsection
