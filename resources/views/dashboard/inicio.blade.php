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
            <h3>0</h3>
            <p>Citas hoy</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3>0</h3>
            <p>Pacientes</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info">
            <h3>0</h3>
            <p>Historias clínicas</p>
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
            <a href="#" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nueva cita
            </a>
        </div>
        <div class="card-body">
            <div class="empty-state">
                <div class="icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <h3>No hay citas próximas</h3>
                <p>Aún no tienes citas programadas. Cuando tus pacientes reserven o añadas citas manualmente, aparecerán aquí.</p>
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Añadir cita
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Últimos pacientes</h2>
            <a href="#" class="btn btn-outline btn-sm">
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
            <a href="#" style="display:flex; flex-direction:column; align-items:center; gap:0.8rem; padding:1.6rem; border:0.1rem solid var(--color-border); border-radius:var(--radius); transition:all 0.2s;">
                <span style="font-size:2.4rem; color:var(--color-primary);"><i class="fas fa-calendar-alt"></i></span>
                <span style="font-size:1.3rem; font-weight:500;">Calendario</span>
            </a>
            <a href="#" style="display:flex; flex-direction:column; align-items:center; gap:0.8rem; padding:1.6rem; border:0.1rem solid var(--color-border); border-radius:var(--radius); transition:all 0.2s;">
                <span style="font-size:2.4rem; color:var(--color-success);"><i class="fas fa-clock"></i></span>
                <span style="font-size:1.3rem; font-weight:500;">Disponibilidad</span>
            </a>
            <a href="#" style="display:flex; flex-direction:column; align-items:center; gap:0.8rem; padding:1.6rem; border:0.1rem solid var(--color-border); border-radius:var(--radius); transition:all 0.2s;">
                <span style="font-size:2.4rem; color:var(--color-warning);"><i class="fas fa-paint-roller"></i></span>
                <span style="font-size:1.3rem; font-weight:500;">Temas</span>
            </a>
            <a href="#" style="display:flex; flex-direction:column; align-items:center; gap:0.8rem; padding:1.6rem; border:0.1rem solid var(--color-border); border-radius:var(--radius); transition:all 0.2s;">
                <span style="font-size:2.4rem; color:var(--color-danger);"><i class="fas fa-cog"></i></span>
                <span style="font-size:1.3rem; font-weight:500;">Configuración</span>
            </a>
        </div>
    </div>
</div>
@endsection
