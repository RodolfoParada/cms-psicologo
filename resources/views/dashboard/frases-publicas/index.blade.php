@extends('dashboard.layout')

@section('titulo', 'Frases públicas')

@push('styles')
<style>
.frases-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
@media (max-width: 768px) { .frases-grid { grid-template-columns: 1fr; } }
.frase-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 1rem;
}
.frase-card label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.3rem;
}
.frase-card input, .frase-card textarea {
    width: 100%;
    padding: 0.5rem 0.7rem;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 0.9rem;
    background: var(--input-bg);
    color: var(--text-primary);
    box-sizing: border-box;
}
.frase-card textarea { min-height: 60px; resize: vertical; }
.frase-card .defecto {
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-top: 0.3rem;
}
</style>
@endpush

@section('contenido')
<div style="margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;">
    <div>
        <h2 style="font-size:1.5rem;font-weight:700;color:var(--text-primary);margin:0;">
            <i class="fas fa-quote-left" style="margin-right:0.5rem;"></i>Frases públicas
        </h2>
        <p style="color:var(--text-secondary);font-size:0.9rem;margin:0.3rem 0 0;">
            Personaliza todos los textos que aparecen en tu web pública.
        </p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST">
    @csrf
    <div class="frases-grid">
        @foreach($slots as $clave => $slot)
            @php $valor = $frases[$clave]->valor ?? $slot['defecto']; @endphp
            <div class="frase-card">
                <label for="{{ $clave }}">{{ $slot['titulo'] }}</label>
                <input type="text" name="{{ $clave }}" id="{{ $clave }}" value="{{ old($clave, $valor) }}">
                <div class="defecto">Por defecto: "{{ $slot['defecto'] }}"</div>
            </div>
        @endforeach
    </div>
    <div style="margin-top:1.5rem;display:flex;gap:1rem;justify-content:flex-end;">
        <button type="submit" style="padding:0.6rem 1.5rem;border-radius:8px;background:var(--accent);color:#fff;border:none;font-weight:600;cursor:pointer;">
            <i class="fas fa-save"></i> Guardar frases
        </button>
    </div>
</form>
@endsection
