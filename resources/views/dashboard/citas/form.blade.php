@extends('dashboard.layout')

@section('titulo', isset($cita) ? 'Editar cita' : 'Nueva cita')

@section('contenido')
<div class="page-header">
    <h1>{{ isset($cita) ? 'Editar cita' : 'Nueva cita' }}</h1>
    <p>Completa los datos de la cita</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ isset($cita) ? route('citas.actualizar', $cita->id) : route('citas.guardar') }}">
            @csrf
            @if (isset($cita)) @method('PUT') @endif

            <div class="form-row">
                <div class="form-group">
                    <label for="paciente_nombre">Nombre del paciente *</label>
                    <input type="text" name="paciente_nombre" id="paciente_nombre"
                           class="form-control" value="{{ old('paciente_nombre', $cita->paciente_nombre ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label for="paciente_telefono">Teléfono *</label>
                    <input type="tel" name="paciente_telefono" id="paciente_telefono"
                           class="form-control" value="{{ old('paciente_telefono', $cita->paciente_telefono ?? '') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="paciente_email">Email</label>
                    <input type="email" name="paciente_email" id="paciente_email"
                           class="form-control" value="{{ old('paciente_email', $cita->paciente_email ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo de sesión *</label>
                    <select name="tipo" id="tipo" class="form-control" required>
                        <option value="online" {{ old('tipo', $cita->tipo ?? '') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="presencial" {{ old('tipo', $cita->tipo ?? '') == 'presencial' ? 'selected' : '' }}>Presencial</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="fecha">Fecha *</label>
                    <input type="date" name="fecha" id="fecha"
                           class="form-control" value="{{ old('fecha', isset($cita) ? $cita->fecha->format('Y-m-d') : '') }}" required>
                </div>
                <div class="form-group">
                    <label for="estado">Estado *</label>
                    <select name="estado" id="estado" class="form-control" required>
                        <option value="pendiente" {{ old('estado', $cita->estado ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmada" {{ old('estado', $cita->estado ?? '') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                        <option value="completada" {{ old('estado', $cita->estado ?? '') == 'completada' ? 'selected' : '' }}>Completada</option>
                        <option value="cancelada" {{ old('estado', $cita->estado ?? '') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="hora_inicio">Hora inicio *</label>
                    <input type="time" name="hora_inicio" id="hora_inicio"
                           class="form-control" value="{{ old('hora_inicio', $cita->hora_inicio ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label for="hora_fin">Hora fin *</label>
                    <input type="time" name="hora_fin" id="hora_fin"
                           class="form-control" value="{{ old('hora_fin', $cita->hora_fin ?? '') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="motivo">Motivo de la consulta</label>
                <textarea name="motivo" id="motivo" class="form-control" rows="3">{{ old('motivo', $cita->motivo ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="notas">Notas internas</label>
                <textarea name="notas" id="notas" class="form-control" rows="3">{{ old('notas', $cita->notas ?? '') }}</textarea>
            </div>

            <div class="btn-group" style="margin-top:1rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ isset($cita) ? 'Actualizar' : 'Guardar' }}
                </button>
                <a href="{{ route('citas.index') }}" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
