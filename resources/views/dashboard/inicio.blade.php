@extends('dashboard.layout')

@section('titulo', 'Inicio')

@section('contenido')
<div class="page-header">
    <h1>Bienvenida, {{ Auth::guard('psicologa')->user()->nombre_completo }}</h1>
    <p>Resumen de tu actividad en PsicoCMS</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $citasHoy }}</h3>
            <p>Citas hoy</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-calendar-week"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalCitas }}</h3>
            <p>Total citas</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $citasPendientes }}</h3>
            <p>Pendientes</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-newspaper"></i>
        </div>
        <div class="stat-info">
            <h3>0</h3>
            <p>Artículos</p>
        </div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h2>Próximas citas</h2>
            <a href="{{ route('citas.crear') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nueva cita
            </a>
        </div>
        <div class="card-body">
            @if ($proximasCitas->count() > 0)
                <div style="display:flex; flex-direction:column; gap:0.8rem;">
                    @foreach ($proximasCitas as $cita)
                        <div style="display:flex; align-items:center; gap:1rem; padding:0.8rem 1rem; border:0.1rem solid var(--color-border); border-radius:var(--radius);">
                            <div style="text-align:center; min-width:4rem;">
                                <div style="font-size:1.8rem; font-weight:700; color:var(--color-primary);">{{ $cita->fecha->format('d') }}</div>
                                <div style="font-size:1.1rem; color:var(--color-text-light);">{{ $cita->fecha->format('M') }}</div>
                            </div>
                            <div style="flex:1;">
                                <div style="font-weight:600;">{{ $cita->paciente_nombre }}</div>
                                <div style="font-size:1.2rem; color:var(--color-text-light);">
                                    {{ substr($cita->hora_inicio, 0, 5) }} - {{ substr($cita->hora_fin, 0, 5) }}
                                    &middot; {{ $cita->tipo == 'online' ? 'Online' : 'Presencial' }}
                                </div>
                            </div>
                            <a href="{{ route('citas.editar', $cita->id) }}" class="btn btn-outline btn-sm">Ver</a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="icon"><i class="fas fa-calendar-day"></i></div>
                    <h3>No hay citas próximas</h3>
                    <p>Aún no tienes citas programadas.</p>
                    <a href="{{ route('citas.crear') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Añadir cita
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Últimos pacientes</h2>
            <a href="{{ route('pacientes.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-users"></i> Ver todos
            </a>
        </div>
        <div class="card-body">
            <div class="empty-state">
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3>Sin pacientes aún</h3>
                <p>Cuando registres pacientes o éstos pidan cita a través de la web, aparecerán en esta sección.</p>
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Añadir paciente
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-top:1.6rem;">
    <div class="card-header">
        <h2>Accesos rápidos</h2>
    </div>
    <div class="card-body">
        <div class="grid-4">
            <a href="{{ route('citas.index') }}" style="display:flex; flex-direction:column; align-items:center; gap:0.8rem; padding:1.6rem; border:0.1rem solid var(--color-border); border-radius:var(--radius); transition:all 0.2s;">
                <span style="font-size:2.4rem; color:var(--color-primary);"><i class="fas fa-calendar-alt"></i></span>
                <span style="font-size:1.3rem; font-weight:500;">Citas</span>
            </a>
            <a href="{{ route('disponibilidad.index') }}" style="display:flex; flex-direction:column; align-items:center; gap:0.8rem; padding:1.6rem; border:0.1rem solid var(--color-border); border-radius:var(--radius); transition:all 0.2s;">
                <span style="font-size:2.4rem; color:var(--color-success);"><i class="fas fa-clock"></i></span>
                <span style="font-size:1.3rem; font-weight:500;">Disponibilidad</span>
            </a>
            <a href="{{ route('temas.index') }}" style="display:flex; flex-direction:column; align-items:center; gap:0.8rem; padding:1.6rem; border:0.1rem solid var(--color-border); border-radius:var(--radius); transition:all 0.2s;">
                <span style="font-size:2.4rem; color:var(--color-warning);"><i class="fas fa-paint-roller"></i></span>
                <span style="font-size:1.3rem; font-weight:500;">Temas</span>
            </a>
            <a href="{{ route('configuracion-web.index') }}" style="display:flex; flex-direction:column; align-items:center; gap:0.8rem; padding:1.6rem; border:0.1rem solid var(--color-border); border-radius:var(--radius); transition:all 0.2s;">
                <span style="font-size:2.4rem; color:var(--color-danger);"><i class="fas fa-cog"></i></span>
                <span style="font-size:1.3rem; font-weight:500;">Configuración</span>
            </a>
        </div>
    </div>
</div>
@endsection
