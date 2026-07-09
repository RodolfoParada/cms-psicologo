@extends('dashboard.layout')

@section('titulo', 'Configuración web')

@push('styles')
<style>
.conf-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    border-bottom: 2px solid var(--border);
    padding-bottom: 0.8rem;
}

.conf-tab {
    padding: 0.5rem 1.2rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    background: var(--bg-secondary);
    color: var(--text-secondary);
    transition: all 0.2s;
}

.conf-tab:hover {
    background: var(--border);
    color: var(--text-primary);
}

.conf-tab.activo {
    background: var(--accent);
    color: #fff;
}

.conf-section {
    display: none;
}

.conf-section.activo {
    display: block;
}

.conf-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.conf-grid .full { grid-column: 1 / -1; }

@media (max-width: 768px) {
    .conf-grid { grid-template-columns: 1fr; }
}

.conf-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.conf-card h3 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.3rem;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 0.6rem 0.8rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.9rem;
    background: var(--input-bg);
    color: var(--text-primary);
    box-sizing: border-box;
}

.form-group textarea { min-height: 100px; resize: vertical; }
.form-group input[type="file"] { padding: 0.4rem; }

.form-group .toggle-wrap {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 0.5rem 0;
}

.form-group .toggle {
    width: 44px;
    height: 24px;
    background: var(--border);
    border-radius: 12px;
    position: relative;
    cursor: pointer;
    transition: background 0.2s;
    flex-shrink: 0;
    appearance: none;
    -webkit-appearance: none;
}

.form-group .toggle:checked {
    background: var(--accent);
}

.form-group .toggle::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 20px;
    height: 20px;
    background: #fff;
    border-radius: 50%;
    transition: transform 0.2s;
}

.form-group .toggle:checked::after {
    transform: translateX(20px);
}

.foto-preview {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--border);
    margin-bottom: 0.5rem;
}

.logo-preview {
    max-height: 60px;
    border-radius: 4px;
    margin-bottom: 0.5rem;
}

.check-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.8rem;
}

@media (max-width: 768px) {
    .check-grid { grid-template-columns: 1fr; }
}

.item-lista {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 0.6rem 0;
    border-bottom: 1px solid var(--border);
}

.item-lista:last-child { border-bottom: none; }
.item-lista .info { flex: 1; }
.item-lista .info strong { display: block; font-size: 0.9rem; color: var(--text-primary); }
.item-lista .info span { font-size: 0.8rem; color: var(--text-secondary); }
</style>
@endpush

@section('contenido')
<div style="margin-bottom:1.5rem;">
    <h2 style="font-size:1.5rem;font-weight:700;color:var(--text-primary);margin:0;">
        <i class="fas fa-globe" style="margin-right:0.5rem;"></i>Configuración web
    </h2>
    <p style="color:var(--text-secondary);font-size:0.9rem;margin:0.3rem 0 0;">
        Administra toda la información que se muestra en la parte pública de tu web.
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="conf-tabs">
    <button class="conf-tab activo" data-tab="personal">Información personal</button>
    <button class="conf-tab" data-tab="web">Configuración web</button>
    <button class="conf-tab" data-tab="servicios">Servicios</button>
    <button class="conf-tab" data-tab="especialidades">Especialidades</button>
    <button class="conf-tab" data-tab="precios">Planes y precios</button>
</div>

