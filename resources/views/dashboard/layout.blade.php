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
        $iniciales = strtoupper(substr($psicologa->nombre, 0, 1) . substr($psicologa->apellidos, 0, 1));
        $nombreCompleto = $psicologa->nombre_completo;
    @endphp

    <div class="dashboard">
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
                        <a href="#" class="nav-item-child">Preguntas frecuentes</a>
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
                        <a href="#" class="nav-item-child">Temas visuales</a>
                        <a href="#" class="nav-item-child">Imágenes</a>
                        <a href="#" class="nav-item-child">Frases públicas</a>
                        <a href="#" class="nav-item-child">Redes sociales</a>
                        <a href="#" class="nav-item-child">Email y notificaciones</a>
                        <a href="#" class="nav-item-child">Configuración web</a>
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
                    <input type="text" placeholder="Buscar citas, pacientes, historias...">
                </div>

                <div class="header-actions">
                    <button class="header-btn" title="Ayuda">
                        <i class="fas fa-question-circle"></i>
                    </button>
                    <button class="header-btn" title="Tema">
                        <i class="fas fa-palette"></i>
                    </button>

                    <div class="header-user" onclick="window.location.href='#'">
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

    <script src="{{ asset('dashboard/js/dashboard.js') }}"></script>
    @stack('scripts')
</body>
</html>
