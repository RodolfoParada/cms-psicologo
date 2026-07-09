@extends('instalacion.layout', ['pasoActual' => 7])

@section('contenido')
<h2>Elige un tema visual</h2>
<p class="descripcion">Selecciona el diseño que más te guste para tu web. Siempre podrás cambiarlo después.</p>

<form method="POST" action="{{ route('instalacion.completar') }}">
    @csrf

    <div class="tema-grid">
        @foreach ($temas as $tema)
            <div class="tema-card" data-id="{{ $tema['id'] }}" onclick="seleccionarTema(this, {{ $tema['id'] }})">
                @php
                    $colores = ['#976147', '#4a7c59', '#8b6fa3', '#3a7ca5', '#c97b7b'];
                    $color = $colores[$tema['id'] - 1];
                @endphp
                <div class="preview" style="background: linear-gradient(135deg, {{ $color }} 0%, {{ $color }}cc 100%);"></div>
                <h4>{{ $tema['nombre'] }}</h4>
                <p>{{ $tema['descripcion'] }}</p>
            </div>
        @endforeach
    </div>

    <input type="hidden" name="tema_id" id="tema_id" value="">

    <div class="btn-group" style="margin-top:3rem;">
        <a href="{{ route('instalacion.paso6') }}" class="btn btn-secondary">Anterior</a>
        <button type="submit" class="btn btn-primary" id="btn-completar" disabled>Completar instalación</button>
    </div>
</form>

<script>
    function seleccionarTema(el, id) {
        document.querySelectorAll('.tema-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('tema_id').value = id;
        document.getElementById('btn-completar').disabled = false;
    }
</script>
@endsection