<form method="POST" action="{{ route('configuracion-web.guardar') }}" enctype="multipart/form-data">
    @csrf

    <div class="conf-section activo" id="tab-personal">
        <div class="conf-card">
            <h3><i class="fas fa-user"></i> Datos personales</h3>
            <div class="conf-grid">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $psicologa->nombre) }}" required>
                </div>
                <div class="form-group">
                    <label>Apellidos *</label>
                    <input type="text" name="apellidos" value="{{ old('apellidos', $psicologa->apellidos) }}" required>
                </div>
                <div class="form-group">
                    <label>Número de colegiado</label>
                    <input type="text" name="numero_colegiado" value="{{ old('numero_colegiado', $psicologa->numero_colegiado) }}">
                </div>
                <div class="form-group">
                    <label>Eslogan / Frase gancho</label>
                    <input type="text" name="slogan" value="{{ old('slogan', $psicologa->slogan) }}" placeholder="Tu espacio de confianza para el bienestar emocional">
                </div>
                <div class="form-group">
                    <label>Teléfono para citas</label>
                    <input type="text" name="telefono_citas" value="{{ old('telefono_citas', $psicologa->telefono_citas) }}">
                </div>
                <div class="form-group">
                    <label>Email para citas</label>
                    <input type="email" name="email_citas" value="{{ old('email_citas', $psicologa->email_citas) }}">
                </div>
                <div class="form-group full">
                    <label>Sobre mí</label>
                    <textarea name="sobre_mi" rows="6">{{ old('sobre_mi', $psicologa->sobre_mi) }}</textarea>
                </div>
            </div>
        </div>

        <div class="conf-card">
            <h3><i class="fas fa-map-marker-alt"></i> Dirección y ubicación</h3>
            <div class="conf-grid">
                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $psicologa->direccion) }}" placeholder="Calle, número, piso">
                </div>
                <div class="form-group">
                    <label>Ciudad</label>
                    <input type="text" name="ciudad" value="{{ old('ciudad', $psicologa->ciudad) }}">
                </div>
                <div class="form-group">
                    <label>Código postal</label>
                    <input type="text" name="codigo_postal" value="{{ old('codigo_postal', $psicologa->codigo_postal) }}">
                </div>
                <div class="form-group">
                    <label>País</label>
                    <input type="text" name="pais" value="{{ old('pais', $psicologa->pais) }}">
                </div>
            </div>
        </div>

        <div class="conf-card">
            <h3><i class="fas fa-camera"></i> Foto de perfil</h3>
            @if($psicologa->foto)
                <img src="{{ asset('storage/' . $psicologa->foto) }}" alt="Foto" class="foto-preview">
            @endif
            <div class="form-group">
                <label>{{ $psicologa->foto ? 'Cambiar foto' : 'Subir foto' }}</label>
                <input type="file" name="foto" accept="image/*">
                <small style="color:var(--text-secondary);font-size:0.75rem;">Formatos: JPEG, PNG, WebP. Máximo 2MB. Idealmente sin fondo.</small>
            </div>
        </div>
    </div>

    <div class="conf-section" id="tab-web">
        <div class="conf-card">
            <h3><i class="fas fa-paint-brush"></i> Apariencia general</h3>
            <div class="conf-grid">
                <div class="form-group">
                    <label>Modo de visualización</label>
                    <select name="modo_visualizacion">
                        <option value="multipagina" {{ $config->modo_visualizacion == 'multipagina' ? 'selected' : '' }}>Multipágina (secciones separadas)</option>
                        <option value="landing" {{ $config->modo_visualizacion == 'landing' ? 'selected' : '' }}>Landing (una sola página)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>URL Google Maps (iframe)</label>
                    <input type="text" name="google_maps_url" value="{{ old('google_maps_url', $config->google_maps_url) }}" placeholder="https://www.google.com/maps/embed?pb=...">
                </div>
                <div class="form-group">
                    <label>Logo</label>
                    @if($config->logo)
                        <div><img src="{{ asset('storage/' . $config->logo) }}" alt="Logo" class="logo-preview"></div>
                    @endif
                    <input type="file" name="logo" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Favicon</label>
                    @if($config->favicon)
                        <div><img src="{{ asset('storage/' . $config->favicon) }}" alt="Favicon" style="height:32px;"></div>
                    @endif
                    <input type="file" name="favicon" accept="image/*">
                </div>
            </div>
        </div>

        <div class="conf-card">
            <h3><i class="fas fa-search"></i> SEO y meta datos</h3>
            <div class="conf-grid">
                <div class="form-group full">
                    <label>Meta descripción</label>
                    <textarea name="meta_descripcion" rows="3" maxlength="500">{{ old('meta_descripcion', $config->meta_descripcion) }}</textarea>
                    <small style="color:var(--text-secondary);font-size:0.75rem;">Máximo 160 caracteres recomendados para SEO.</small>
                </div>
                <div class="form-group full">
                    <label>Meta keywords (separadas por comas)</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $config->meta_keywords) }}" placeholder="psicología, terapia, bienestar emocional">
                </div>
            </div>
        </div>

        <div class="conf-card">
            <h3><i class="fas fa-toggle-on"></i> Secciones visibles</h3>
            <p style="color:var(--text-secondary);font-size:0.85rem;margin:0 0 1rem;">Activa o desactiva las secciones de tu web pública.</p>
            <div class="check-grid">
                <div class="form-group">
                    <div class="toggle-wrap">
                        <input type="checkbox" name="mostrar_blog" class="toggle" id="mostrar_blog" value="1" {{ $config->mostrar_blog ? 'checked' : '' }}>
                        <label for="mostrar_blog" style="margin:0;cursor:pointer;">Blog</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="toggle-wrap">
                        <input type="checkbox" name="mostrar_faq" class="toggle" id="mostrar_faq" value="1" {{ $config->mostrar_faq ? 'checked' : '' }}>
                        <label for="mostrar_faq" style="margin:0;cursor:pointer;">Preguntas frecuentes</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="toggle-wrap">
                        <input type="checkbox" name="mostrar_reservas" class="toggle" id="mostrar_reservas" value="1" {{ $config->mostrar_reservas ? 'checked' : '' }}>
                        <label for="mostrar_reservas" style="margin:0;cursor:pointer;">Reserva de citas</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="toggle-wrap">
                        <input type="checkbox" name="mostrar_especialidades" class="toggle" id="mostrar_especialidades" value="1" {{ $config->mostrar_especialidades ? 'checked' : '' }}>
                        <label for="mostrar_especialidades" style="margin:0;cursor:pointer;">Especialidades</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="toggle-wrap">
                        <input type="checkbox" name="mostrar_servicios" class="toggle" id="mostrar_servicios" value="1" {{ $config->mostrar_servicios ? 'checked' : '' }}>
                        <label for="mostrar_servicios" style="margin:0;cursor:pointer;">Servicios</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="toggle-wrap">
                        <input type="checkbox" name="mostrar_testimonios" class="toggle" id="mostrar_testimonios" value="1" {{ $config->mostrar_testimonios ? 'checked' : '' }}>
                        <label for="mostrar_testimonios" style="margin:0;cursor:pointer;">Testimonios</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="conf-section" id="tab-servicios">
        <div class="conf-card">
            <h3><i class="fas fa-concierge-bell"></i> Servicios</h3>
            <p style="color:var(--text-secondary);font-size:0.85rem;margin:0 0 1rem;">
                Los servicios se gestionan desde la sección de servicios.
                <a href="#" style="color:var(--accent);">Ir a Servicios</a>
            </p>
            @if($servicios->count() > 0)
                @foreach($servicios as $s)
                    <div class="item-lista">
                        <div class="info">
                            <strong>{{ $s->nombre }}</strong>
                            <span>{{ Str::limit($s->descripcion, 100) }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <p style="color:var(--text-secondary);font-size:0.9rem;">Aún no has añadido servicios.</p>
            @endif
        </div>
    </div>

    <div class="conf-section" id="tab-especialidades">
        <div class="conf-card">
            <h3><i class="fas fa-star"></i> Especialidades</h3>
            <p style="color:var(--text-secondary);font-size:0.85rem;margin:0 0 1rem;">
                Las especialidades se gestionan desde la sección de especialidades.
                <a href="#" style="color:var(--accent);">Ir a Especialidades</a>
            </p>
            @if($especialidades->count() > 0)
                @foreach($especialidades as $e)
                    <div class="item-lista">
                        <div class="info">
                            <strong>{{ $e->nombre }}</strong>
                            <span>{{ Str::limit($e->descripcion, 100) }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <p style="color:var(--text-secondary);font-size:0.9rem;">Aún no has añadido especialidades.</p>
            @endif
        </div>
    </div>

    <div class="conf-section" id="tab-precios">
        <div class="conf-card">
            <h3><i class="fas fa-tags"></i> Planes online</h3>
            @if($preciosOnline->count() > 0)
                @foreach($preciosOnline as $p)
                    <div class="item-lista">
                        <div class="info">
                            <strong>{{ $p->nombre }}</strong>
                            <span>{{ $p->precio_mensual ? $p->precio_mensual . '€/mes' : '' }} {{ $p->precio_anual ? '| ' . $p->precio_anual . '€/año' : '' }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <p style="color:var(--text-secondary);font-size:0.9rem;">Sin planes online configurados.</p>
            @endif
        </div>
        <div class="conf-card">
            <h3><i class="fas fa-tags"></i> Planes presenciales</h3>
            @if($preciosPresencial->count() > 0)
                @foreach($preciosPresencial as $p)
                    <div class="item-lista">
                        <div class="info">
                            <strong>{{ $p->nombre }}</strong>
                            <span>{{ $p->precio_mensual ? $p->precio_mensual . '€/mes' : '' }} {{ $p->precio_anual ? '| ' . $p->precio_anual . '€/año' : '' }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <p style="color:var(--text-secondary);font-size:0.9rem;">Sin planes presenciales configurados.</p>
            @endif
        </div>
    </div>

    <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--border);display:flex;gap:1rem;justify-content:flex-end;">
        <button type="submit" style="padding:0.7rem 2rem;border-radius:8px;background:var(--accent);color:#fff;border:none;font-weight:600;font-size:0.95rem;cursor:pointer;">
            <i class="fas fa-save"></i> Guardar configuración
        </button>
    </div>
</form>

<script>
(function() {
    const tabs = document.querySelectorAll('.conf-tab');
    const sections = {
        personal: document.getElementById('tab-personal'),
        web: document.getElementById('tab-web'),
        servicios: document.getElementById('tab-servicios'),
        especialidades: document.getElementById('tab-especialidades'),
        precios: document.getElementById('tab-precios'),
    };

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('activo'));
            this.classList.add('activo');
            Object.values(sections).forEach(s => s.classList.remove('activo'));
            const target = sections[this.dataset.tab];
            if (target) target.classList.add('activo');
        });
    });
})();
</script>
@endsection
