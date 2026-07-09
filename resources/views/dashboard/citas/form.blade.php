@extends('dashboard.layout')

@section('titulo', isset($cita) ? 'Editar cita' : 'Nueva cita')

@push('styles')
<style>
.cita-form-wrapper {
    max-width: 700px;
    background: var(--card-bg);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.cita-form-wrapper h2 {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.2rem;
}

.cita-form-wrapper .subtitle {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
}

.cita-form-wrapper .form-group {
    margin-bottom: 1.2rem;
}

.cita-form-wrapper .form-group label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.4rem;
}

.cita-form-wrapper .form-group input,
.cita-form-wrapper .form-group select,
.cita-form-wrapper .form-group textarea {
    width: 100%;
    padding: 0.65rem 0.9rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.9rem;
    color: var(--text-primary);
    background: var(--input-bg);
    box-sizing: border-box;
}

.cita-form-wrapper .form-group input:focus,
.cita-form-wrapper .form-group select:focus,
.cita-form-wrapper .form-group textarea:focus {
    outline: none;
    border-color: var(--accent);
}

.cita-form-wrapper .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.cita-form-wrapper .form-actions {
    display: flex;
    gap: 0.8rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
}

.buscador-paciente {
    position: relative;
}

.buscador-paciente input {
    padding-left: 2.2rem !important;
}

.buscador-paciente .search-icon {
    position: absolute;
    left: 0.8rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.resultados-busqueda {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 0 0 8px 8px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    z-index: 100;
    max-height: 240px;
    overflow-y: auto;
    display: none;
}

.resultados-busqueda.visible {
    display: block;
}

.resultados-busqueda .resultado-item {
    padding: 0.7rem 0.9rem;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border);
    transition: background 0.15s;
}

.resultados-busqueda .resultado-item:last-child {
    border-bottom: none;
}

.resultados-busqueda .resultado-item:hover {
    background: var(--bg-secondary);
}

