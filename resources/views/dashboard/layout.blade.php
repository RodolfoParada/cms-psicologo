<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Panel') — PsicoCMS</title>
    <link rel="stylesheet" href="{{ asset('dashboard/css/dashboard.css') }}">
    @php
        $faPath = asset('themes/tema-base/fonts/fontawesome-free-6.1.2-web/css/all.min.css');
    @endphp
    <link rel="stylesheet" href="{{ $faPath }}">
    @stack('styles')
</head>
<body>
    @php
        $psicologa = Auth::guard('psicologa')->user();
        $config = App\Models\ConfiguracionWeb::where('psicologa_id', $psicologa->id)->first();
        $dashboardTema = $config->dashboard_tema ?? 'claro';
        $dashboardColor = $config->dashboard_color ?? '#4a7cc4';
        $iniciales = strtoupper(substr($psicologa->nombre, 0, 1) . substr($psicologa->apellidos, 0, 1));
        $nombreCompleto = $psicologa->nombre_completo;
    @endphp

    <div class="dashboard" data-dashboard-theme="{{ $dashboardTema }}" data-dashboard-color="{{ $dashboardColor }}">
        {{-- Sidebar overlay --}}
        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        {{-- Sidebar --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <div class="logo-icon">P</div>
                <span class="logo-text">PsicoCMS</span>
            </div>

            <nav class="sidebar-nav">
                {{-- Gestión principal --}}
                <div class="nav-section">
                    <div class="nav-section-title">Gestión principal</div>
                    <a href="{{ route('dashboard.inicio') }}" class="nav-item {{ request()->routeIs('dashboard.inicio') ? 'active' : '' }}">
                        <span class="icon"><i class="fas fa-chart-pie"></i></span>
                        Inicio
                    </a>
                    <a href="{{ route('citas.index') }}" class="nav-item {{ request()->routeIs('citas*') && !request()->routeIs('calendario*') ? 'active' : '' }}">
                        <span class="icon"><i class="fas fa-calendar-check"></i></span>
                        Citas
                    </a>
                    <a href="{{ route('calendario.index') }}" class="nav-item {{ request()->routeIs('calendario*') ? 'active' : '' }}">
                        <span class="icon"><i class="fas fa-calendar-alt"></i></span>
                        Calendario
                    </a>
                    <a href="{{ route('pacientes.index') }}" class="nav-item {{ request()->routeIs('pacientes*') ? 'active' : '' }}">
                        <span class="icon"><i class="fas fa-users"></i></span>
                        Pacientes
                    </a>
                    <a href="{{ route('historias.index') }}" class="nav-item {{ request()->routeIs('historias*') ? 'active' : '' }}">
                        <span class="icon"><i class="fas fa-notes-medical"></i></span>
                        Historias clínicas
                    </a>
                </div>

                {{-- Contenido web --}}
                <div class="nav-section">
                    <div class="nav-section-title nav-toggle" style="cursor:pointer;">
                        Contenido web <span class="arrow"><i class="fas fa-chevron-right"></i></span>
                    </div>
                    <div class="nav-children">
                        <a href="{{ route('blog.index') }}" class="nav-item-child {{ request()->routeIs('blog*') && !request()->routeIs('blog.categorias*') ? 'active' : '' }}">Blog</a>
                        <a href="{{ route('blog.categorias') }}" class="nav-item-child {{ request()->routeIs('blog.categorias*') ? 'active' : '' }}">Categorías blog</a>
                        <a href="{{ route('faq.index') }}" class="nav-item-child {{ request()->routeIs('faq*') ? 'active' : '' }}">Preguntas frecuentes</a>
                        <a href="#" class="nav-item-child">Servicios</a>
                        <a href="#" class="nav-item-child">Especialidades</a>
                    </div>
                </div>

                {{-- Configuración --}}
                <div class="nav-section">
                    <div class="nav-section-title nav-toggle" style="cursor:pointer;">
                        Configuración <span class="arrow"><i class="fas fa-chevron-right"></i></span>
                    </div>
                    <div class="nav-children">
                        <a href="{{ route('disponibilidad.index') }}" class="nav-item-child {{ request()->routeIs('disponibilidad*') ? 'active' : '' }}">Disponibilidad</a>
                        <a href="{{ route('temas.index') }}" class="nav-item-child {{ request()->routeIs('temas*') ? 'active' : '' }}">Temas visuales</a>
                        <a href="{{ route('imagenes.index') }}" class="nav-item-child {{ request()->routeIs('imagenes*') ? 'active' : '' }}">Imágenes</a>
                        <a href="{{ route('frases-publicas.index') }}" class="nav-item-child {{ request()->routeIs('frases-publicas*') ? 'active' : '' }}">Frases públicas</a>
                        <a href="{{ route('redes-sociales.index') }}" class="nav-item-child {{ request()->routeIs('redes-sociales*') ? 'active' : '' }}">Redes sociales</a>
                        <a href="{{ route('notificaciones.index') }}" class="nav-item-child {{ request()->routeIs('notificaciones*') ? 'active' : '' }}">Email y notificaciones</a>
                        <a href="{{ route('proteccion-datos.index') }}" class="nav-item-child {{ request()->routeIs('proteccion-datos*') ? 'active' : '' }}">Protección de datos</a>
                        <a href="{{ route('configuracion-web.index') }}" class="nav-item-child {{ request()->routeIs('configuracion-web*') ? 'active' : '' }}">Configuración web</a>
                    </div>
                </div>
            </nav>

            <div class="sidebar-footer">
                <a href="/" target="_blank" class="nav-item">
                    <span class="icon"><i class="fas fa-external-link-alt"></i></span>
                    Ver web pública
                </a>
            </div>
        </aside>

        {{-- Main content --}}
        <main class="main-content">
            {{-- Header --}}
            <header class="header">
                <button class="header-toggle" id="sidebar-toggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="header-search">
                    <span class="search-icon"><i class="fas fa-search"></i></span>
                    <form action="{{ route('buscador.buscar') }}" method="GET" style="flex:1;display:flex;">
                        <input type="text" name="q" placeholder="Buscar citas, pacientes, historias..." value="{{ request('q') }}" style="flex:1;border:none;outline:none;background:transparent;font-size:1.3rem;color:var(--text-primary);padding:0.6rem 0;" onkeydown="if(event.key==='Enter'){this.form.submit();}">
                    </form>
                </div>

                <div class="header-actions">
                    <button class="header-btn" id="btn-ayuda" title="Ayuda" onclick="window.location.href='{{ route('ayuda.index') }}'">
                        <i class="fas fa-question-circle"></i>
                    </button>
                    <button class="header-btn" id="btn-tema" title="Personalizar tema">
                        <i class="fas fa-palette"></i>
                    </button>

                    <div class="header-user" onclick="window.location.href='{{ route('perfil.index') }}'" style="cursor:pointer;">
                        <div class="avatar">
                            @if ($psicologa->foto)
                                <img src="{{ asset('storage/' . $psicologa->foto) }}" alt="{{ $nombreCompleto }}">
                            @else
                                {{ $iniciales }}
                            @endif
                        </div>
                        <span class="user-name">{{ $nombreCompleto }}</span>
                    </div>
                </div>
            </header>

            {{-- Page content --}}
            <div class="page-content">
                @yield('contenido')
            </div>
        </main>
    </div>

    {{-- Theme modal --}}
    <div class="theme-modal" id="themeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-palette"></i> Personalizar dashboard</h3>
                <button class="modal-close" id="themeModalClose">&times;</button>
            </div>

            <div class="modal-section">
                <label>Tema</label>
                <div class="theme-options">
                    <div class="theme-option {{ $dashboardTema == 'claro' ? 'selected' : '' }}" data-theme-value="claro">
                        <i class="fas fa-sun"></i> Claro
                    </div>
                    <div class="theme-option {{ $dashboardTema == 'oscuro' ? 'selected' : '' }}" data-theme-value="oscuro">
                        <i class="fas fa-moon"></i> Oscuro
                    </div>
                </div>
            </div>

            <div class="modal-section">
                <label>Color principal</label>
                <div class="color-options" id="colorOptions">
                    @php
                        $colors = ['#4a7cc4', '#7c3aed', '#ec4899', '#f59e0b', '#10b981', '#06b6d4', '#f43f5e', '#8b5cf6'];
                    @endphp
                    @foreach($colors as $color)
                        <div class="color-swatch {{ $dashboardColor == $color ? 'selected' : '' }}"
                             style="background:{{ $color }};color:{{ $color }};"
                             data-color="{{ $color }}"
                             title="{{ $color }}"></div>
                    @endforeach
                </div>
            </div>

            <div style="display:flex;gap:0.8rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid var(--border);">
                <button id="saveThemeBtn" style="padding:0.6rem 1.5rem;border-radius:8px;background:var(--accent);color:#fff;border:none;font-weight:600;cursor:pointer;">
                    <i class="fas fa-save"></i> Guardar preferencias
                </button>
            </div>
        </div>
    </div>

    <script src="{{ asset('dashboard/js/dashboard.js') }}"></script>
    @stack('scripts')
</body>
</html>
