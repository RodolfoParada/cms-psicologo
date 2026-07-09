@extends('instalacion.layout', ['pasoActual' => 4])

@section('contenido')
<h2>Planes y precios</h2>
<p class="descripcion">Configura los precios de tus sesiones online y presenciales.</p>

<form method="POST" action="{{ route('instalacion.paso4.post') }}">
    @csrf

    <h3 style="font-size:1.6rem; color:#3d2b24; margin:0 0 1rem;">Sesiones online</h3>
    <div id="precios-online-container">
        @php
            $preciosOnline = old('precios_online', session('instalacion.paso4.precios_online', []));
        @endphp
        @if (count($preciosOnline) > 0)
            @foreach ($preciosOnline as $i => $po)
                <div class="input-group" style="flex-wrap:wrap;">
                    <input type="text" name="precios_online[{{ $i }}][nombre]" value="{{ $po['nombre'] }}" placeholder="Nombre del plan" style="min-width:15rem;">
                    <input type="number" name="precios_online[{{ $i }}][precio_mensual]" value="{{ $po['precio_mensual'] }}" placeholder="Precio mensual" step="0.01" min="0" style="width:12rem;">
                    <input type="number" name="precios_online[{{ $i }}][precio_anual]" value="{{ $po['precio_anual'] }}" placeholder="Precio anual" step="0.01" min="0" style="width:12rem;">
                    <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                </div>
            @endforeach
        @endif
    </div>
    <button type="button" class="btn-add" onclick="agregarPrecioOnline()">+ Añadir plan online</button>

    <h3 style="font-size:1.6rem; color:#3d2b24; margin:2rem 0 1rem;">Sesiones presenciales</h3>
    <div id="precios-presencial-container">
        @php
            $preciosPresencial = old('precios_presencial', session('instalacion.paso4.precios_presencial', []));
        @endphp
        @if (count($preciosPresencial) > 0)
            @foreach ($preciosPresencial as $i => $pp)
                <div class="input-group" style="flex-wrap:wrap;">
                    <input type="text" name="precios_presencial[{{ $i }}][nombre]" value="{{ $pp['nombre'] }}" placeholder="Nombre del plan" style="min-width:15rem;">
                    <input type="number" name="precios_presencial[{{ $i }}][precio_mensual]" value="{{ $pp['precio_mensual'] }}" placeholder="Precio mensual" step="0.01" min="0" style="width:12rem;">
                    <input type="number" name="precios_presencial[{{ $i }}][precio_anual]" value="{{ $pp['precio_anual'] }}" placeholder="Precio anual" step="0.01" min="0" style="width:12rem;">
                    <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                </div>
            @endforeach
        @endif
    </div>
    <button type="button" class="btn-add" onclick="agregarPrecioPresencial()">+ Añadir plan presencial</button>

    <div class="btn-group" style="margin-top:3rem;">
        <a href="{{ route('instalacion.paso3') }}" class="btn btn-secondary">Anterior</a>
        <button type="submit" class="btn btn-primary">Siguiente</button>
    </div>
</form>

<script>
    let poIndex = {{ count($preciosOnline) }};
    let ppIndex = {{ count($preciosPresencial) }};

    function agregarPrecioOnline() {
        const c = document.getElementById('precios-online-container');
        const d = document.createElement('div');
        d.className = 'input-group';
        d.style.flexWrap = 'wrap';
        d.innerHTML = '<input type="text" name="precios_online[' + poIndex + '][nombre]" placeholder="Nombre del plan" style="min-width:15rem;">' +
            '<input type="number" name="precios_online[' + poIndex + '][precio_mensual]" placeholder="Precio mensual" step="0.01" min="0" style="width:12rem;">' +
            '<input type="number" name="precios_online[' + poIndex + '][precio_anual]" placeholder="Precio anual" step="0.01" min="0" style="width:12rem;">' +
            '<button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>';
        c.appendChild(d);
        poIndex++;
    }

    function agregarPrecioPresencial() {
        const c = document.getElementById('precios-presencial-container');
        const d = document.createElement('div');
        d.className = 'input-group';
        d.style.flexWrap = 'wrap';
        d.innerHTML = '<input type="text" name="precios_presencial[' + ppIndex + '][nombre]" placeholder="Nombre del plan" style="min-width:15rem;">' +
            '<input type="number" name="precios_presencial[' + ppIndex + '][precio_mensual]" placeholder="Precio mensual" step="0.01" min="0" style="width:12rem;">' +
            '<input type="number" name="precios_presencial[' + ppIndex + '][precio_anual]" placeholder="Precio anual" step="0.01" min="0" style="width:12rem;">' +
            '<button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>';
        c.appendChild(d);
        ppIndex++;
    }
</script>
@endsection
