@extends('dashboard.layout')

@section('titulo', 'Disponibilidad')

@section('contenido')
<div class="page-header">
    <h1>Disponibilidad</h1>
    <p>Configura los horarios de atención y las sesiones</p>
</div>

@if (session('success'))
    <div class="alert alert-success alert-auto-close">{{ session('success') }}</div>
@endif

{{-- Modo vacaciones + Periodos --}}
<div class="card" style="margin-bottom:1.6rem;">
    <div class="card-header">
        <h2>Vacaciones</h2>
    </div>
    <div class="card-body">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem;">
            <div>
                <h3 style="font-size:1.5rem; margin:0 0 1rem;">Modo vacaciones</h3>
                <p style="font-size:1.3rem; color:var(--color-text-light); margin:0 0 1rem;">
                    Activa esta opción para deshabilitar temporalmente el sistema de reservas.
                </p>
                <form method="POST" action="{{ route('disponibilidad.vacaciones.toggle') }}" style="display:flex; align-items:center; gap:1rem;">
                    @csrf
                    <label class="toggle">
                        <input type="checkbox" name="activo" {{ Auth::guard('psicologa')->user()->modo_vacaciones ? 'checked' : '' }}
                            onchange="this.form.submit()">
                        <span class="slider"></span>
                    </label>
                    <span style="font-size:1.3rem; font-weight:500;">
                        {{ Auth::guard('psicologa')->user()->modo_vacaciones ? 'Vacaciones activadas' : 'Vacaciones desactivadas' }}
                    </span>
                </form>
            </div>

            <div>
                <h3 style="font-size:1.5rem; margin:0 0 1rem;">Periodos de vacaciones</h3>
                <div id="periodos-lista">
                    @forelse ($vacaciones as $v)
                        <div style="display:flex; align-items:center; gap:0.8rem; margin-bottom:0.6rem; font-size:1.3rem;">
                            <span>{{ $v->fecha_inicio->format('d/m/Y') }} - {{ $v->fecha_fin->format('d/m/Y') }}</span>
                            <form method="POST" action="{{ route('disponibilidad.vacaciones.eliminar', $v->id) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" style="padding:0.3rem 0.8rem;">X</button>
                            </form>
                        </div>
                    @empty
                        <p style="font-size:1.3rem; color:var(--color-text-light);">No hay periodos de vacaciones.</p>
                    @endforelse
                </div>
                <form method="POST" action="{{ route('disponibilidad.vacaciones.guardar') }}" style="margin-top:1rem;">
                    @csrf
                    <div style="display:flex; gap:0.8rem; align-items:center; flex-wrap:wrap;">
                        <input type="date" name="periodos[0][fecha_inicio]" class="form-control" style="width:auto;" required>
                        <span style="font-size:1.3rem;">a</span>
                        <input type="date" name="periodos[0][fecha_fin]" class="form-control" style="width:auto;" required>
                        <button type="submit" class="btn btn-primary btn-sm">Añadir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Configuración Online --}}
@include('dashboard._disponibilidad-config', [
    'tipo' => 'online',
    'titulo' => 'Sesiones online',
    'descanso' => $descansoOnline,
    'disponibilidad' => $disponibilidadOnline,
])

{{-- Configuración Presencial --}}
@include('dashboard._disponibilidad-config', [
    'tipo' => 'presencial',
    'titulo' => 'Sesiones presenciales',
    'descanso' => $descansoPresencial,
    'disponibilidad' => $disponibilidadPresencial,
])

<style>
.horario-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.8rem;
    margin-top: 1rem;
}
.horario-dia {
    border: 0.1rem solid var(--color-border);
    border-radius: var(--radius);
    padding: 1rem;
    min-height: 8rem;
}
.horario-dia h4 {
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0 0 0.6rem;
    text-align: center;
    color: var(--color-text);
}
.horario-dia .slot {
    font-size: 1.1rem;
    padding: 0.4rem 0.6rem;
    background: var(--color-primary-light);
    color: var(--color-primary);
    border-radius: 0.4rem;
    margin-bottom: 0.3rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
}
.horario-dia .slot:hover {
    background: var(--color-primary);
    color: #fff;
}
.horario-dia .slot-add {
    font-size: 1.1rem;
    padding: 0.4rem;
    text-align: center;
    color: var(--color-text-light);
    border: 0.1rem dashed var(--color-border);
    border-radius: 0.4rem;
    cursor: pointer;
}
.horario-dia .slot-add:hover {
    border-color: var(--color-primary);
    color: var(--color-primary);
}
</style>

<script>
function eliminarSlot(tipo, dia, horaInicio) {
    if (!confirm('¿Eliminar este horario?')) return;
    fetch('{{ route("disponibilidad.horarios.guardar") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({
            tipo: tipo,
            horarios: getHorariosActuales(tipo).filter(h => !(h.dia == dia && h.hora_inicio == horaInicio))
        })
    }).then(r => {
        if (r.ok) location.reload();
    });
}

function getHorariosActuales(tipo) {
    const horarios = [];
    document.querySelectorAll(`[data-tipo="${tipo}"]`).forEach(el => {
        horarios.push({
            dia: parseInt(el.dataset.dia),
            hora_inicio: el.dataset.inicio,
            hora_fin: el.dataset.fin
        });
    });
    return horarios;
}

function agregarSlot(tipo, dia) {
    const inicio = prompt('Hora de inicio (HH:MM):');
    if (!inicio || !/^\d{2}:\d{2}$/.test(inicio)) return;
    const fin = prompt('Hora de fin (HH:MM):');
    if (!fin || !/^\d{2}:\d{2}$/.test(fin)) return;

    const horarios = getHorariosActuales(tipo);
    horarios.push({ dia, hora_inicio: inicio, hora_fin: fin });

    fetch('{{ route("disponibilidad.horarios.guardar") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ tipo, horarios })
    }).then(r => {
        if (r.ok) location.reload();
    });
}
</script>
@endsection
