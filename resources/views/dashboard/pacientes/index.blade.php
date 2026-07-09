@extends('dashboard.layout')

@section('titulo', 'Pacientes')

@push('styles')
<style>
.pacientes-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.pacientes-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.pacientes-grid {
    display: grid;
    gap: 1rem;
}

.paciente-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: box-shadow 0.2s;
}

.paciente-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.paciente-info h3 {
    font-size: 1.05rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 0.3rem;
}

.paciente-meta {
    display: flex;
    gap: 1.2rem;
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.paciente-meta span {
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.paciente-actions {
    display: flex;
    gap: 0.5rem;
}

.paciente-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--accent);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.badge-paciente {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    background: #dbeafe;
    color: #1e40af;
}

@media (max-width: 768px) {
    .paciente-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.8rem;
    }
    .paciente-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>
@endpush

@section('contenido')
<div class="pacientes-header">
    <h2><i class="fas fa-users" style="margin-right:0.5rem;"></i>Pacientes</h2>
    <a href="{{ route('pacientes.crear') }}" class="btn-accion" style="background:var(--accent);color:#fff;text-decoration:none;padding:0.6rem 1.2rem;border-radius:8px;font-size:0.9rem;font-weight:600;">
        <i class="fas fa-plus"></i> Nuevo paciente
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($pacientes->count() > 0)
    <div class="pacientes-grid">
        @foreach($pacientes as $paciente)
            <div class="paciente-card">
                <div style="display:flex;align-items:center;gap:1rem;flex:1;min-width:0;">
                    <div class="paciente-avatar">
                        {{ strtoupper(substr($paciente->nombre, 0, 1)) }}
                    </div>
                    <div class="paciente-info">
                        <h3>
                            <a href="{{ route('pacientes.show', $paciente->id) }}" style="color:inherit;text-decoration:none;">
                                {{ $paciente->nombre }}
                            </a>
                        </h3>
                        <div class="paciente-meta">
                            <span><i class="fas fa-phone"></i> {{ $paciente->telefono }}</span>
                            @if($paciente->email)
                                <span><i class="fas fa-envelope"></i> {{ $paciente->email }}</span>
                            @endif
                            <span class="badge-paciente">
                                <i class="fas fa-calendar-check"></i> {{ $paciente->citas_count ?? 0 }} cita(s)
                            </span>
                        </div>
                    </div>
                </div>
                <div class="paciente-actions">
                    <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn-icono" title="Ver ficha">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('pacientes.editar', $paciente->id) }}" class="btn-icono" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('pacientes.eliminar', $paciente->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este paciente? Las citas quedarán sin paciente asociado.');">
                        @csrf @method('DELETE')
                        <button class="btn-icono danger" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top:2rem;">
        {{ $pacientes->links() }}
    </div>
@else
    <div style="text-align:center;padding:4rem 2rem;background:var(--card-bg);border-radius:12px;border:1px solid var(--border);">
        <div style="font-size:3rem;color:var(--text-secondary);margin-bottom:1rem;">
            <i class="fas fa-users"></i>
        </div>
        <h3 style="color:var(--text-primary);margin-bottom:0.5rem;">No hay pacientes todavía</h3>
        <p style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:1.5rem;">
            Los pacientes se crearán automáticamente al reservar cita, o puedes añadirlos manualmente.
        </p>
        <a href="{{ route('pacientes.crear') }}" style="background:var(--accent);color:#fff;text-decoration:none;padding:0.7rem 1.5rem;border-radius:8px;font-weight:600;display:inline-flex;align-items:center;gap:0.5rem;">
            <i class="fas fa-plus"></i> Añadir primer paciente
        </a>
    </div>
@endif
@endsection
