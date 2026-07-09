@extends('dashboard.layout')

@section('titulo', 'Redes sociales')

@push('styles')
<style>
.redes-grid {
    display: grid;
    gap: 1rem;
}
.red-item {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 1rem 1.2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.red-item .icono {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.red-item .info { flex: 1; }
.red-item .info h4 { margin: 0; font-size: 0.95rem; color: var(--text-primary); }
.red-item .info .plataforma-id { font-size: 0.8rem; color: var(--text-secondary); }
.red-item input[type="url"] {
    width: 100%;
    max-width: 350px;
    padding: 0.5rem 0.7rem;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 0.85rem;
    background: var(--input-bg);
    color: var(--text-primary);
}
.red-item .toggle-wrap {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
</style>
@endpush

@section('contenido')
<div style="margin-bottom:1.5rem;">
    <h2 style="font-size:1.5rem;font-weight:700;color:var(--text-primary);margin:0;">
        <i class="fas fa-share-alt" style="margin-right:0.5rem;"></i>Redes sociales
    </h2>
    <p style="color:var(--text-secondary);font-size:0.9rem;margin:0.3rem 0 0;">
        Añade los enlaces a tus redes sociales para que aparezcan en la web pública.
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST">
    @csrf
    <div class="redes-grid">
        @foreach($plataformas as $clave => $info)
            @php $red = $redes[$clave] ?? null; @endphp
            <div class="red-item">
                <div class="icono" style="background:{{ $info['color'] }}">
                    <i class="fab {{ $info['icono'] }}"></i>
                </div>
                <div class="info">
                    <h4>{{ $info['nombre'] }}</h4>
                </div>
                <input type="url" name="url_{{ $clave }}" placeholder="https://..." value="{{ old('url_' . $clave, $red->url ?? '') }}">
                <div class="toggle-wrap">
                    <input type="checkbox" class="toggle" name="activo_{{ $clave }}" id="activo_{{ $clave }}" value="1" {{ ($red && $red->activo) ? 'checked' : '' }}>
                </div>
            </div>
        @endforeach
    </div>
    <div style="margin-top:1.5rem;display:flex;gap:1rem;justify-content:flex-end;">
        <button type="submit" style="padding:0.6rem 1.5rem;border-radius:8px;background:var(--accent);color:#fff;border:none;font-weight:600;cursor:pointer;">
            <i class="fas fa-save"></i> Guardar redes sociales
        </button>
    </div>
</form>
@endsection
