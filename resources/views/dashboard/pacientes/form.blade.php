@extends('dashboard.layout')

@section('titulo', isset($paciente) ? 'Editar paciente' : 'Nuevo paciente')

@push('styles')
<style>
.paciente-form {
    max-width: 700px;
    background: var(--card-bg);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.paciente-form h2 {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 1.5rem;
}

.paciente-form .form-group {
    margin-bottom: 1.2rem;
}

.paciente-form .form-group label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.4rem;
}

.paciente-form .form-group input,
.paciente-form .form-group textarea {
    width: 100%;
    padding: 0.65rem 0.9rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.9rem;
    color: var(--text-primary);
    background: var(--input-bg);
    box-sizing: border-box;
}

.paciente-form .form-group input:focus,
.paciente-form .form-group textarea:focus {
    outline: none;
    border-color: var(--accent);
}

.paciente-form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.paciente-form .form-actions {
    display: flex;
    gap: 0.8rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
}

@media (max-width: 768px) {
    .paciente-form .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('contenido')
<div class="paciente-form">
    <h2><i class="fas {{ isset($paciente) ? 'fa-user-edit' : 'fa-user-plus' }}" style="margin-right:0.5rem;"></i>
        {{ isset($paciente) ? 'Editar paciente' : 'Nuevo paciente' }}</h2>

    <form action="{{ isset($paciente) ? route('pacientes.actualizar', $paciente->id) : route('pacientes.guardar') }}" method="POST">
        @csrf
        @if(isset($paciente)) @method('PUT') @endif

        <div class="form-group">
            <label for="nombre">Nombre completo *</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $paciente->nombre ?? '') }}" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="telefono">Teléfono *</label>
                <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $paciente->telefono ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $paciente->email ?? '') }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento', isset($paciente) && $paciente->fecha_nacimiento ? $paciente->fecha_nacimiento->format('Y-m-d') : '') }}">
            </div>
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" value="{{ old('direccion', $paciente->direccion ?? '') }}">
            </div>
        </div>

        <div class="form-group">
            <label for="observaciones">Observaciones / Notas</label>
            <textarea name="observaciones" id="observaciones" rows="4">{{ old('observaciones', $paciente->observaciones ?? '') }}</textarea>
        </div>

        <div class="form-actions">
            <a href="{{ route('pacientes.index') }}" style="padding:0.65rem 1.3rem;border-radius:8px;font-size:0.9rem;font-weight:600;text-decoration:none;color:var(--text-primary);background:var(--bg-secondary);border:1px solid var(--border);">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
            <button type="submit" style="padding:0.65rem 1.5rem;border-radius:8px;font-size:0.9rem;font-weight:600;background:var(--accent);color:#fff;border:none;cursor:pointer;">
                <i class="fas fa-save"></i> {{ isset($paciente) ? 'Actualizar paciente' : 'Guardar paciente' }}
            </button>
        </div>
    </form>
</div>
@endsection
