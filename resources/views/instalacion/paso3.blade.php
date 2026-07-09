@extends('instalacion.layout', ['pasoActual' => 3])

@section('contenido')
<h2>Especialidades y servicios</h2>
<p class="descripcion">Define las áreas en las que trabajas y los servicios que ofreces.</p>

<form method="POST" action="{{ route('instalacion.paso3.post') }}">
    @csrf

    <h3 style="font-size:1.6rem; color:#3d2b24; margin:0 0 1rem;">Especialidades</h3>
    <div id="especialidades-container">
        @php
            $especialidades = old('especialidades', session('instalacion.paso3.especialidades', []));
        @endphp
        @if (count($especialidades) > 0)
            @foreach ($especialidades as $i => $esp)
                <div class="input-group">
                    <input type="text" name="especialidades[{{ $i }}][nombre]" value="{{ $esp['nombre'] }}" placeholder="Nombre de la especialidad">
                    <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                </div>
            @endforeach
        @endif
    </div>
    <button type="button" class="btn-add" onclick="agregarEspecialidad()">+ Añadir especialidad</button>

    <h3 style="font-size:1.6rem; color:#3d2b24; margin:2rem 0 1rem;">Servicios</h3>
    <div id="servicios-container">
        @php
            $servicios = old('servicios', session('instalacion.paso3.servicios', []));
        @endphp
        @if (count($servicios) > 0)
            @foreach ($servicios as $i => $sv)
                <div class="input-group">
                    <input type="text" name="servicios[{{ $i }}][nombre]" value="{{ $sv['nombre'] }}" placeholder="Nombre del servicio">
                    <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                </div>
            @endforeach
        @endif
    </div>
    <button type="button" class="btn-add" onclick="agregarServicio()">+ Añadir servicio</button>

    <div class="btn-group" style="margin-top:3rem;">
        <a href="{{ route('instalacion.paso2') }}" class="btn btn-secondary">Anterior</a>
        <button type="submit" class="btn btn-primary">Siguiente</button>
    </div>
</form>

<script>
    let espIndex = {{ count($especialidades) }};
    let svIndex = {{ count($servicios) }};

    function agregarEspecialidad() {
        const c = document.getElementById('especialidades-container');
        const d = document.createElement('div');
        d.className = 'input-group';
        d.innerHTML = '<input type="text" name="especialidades[' + espIndex + '][nombre]" placeholder="Nombre de la especialidad">' +
            '<button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>';
        c.appendChild(d);
        espIndex++;
    }

    function agregarServicio() {
        const c = document.getElementById('servicios-container');
        const d = document.createElement('div');
        d.className = 'input-group';
        d.innerHTML = '<input type="text" name="servicios[' + svIndex + '][nombre]" placeholder="Nombre del servicio">' +
            '<button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>';
        c.appendChild(d);
        svIndex++;
    }
</script>
@endsection
