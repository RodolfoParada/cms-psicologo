@extends('dashboard.layout')

@section('titulo', 'Historias clínicas')

@push('styles')
<style>
.historias-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.historias-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.historias-filtros {
    display: flex;
    gap: 0.8rem;
    align-items: center;
}

.historias-filtros select {
    padding: 0.5rem 0.8rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.85rem;
    background: var(--card-bg);
    color: var(--text-primary);
}

.historias-grid {
    display: grid;
    gap: 1rem;
}

.historia-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.2rem;
    transition: box-shadow 0.2s;
}

.historia-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.historia-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.8rem;
}

.historia-paciente {
    display: flex;
    align-items: center;
    gap: 0.7rem;
}

.historia-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--accent);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    flex-shrink: 0;
}

.historia-paciente-info h3 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.historia-paciente-info .fecha {
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.historia-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

.historia-contenido {
    font-size: 0.9rem;
    color: var(--text-secondary);
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 0.8rem;
}

.historia-archivos {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.historia-archivos .archivo-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.25rem 0.6rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    background: var(--bg-secondary);
    color: var(--text-secondary);
}

.historia-archivos .archivo-badge i {
    font-size: 0.8rem;
}

@media (max-width: 768px) {
    .historias-header {
        flex-direction: column;
        align-items: stretch;
    }
    .historias-filtros {
        flex-direction: column;
    }
}
</style>
@endpush

@section('contenido')
<div class="historias-header">
    <h2><i class="fas fa-notes-medical" style="margin-right:0.5rem;"></i>Historias clínicas</h2>
    <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
        <form method="GET" action="{{ route('historias.index') }}" style="display:flex;gap:0.5rem;align-items:center;">
            <select name="paciente_id" onchange="this.form.submit()">
                <option value="">Todos los pacientes</option>
                @foreach($pacientes as $p)
                    <option value="{{ $p->id }}" {{ request('paciente_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nombre }}
                    </option>
                @endforeach
            </select>
            @if(request('paciente_id'))
                <a href="{{ route('historias.index') }}" style="color:var(--text-secondary);font-size:0.85rem;">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            @endif
        </form>
        <a href="{{ route('historias.crear') }}" style="background:var(--accent);color:#fff;text-decoration:none;padding:0.6rem 1.2rem;border-radius:8px;font-size:0.9rem;font-weight:600;display:inline-flex;align-items:center;gap:0.4rem;">
            <i class="fas fa-plus"></i> Nueva historia
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($historias->count() > 0)
    <div class="historias-grid">
        @foreach($historias as $historia)
            <div class="historia-card">
                <div class="historia-header">
                    <div class="historia-paciente">
                        <div class="historia-avatar">
                            {{ strtoupper(substr($historia->paciente->nombre, 0, 1)) }}
                        </div>
                        <div class="historia-paciente-info">
                            <h3>{{ $historia->paciente->nombre }}</h3>
                            <div class="fecha">
                                <i class="far fa-calendar"></i>
                                {{ $historia->fecha_sesion->format('d/m/Y') }} &middot;
                                <i class="far fa-clock"></i>
                                {{ $historia->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>
                    <div class="historia-actions">
                        <a href="{{ route('historias.editar', $historia->id) }}" class="btn-icono" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('historias.eliminar', $historia->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta historia clínica? Los archivos se borrarán permanentemente.');">
                            @csrf @method('DELETE')
                            <button class="btn-icono danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="historia-contenido">
                    {!! strip_tags(Str::limit($historia->contenido, 300)) !!}
                </div>

                @if($historia->archivos->count() > 0)
                    <div class="historia-archivos">
                        @foreach($historia->archivos as $archivo)
                            <span class="archivo-badge">
                                <i class="fas {{ $archivo->tipo == 'pdf' ? 'fa-file-pdf' : 'fa-image' }}"></i>
                                {{ $archivo->nombre_original }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div style="margin-top:2rem;">
        {{ $historias->links() }}
    </div>
@else
    <div style="text-align:center;padding:4rem 2rem;background:var(--card-bg);border-radius:12px;border:1px solid var(--border);">
        <div style="font-size:3rem;color:var(--text-secondary);margin-bottom:1rem;">
            <i class="fas fa-notes-medical"></i>
        </div>
        <h3 style="color:var(--text-primary);margin-bottom:0.5rem;">No hay historias clínicas</h3>
        <p style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:1.5rem;">
            Las historias clínicas te permiten hacer un seguimiento de cada sesión con tus pacientes.
        </p>
        <a href="{{ route('historias.crear') }}" style="background:var(--accent);color:#fff;text-decoration:none;padding:0.7rem 1.5rem;border-radius:8px;font-weight:600;display:inline-flex;align-items:center;gap:0.5rem;">
            <i class="fas fa-plus"></i> Crear primera historia
        </a>
    </div>
@endif
@endsection
