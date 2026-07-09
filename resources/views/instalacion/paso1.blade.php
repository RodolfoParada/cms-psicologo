@extends('instalacion.layout', ['pasoActual' => 1])

@section('contenido')
<h2>Datos de la psicóloga</h2>
<p class="descripcion">Estos datos serán tu acceso privado al panel de administración.</p>

<form method="POST" action="{{ route('instalacion.paso1.post') }}">
    @csrf

    <div class="form-row">
        <div class="form-group">
            <label for="nombre">Nombre *</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', session('instalacion.paso1.nombre')) }}" required>
        </div>
        <div class="form-group">
            <label for="apellidos">Apellidos *</label>
            <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos', session('instalacion.paso1.apellidos')) }}" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" name="email" id="email" value="{{ old('email', session('instalacion.paso1.email')) }}" required>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono *</label>
            <input type="tel" name="telefono" id="telefono" value="{{ old('telefono', session('instalacion.paso1.telefono')) }}" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="password">Contraseña *</label>
            <input type="password" name="password" id="password" required minlength="8">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirmar contraseña *</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8">
        </div>
    </div>

    <div class="btn-group">
        <span></span>
        <button type="submit" class="btn btn-primary">Siguiente</button>
    </div>
</form>
@endsection
