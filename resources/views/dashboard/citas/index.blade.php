@extends('dashboard.layout')

@section('titulo', 'Citas')

@section('contenido')
<div class="page-header" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
    <div>
        <h1>Citas</h1>
        <p>Gestiona todas las citas de tus pacientes</p>
    </div>
    <a href="{{ route('citas.crear') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nueva cita
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-auto-close">{{ session('success') }}</div>
@endif

{{-- Filtros --}}
<div class="card" style="margin-bottom:1.6rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('citas.index') }}">
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(18rem, 1fr)); gap:1rem; align-items:end;">
                <div class="form-group" style="margin:0;">
                    <label>Desde</label>
                    <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Estado</label>
                    <select name="estado" class="form-control">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                        <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                        <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Tipo</label>
                    <select name="tipo" class="form-control">
                        <option value="">Todos</option>
                        <option value="online" {{ request('tipo') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="presencial" {{ request('tipo') == 'presencial' ? 'selected' : '' }}>Presencial</option>
                    </select>
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Buscar</label>
                    <input type="text" name="busqueda" class="form-control" value="{{ request('busqueda') }}" placeholder="Nombre o teléfono">
                </div>
                <div class="form-group" style="margin:0; display:flex; gap:0.5rem;">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filtrar</button>
                    <a href="{{ route('citas.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-times"></i> Limpiar</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Listado --}}
<div class="card">
    <div class="card-body" style="padding:0;">
        @if ($citas->count() > 0)
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Paciente</th>
                            <th>Teléfono</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($citas as $cita)
                            <tr>
                                <td>{{ $cita->fecha->format('d/m/Y') }}</td>
                                <td>{{ substr($cita->hora_inicio, 0, 5) }} - {{ substr($cita->hora_fin, 0, 5) }}</td>
                                <td>{{ $cita->paciente_nombre }}</td>
                                <td>{{ $cita->paciente_telefono }}</td>
                                <td>
                                    <span class="badge {{ $cita->tipo == 'online' ? 'badge-info' : 'badge-secondary' }}">
                                        {{ $cita->tipo == 'online' ? 'Online' : 'Presencial' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge
                                        @switch($cita->estado)
                                            @case('pendiente') badge-warning @break
                                            @case('confirmada') badge-info @break
                                            @case('completada') badge-success @break
                                            @case('cancelada') badge-danger @break
                                        @endswitch
                                    ">{{ ucfirst($cita->estado) }}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('citas.editar', $cita->id) }}" class="btn btn-outline btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('citas.eliminar', $cita->id) }}"
                                              onsubmit="return confirm('¿Eliminar esta cita?')" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        <div class="btn-group" style="gap:0.3rem;">
                                            <form method="POST" action="{{ route('citas.estado', $cita->id) }}" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="estado" value="confirmada">
                                                <button type="submit" class="btn btn-success btn-sm" title="Confirmar" {{ $cita->estado == 'confirmada' || $cita->estado == 'completada' || $cita->estado == 'cancelada' ? 'disabled' : '' }}>
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('citas.estado', $cita->id) }}" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="estado" value="cancelada">
                                                <button type="submit" class="btn btn-warning btn-sm" title="Cancelar" {{ $cita->estado == 'completada' || $cita->estado == 'cancelada' ? 'disabled' : '' }}>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:1.6rem;">
                {{ $citas->links() }}
            </div>

        @else
            <div class="empty-state">
                <div class="icon"><i class="fas fa-calendar-times"></i></div>
                <h3>No hay citas</h3>
                <p>Aún no hay citas registradas. Crea la primera cita para empezar.</p>
                <a href="{{ route('citas.crear') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva cita
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.pagination { display:flex; gap:0.4rem; align-items:center; justify-content:center; flex-wrap:wrap; }
.pagination a, .pagination span {
    padding:0.6rem 1.2rem; border:0.1rem solid var(--color-border); border-radius:0.4rem;
    font-size:1.3rem; color:var(--color-text); text-decoration:none;
}
.pagination a:hover { background:var(--color-hover); border-color:var(--color-primary); }
.pagination .active { background:var(--color-primary); color:#fff; border-color:var(--color-primary); }
</style>
@endsection
