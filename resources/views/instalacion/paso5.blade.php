@extends('instalacion.layout', ['pasoActual' => 5])

@section('contenido')
<h2>Ubicación de la consulta</h2>
<p class="descripcion">Dirección y lugar donde atiendes presencialmente.</p>

<form method="POST" action="{{ route('instalacion.paso5.post') }}">
    @csrf

    <div class="form-group">
        <label for="direccion">Dirección</label>
        <input type="text" name="direccion" id="direccion" value="{{ old('direccion', session('instalacion.paso5.direccion')) }}" placeholder="Calle, número, piso">
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="ciudad">Ciudad</label>
            <input type="text" name="ciudad" id="ciudad" value="{{ old('ciudad', session('instalacion.paso5.ciudad')) }}">
        </div>
        <div class="form-group">
            <label for="codigo_postal">Código postal</label>
            <input type="text" name="codigo_postal" id="codigo_postal" value="{{ old('codigo_postal', session('instalacion.paso5.codigo_postal')) }}">
        </div>
    </div>

    <div class="form-group">
        <label for="pais">País</label>
        <input type="text" name="pais" id="pais" value="{{ old('pais', session('instalacion.paso5.pais')) }}" placeholder="España">
    </div>

    <div class="btn-group">
        <a href="{{ route('instalacion.paso4') }}" class="btn btn-secondary">Anterior</a>
        <button type="submit" class="btn btn-primary">Siguiente</button>
    </div>
</form>
@endsection
