@extends('dashboard.layout')

@section('titulo', 'Temas visuales')

@push('styles')
<style>
.temas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.tema-card {
    background: var(--card-bg);
    border: 2px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s;
    cursor: pointer;
    position: relative;
}

.tema-card:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.tema-card.seleccionado {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
}

.tema-card-preview {
    height: 200px;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}

.tema-card-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.tema-card-preview .icono-tema {
    font-size: 3rem;
    opacity: 0.3;
}

.tema-card-body {
    padding: 1rem 1.2rem;
}

.tema-card-body h3 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.3rem;
}

.tema-card-body p {
    font-size: 0.82rem;
    color: var(--text-secondary);
    margin: 0 0 0.8rem;
    line-height: 1.4;
}

.tema-card-body .badge-activo {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: #d1fae5;
    color: #065f46;
    padding: 0.2rem 0.7rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.tema-card-actions {
    padding: 0.8rem 1.2rem;
    border-top: 1px solid var(--border);
    display: flex;
    gap: 0.5rem;
}

.tema-card-actions button,
.tema-card-actions a {
    flex: 1;
    text-align: center;
    padding: 0.5rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-activar {
    background: var(--accent);
    color: #fff;
}

.btn-activar:hover { opacity: 0.9; }

.btn-preview {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.btn-preview:hover { background: var(--border); }

.btn-preview i, .btn-activar i { margin-right: 0.3rem; }
</style>
@endpush

@section('contenido')
<div style="margin-bottom:1.5rem;">
    <h2 style="font-size:1.5rem;font-weight:700;color:var(--text-primary);margin:0;">
        <i class="fas fa-palette" style="margin-right:0.5rem;"></i>Temas visuales
    </h2>
    <p style="color:var(--text-secondary);font-size:0.9rem;margin:0.3rem 0 0;">
        Selecciona el tema que más se adapte a tu estilo profesional.
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="temas-grid">
    @foreach($temas as $tema)
        <div class="tema-card {{ $psicologa->tema_id == $tema->id ? 'seleccionado' : '' }}">
            <div class="tema-card-preview" style="background: linear-gradient(135deg, {{ $tema->color_primario }}22, {{ $tema->color_secundario }}22);">
                @if($tema->preview && file_exists(public_path($tema->preview)))
                    <img src="{{ asset($tema->preview) }}" alt="{{ $tema->nombre }}">
                @else
                    <div style="text-align:center;">
                        <div style="font-size:3rem;color:{{ $tema->color_primario }};">
                            <i class="fas fa-paint-roller"></i>
                        </div>
                        <div style="font-size:0.8rem;color:var(--text-secondary);margin-top:0.5rem;">{{ $tema->nombre }}</div>
                    </div>
                @endif
                @if($psicologa->tema_id == $tema->id)
                    <div style="position:absolute;top:0.5rem;right:0.5rem;background:var(--accent);color:#fff;border-radius:20px;padding:0.2rem 0.7rem;font-size:0.75rem;font-weight:600;">
                        <i class="fas fa-check"></i> Activo
                    </div>
                @endif
            </div>
            <div class="tema-card-body">
                <h3>{{ $tema->nombre }}</h3>
                <p>{{ $tema->descripcion }}</p>
                <div style="display:flex;gap:0.5rem;">
                    <span style="width:20px;height:20px;border-radius:4px;background:{{ $tema->color_primario }};"></span>
                    <span style="width:20px;height:20px;border-radius:4px;background:{{ $tema->color_secundario }};"></span>
                </div>
            </div>
            <div class="tema-card-actions">
                <a href="{{ route('temas.previsualizar', $tema->id) }}" target="_blank" class="btn-preview">
                    <i class="fas fa-eye"></i> Vista previa
                </a>
                @if($psicologa->tema_id != $tema->id)
                    <form action="{{ route('temas.seleccionar') }}" method="POST" style="flex:1;">
                        @csrf
                        <input type="hidden" name="tema_id" value="{{ $tema->id }}">
                        <button type="submit" class="btn-activar">
                            <i class="fas fa-check"></i> Activar
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>

<div style="margin-top:2rem;text-align:center;padding:2rem;background:var(--card-bg);border:1px dashed var(--border);border-radius:12px;">
    <p style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:0.5rem;">
        ¿Quieres un diseño completamente personalizado?
    </p>
    <a href="https://victorroblesweb.es/contacto" target="_blank" rel="noopener noreferrer"
       style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.7rem 1.5rem;background:var(--accent);color:#fff;text-decoration:none;border-radius:8px;font-weight:600;">
        <i class="fas fa-external-link-alt"></i> Pídemelo aquí
    </a>
</div>
@endsection
