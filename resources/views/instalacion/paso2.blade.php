@extends('instalacion.layout', ['pasoActual' => 2])

@section('contenido')
<h2>Información de la web</h2>
<p class="descripcion">Datos que aparecerán en la parte pública de tu web.</p>

<form method="POST" action="{{ route('instalacion.paso2.post') }}">
    @csrf

    <div class="form-group">
        <label for="slogan">Frase gancho / Eslogan</label>
        <input type="text" name="slogan" id="slogan" value="{{ old('slogan', session('instalacion.paso2.slogan')) }}" placeholder="Ej: Tu bienestar emocional es mi prioridad">
    </div>

    <div class="form-group">
        <label for="numero_colegiado">Número de colegiado</label>
        <input type="text" name="numero_colegiado" id="numero_colegiado" value="{{ old('numero_colegiado', session('instalacion.paso2.numero_colegiado')) }}">
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="telefono_citas">Teléfono para citas</label>
            <input type="tel" name="telefono_citas" id="telefono_citas" value="{{ old('telefono_citas', session('instalacion.paso2.telefono_citas')) }}">
        </div>
        <div class="form-group">
            <label for="email_citas">Email para citas</label>
            <input type="email" name="email_citas" id="email_citas" value="{{ old('email_citas', session('instalacion.paso2.email_citas')) }}">
        </div>
    </div>

    <div class="form-group">
        <label for="sobre_mi">Sobre mí</label>
        <textarea name="sobre_mi" id="sobre_mi" placeholder="Cuéntales a tus pacientes quién eres y cómo trabajas...">{{ old('sobre_mi', session('instalacion.paso2.sobre_mi')) }}</textarea>
    </div>

    <div class="btn-group">
        <a href="{{ route('instalacion.paso1') }}" class="btn btn-secondary">Anterior</a>
        <button type="submit" class="btn btn-primary">Siguiente</button>
    </div>
</form>
@endsection
