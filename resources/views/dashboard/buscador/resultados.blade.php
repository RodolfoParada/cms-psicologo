@extends('dashboard.layout')

@section('titulo', 'Resultados de búsqueda')

@push('styles')
<style>
.busqueda-header {
    margin-bottom: 2rem;
}
.busqueda-header h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--color-text);
    margin: 0;
}
.busqueda-header p {
    color: var(--color-text-light);
    font-size: 1.3rem;
    margin: 0.3rem 0 0;
}
.result-group {
    margin-bottom: 2.5rem;
}
.result-group h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-text);
    margin: 0 0 1rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}
.result-group h3 span {
    font-size: 1.2rem;
    color: var(--color-text-light);
    font-weight: 400;
}
.result-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.2rem;
    background: var(--card-bg);
    border: 1px solid var(--color-border);
    border-radius: 8px;
    margin-bottom: 0.6rem;
    transition: background 0.2s;
}
.result-item:hover {
    background: var(--color-hover);
}
.result-item .info {
    flex: 1;
}
.result-item .info .titulo {
    font-weight: 600;
    color: var(--color-text);
    font-size: 1.4rem;
}
.result-item .info .detalle {
    font-size: 1.2rem;
    color: var(--color-text-light);
    margin-top: 0.2rem;
}
.result-item .accion a {
    display: inline-block;
    padding: 0.5rem 1.2rem;
    border-radius: 6px;
    background: var(--color-primary);
    color: #fff;
    font-size: 1.2rem;
    text-decoration: none;
    font-weight: 600;
    transition: opacity 0.2s;
}
.result-item .accion a:hover {
    opacity: 0.85;
}
.sin-resultados {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--color-text-light);
}
.sin-resultados i {
    font-size: 4rem;
    margin-bottom: 1rem;
    display: block;
    opacity: 0.3;
}
.sin-resultados h3 {
    font-size: 1.6rem;
    color: var(--color-text);
    margin-bottom: 0.3rem;
}
.sin-resultados p {
    font-size: 1.3rem;
}
</style>
@endpush

@section('contenido')
<div class="busqueda-header">
    <h2><i class="fas fa-search" style="margin-right:0.5rem;"></i>Resultados para "{{ $q }}"</h2>
</div>

@php $total = $citas->count() + $pacientes->count() + $historias->count() + $articulos->count(); @endphp

@if($total > 0)
    @if($citas->count() > 0)
    <div class="result-group">
        <h3><i class="fas fa-calendar-check" style="color:var(--color-primary);"></i> Citas <span>({{ $citas->count() }})</span></h3>
        @foreach($citas as $cita)
        <div class="result-item">
            <div class="info">
                <div class="titulo">{{ $cita->paciente_nombre }} — {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }} {{ $cita->hora_inicio ? substr($cita->hora_inicio, 0, 5) : '' }}</div>
                <div class="detalle">{{ $cita->tipo == 'online' ? 'Online' : 'Presencial' }} · {{ $cita->motivo ? Str::limit($cita->motivo, 60) : 'Sin motivo' }}</div>
            </div>
            <div class="accion"><a href="{{ route('citas.editar', $cita->id) }}">Ver</a></div>
        </div>
        @endforeach
    </div>
    @endif

    @if($pacientes->count() > 0)
    <div class="result-group">
        <h3><i class="fas fa-users" style="color:var(--color-primary);"></i> Pacientes <span>({{ $pacientes->count() }})</span></h3>
        @foreach($pacientes as $paciente)
        <div class="result-item">
            <div class="info">
                <div class="titulo">{{ $paciente->nombre }}</div>
                <div class="detalle">{{ $paciente->telefono }} · {{ $paciente->email ?? 'Sin email' }}</div>
            </div>
            <div class="accion"><a href="{{ route('pacientes.show', $paciente->id) }}">Ver</a></div>
        </div>
        @endforeach
    </div>
    @endif

    @if($historias->count() > 0)
    <div class="result-group">
        <h3><i class="fas fa-notes-medical" style="color:var(--color-primary);"></i> Historias clínicas <span>({{ $historias->count() }})</span></h3>
        @foreach($historias as $historia)
        <div class="result-item">
            <div class="info">
                <div class="titulo">{{ $historia->titulo }}</div>
                <div class="detalle">{{ $historia->paciente->nombre ?? 'Paciente eliminado' }} · {{ \Carbon\Carbon::parse($historia->fecha_sesion)->format('d/m/Y') }}</div>
            </div>
            <div class="accion"><a href="{{ route('historias.editar', $historia->id) }}">Ver</a></div>
        </div>
        @endforeach
    </div>
    @endif

    @if($articulos->count() > 0)
    <div class="result-group">
        <h3><i class="fas fa-blog" style="color:var(--color-primary);"></i> Blog <span>({{ $articulos->count() }})</span></h3>
        @foreach($articulos as $articulo)
        <div class="result-item">
            <div class="info">
                <div class="titulo">{{ $articulo->titulo }}</div>
                <div class="detalle">{{ $articulo->categoria->nombre ?? 'Sin categoría' }} · {{ \Carbon\Carbon::parse($articulo->created_at)->format('d/m/Y') }}</div>
            </div>
            <div class="accion"><a href="{{ route('blog.editar', $articulo->id) }}">Ver</a></div>
        </div>
        @endforeach
    </div>
    @endif
@else
    <div class="sin-resultados">
        <i class="fas fa-search"></i>
        <h3>Sin resultados</h3>
        <p>No se encontraron coincidencias para "{{ $q }}".</p>
    </div>
@endif
@endsection
