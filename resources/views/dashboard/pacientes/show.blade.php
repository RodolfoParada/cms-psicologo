@extends('dashboard.layout')

@section('titulo', $paciente->nombre)

@push('styles')
<style>
.paciente-detalle {
    display: grid;
    gap: 1.5rem;
}

.paciente-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
}

.paciente-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.paciente-card .avatar-large {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: var(--accent);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.paciente-card .info-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-top: 1rem;
}

.paciente-card .info-item {
    padding: 0.8rem;
    background: var(--bg-secondary);
    border-radius: 8px;
}

.paciente-card .info-item label {
    display: block;
    font-size: 0.8rem;
    color: var(--text-secondary);
    font-weight: 600;
    margin-bottom: 0.2rem;
}

.paciente-card .info-item span {
    font-size: 0.95rem;
    color: var(--text-primary);
    font-weight: 500;
}

.citas-list {
    display: grid;
    gap: 0.7rem;
}

.cita-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem 1rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--card-bg);
}

.cita-item .cita-fecha {
    font-weight: 600;
    color: var(--text-primary);
}

.cita-item .cita-hora {
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.cita-item .cita-info {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.badge-estado {
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-estado.pendiente { background: #fef3c7; color: #92400e; }
.badge-estado.confirmada { background: #d1fae5; color: #065f46; }
.badge-estado.completada { background: #dbeafe; color: #1e40af; }
.badge-estado.cancelada { background: #fee2e2; color: #991b1b; }

@media (max-width: 768px) {
    .paciente-card .info-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('contenido')
<div class="paciente-detalle">
    <div class="paciente-card">
        <div class="paciente-card-header">
            <div style="display:flex;align-items:center;gap:1rem;">
                <div class="avatar-large">{{ strtoupper(substr($paciente->nombre, 0, 1)) }}</div>
                <div>
                    <h2 style="font-size:1.4rem;font-weight:700;color:var(--text-primary);margin:0;">{{ $paciente->nombre }}</h2>
                    <p style="color:var(--text-secondary);font-size:0.85rem;margin:0.2rem 0 0;">
                        <i class="fas fa-hashtag"></i> ID #{{ $paciente->id }}
                    </p>
                </div>
            </div>
            <div style="display:flex;gap:0.5rem;">
                <a href="{{ route('proteccion-datos.paciente', $paciente->id) }}" class="btn-icono" title="Descargar protección de datos" style="color:#2563eb;" target="_blank">
                    <i class="fas fa-file-pdf"></i>
                </a>
                <a href="{{ route('pacientes.editar', $paciente->id) }}" class="btn-icono" title="Editar paciente">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('pacientes.eliminar', $paciente->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este paciente?')">
                    @csrf @method('DELETE')
                    <button class="btn-icono danger" title="Eliminar paciente">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="info-row">
            <div class="info-item">
                <label><i class="fas fa-phone"></i> Teléfono</label>
                <span>{{ $paciente->telefono }}</span>
            </div>
            <div class="info-item">
                <label><i class="fas fa-envelope"></i> Email</label>
                <span>{{ $paciente->email ?? '—' }}</span>
            </div>
            <div class="info-item">
                <label><i class="fas fa-calendar"></i> Fecha de nacimiento</label>
                <span>{{ $paciente->fecha_nacimiento ? $paciente->fecha_nacimiento->format('d/m/Y') : '—' }}</span>
            </div>
            <div class="info-item">
                <label><i class="fas fa-map-marker-alt"></i> Dirección</label>
                <span>{{ $paciente->direccion ?? '—' }}</span>
            </div>
        </div>

        @if($paciente->observaciones)
            <div style="margin-top:1rem;padding:0.8rem;background:var(--bg-secondary);border-radius:8px;">
                <label style="display:block;font-size:0.8rem;color:var(--text-secondary);font-weight:600;margin-bottom:0.3rem;">
                    <i class="fas fa-sticky-note"></i> Observaciones
                </label>
                <p style="margin:0;font-size:0.9rem;color:var(--text-primary);white-space:pre-wrap;">{{ $paciente->observaciones }}</p>
            </div>
        @endif
    </div>

    <div class="paciente-card">
        <h3 style="font-size:1.1rem;font-weight:600;color:var(--text-primary);margin:0 0 1rem;">
            <i class="fas fa-calendar-check"></i> Historial de citas
        </h3>

        @if($citas->count() > 0)
            <div class="citas-list">
                @foreach($citas as $cita)
                    <div class="cita-item">
                        <div class="cita-info">
                            <span class="cita-fecha">{{ $cita->fecha->format('d/m/Y') }}</span>
                            <span class="cita-hora">{{ substr($cita->hora_inicio, 0, 5) }} - {{ substr($cita->hora_fin, 0, 5) }}</span>
                            <span class="badge-estado {{ $cita->estado }}">
                                {{ ucfirst($cita->estado) }}
                            </span>
                            <span style="font-size:0.85rem;color:var(--text-secondary);">
                                <i class="fas {{ $cita->tipo == 'online' ? 'fa-video' : 'fa-building' }}"></i>
                                {{ ucfirst($cita->tipo) }}
                            </span>
                        </div>
                        <a href="{{ route('citas.editar', $cita->id) }}" class="btn-icono" title="Ver cita">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                @endforeach
            </div>
            <div style="margin-top:1rem;">{{ $citas->links() }}</div>
        @else
            <div style="text-align:center;padding:2rem;color:var(--text-secondary);">
                <i class="fas fa-calendar-times" style="font-size:2rem;display:block;margin-bottom:0.5rem;"></i>
                Este paciente no tiene citas registradas.
            </div>
        @endif
    </div>
</div>
@endsection