.resultados-busqueda .resultado-item .nombre {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.resultados-busqueda .resultado-item .telefono {
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.resultados-busqueda .no-results {
    padding: 1rem;
    text-align: center;
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.paciente-seleccionado {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.3rem 0.8rem;
    background: #d1fae5;
    color: #065f46;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    margin-top: 0.4rem;
}

.paciente-seleccionado button {
    background: none;
    border: none;
    color: #065f46;
    cursor: pointer;
    font-size: 0.9rem;
    padding: 0;
    line-height: 1;
}

@media (max-width: 768px) {
    .cita-form-wrapper .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('contenido')
<div class="cita-form-wrapper">
    <h2><i class="fas {{ isset($cita) ? 'fa-edit' : 'fa-plus' }}" style="margin-right:0.5rem;"></i>
        {{ isset($cita) ? 'Editar cita' : 'Nueva cita' }}</h2>
    <p class="subtitle">Completa los datos de la cita</p>

    @if ($errors->any())
        <div style="background:#fee2e2;border:1px solid #fecaca;border-radius:8px;padding:0.8rem 1rem;margin-bottom:1.2rem;">
            @foreach ($errors->all() as $error)
                <div style="color:#991b1b;font-size:0.85rem;">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ isset($cita) ? route('citas.actualizar', $cita->id) : route('citas.guardar') }}" id="formCita">
        @csrf
        @if (isset($cita)) @method('PUT') @endif

        <div class="form-group buscador-paciente">
            <label>Buscar paciente existente</label>
            <span class="search-icon"><i class="fas fa-search"></i></span>
            <input type="text" id="buscadorPaciente" placeholder="Buscar por nombre o teléfono..." autocomplete="off">
            <div class="resultados-busqueda" id="resultadosBusqueda"></div>
            <div id="pacienteSeleccionado" class="paciente-seleccionado" style="display:none;">
                <i class="fas fa-check-circle"></i> <span id="pacienteSeleccionadoNombre"></span>
                <button type="button" onclick="limpiarPaciente()">&times;</button>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="paciente_nombre">Nombre del paciente *</label>
                <input type="text" name="paciente_nombre" id="paciente_nombre"
                       value="{{ old('paciente_nombre', $cita->paciente_nombre ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="paciente_telefono">Teléfono *</label>
                <input type="tel" name="paciente_telefono" id="paciente_telefono"
                       value="{{ old('paciente_telefono', $cita->paciente_telefono ?? '') }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="paciente_email">Email</label>
                <input type="email" name="paciente_email" id="paciente_email"
                       value="{{ old('paciente_email', $cita->paciente_email ?? '') }}">
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de sesión *</label>
                <select name="tipo" id="tipo" required>
                    <option value="online" {{ old('tipo', $cita->tipo ?? '') == 'online' ? 'selected' : '' }}>Online</option>
                    <option value="presencial" {{ old('tipo', $cita->tipo ?? '') == 'presencial' ? 'selected' : '' }}>Presencial</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="fecha">Fecha *</label>
                <input type="date" name="fecha" id="fecha"
                       value="{{ old('fecha', isset($cita) ? $cita->fecha->format('Y-m-d') : '') }}" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado *</label>
                <select name="estado" id="estado" required>
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
                       value="{{ old('hora_inicio', $cita->hora_inicio ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="hora_fin">Hora fin *</label>
                <input type="time" name="hora_fin" id="hora_fin"
                       value="{{ old('hora_fin', $cita->hora_fin ?? '') }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="motivo">Motivo de la consulta</label>
            <textarea name="motivo" id="motivo" rows="2">{{ old('motivo', $cita->motivo ?? '') }}</textarea>
        </div>

        <div class="form-group">
            <label for="notas">Notas internas</label>
            <textarea name="notas" id="notas" rows="2">{{ old('notas', $cita->notas ?? '') }}</textarea>
        </div>

        <div class="form-actions">
            <a href="{{ route('citas.index') }}" style="padding:0.65rem 1.3rem;border-radius:8px;font-size:0.9rem;font-weight:600;text-decoration:none;color:var(--text-primary);background:var(--bg-secondary);border:1px solid var(--border);">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
            <button type="submit" style="padding:0.65rem 1.5rem;border-radius:8px;font-size:0.9rem;font-weight:600;background:var(--accent);color:#fff;border:none;cursor:pointer;">
                <i class="fas fa-save"></i> {{ isset($cita) ? 'Actualizar cita' : 'Guardar cita' }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let timeoutBusqueda = null;
let pacienteIdSeleccionado = null;

const buscador = document.getElementById('buscadorPaciente');
const resultados = document.getElementById('resultadosBusqueda');
const nombreInput = document.getElementById('paciente_nombre');
const telefonoInput = document.getElementById('paciente_telefono');
const emailInput = document.getElementById('paciente_email');
const pacienteTag = document.getElementById('pacienteSeleccionado');
const pacienteTagNombre = document.getElementById('pacienteSeleccionadoNombre');

buscador.addEventListener('input', function () {
    clearTimeout(timeoutBusqueda);
    const q = this.value.trim();
    if (q.length < 2) {
        resultados.classList.remove('visible');
        resultados.innerHTML = '';
        return;
    }

    timeoutBusqueda = setTimeout(function () {
        fetch('/panel-psicologa/api/pacientes/buscar?q=' + encodeURIComponent(q), {
            headers: { 'Accept': 'application/json' }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.length === 0) {
                resultados.innerHTML = '<div class="no-results">No se encontraron pacientes</div>';
            } else {
                resultados.innerHTML = data.map(function (p) {
                    return '<div class="resultado-item" data-id="' + p.id + '" data-nombre="' + esc(p.nombre) + '" data-tel="' + esc(p.telefono) + '" data-email="' + esc(p.email || '') + '">' +
                        '<span class="nombre">' + esc(p.nombre) + '</span>' +
                        '<span class="telefono">' + esc(p.telefono) + '</span>' +
                    '</div>';
                }).join('');
            }
            resultados.classList.add('visible');
        });
    }, 300);
});

resultados.addEventListener('click', function (e) {
    const item = e.target.closest('.resultado-item');
    if (!item) return;

    const nombre = item.dataset.nombre;
    const tel = item.dataset.tel;
    const email = item.dataset.email;
    const id = item.dataset.id;

    nombreInput.value = nombre;
    telefonoInput.value = tel;
    emailInput.value = email;
    pacienteIdSeleccionado = id;

    pacienteTagNombre.textContent = nombre + ' (' + tel + ')';
    pacienteTag.style.display = 'inline-flex';
    buscador.value = '';
    resultados.classList.remove('visible');
    resultados.innerHTML = '';
});

document.addEventListener('click', function (e) {
    if (!e.target.closest('.buscador-paciente')) {
        resultados.classList.remove('visible');
    }
});

function limpiarPaciente() {
    pacienteIdSeleccionado = null;
    pacienteTag.style.display = 'none';
    pacienteTagNombre.textContent = '';
}

function esc(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}
</script>
@endpush
