@extends('dashboard.layout')

@section('titulo', 'Gestión de imágenes')

@push('styles')
<style>
.imagenes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.2rem;
}

.imagen-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s;
}

.imagen-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.imagen-card-preview {
    height: 160px;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}

.imagen-card-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.imagen-card-preview .placeholder {
    text-align: center;
    color: var(--text-secondary);
    font-size: 0.8rem;
}

.imagen-card-preview .placeholder i {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    display: block;
    opacity: 0.3;
}

.imagen-card-body {
    padding: 0.8rem 1rem;
}

.imagen-card-body h4 {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.2rem;
}

.imagen-card-body p {
    font-size: 0.78rem;
    color: var(--text-secondary);
    margin: 0 0 0.5rem;
    line-height: 1.3;
}

.imagen-card-body .custom-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.7rem;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    font-weight: 600;
}

.custom-badge.si { background: #d1fae5; color: #065f46; }
.custom-badge.no { background: #f3f4f6; color: #6b7280; }

.imagen-card-actions {
    padding: 0.6rem 1rem;
    border-top: 1px solid var(--border);
    display: flex;
    gap: 0.5rem;
}

.imagen-card-actions form {
    flex: 1;
}

.modal-imagen {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 5000;
    align-items: center;
    justify-content: center;
}

.modal-imagen.visible { display: flex; }

.modal-imagen .modal-content {
    background: var(--card-bg);
    border-radius: 16px;
    width: 90%;
    max-width: 480px;
    padding: 1.5rem;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modal-imagen .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.modal-imagen .modal-header h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.modal-imagen .modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text-secondary);
}

.modal-imagen .form-group {
    margin-bottom: 1rem;
}

.modal-imagen .form-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.3rem;
}

.modal-imagen .form-group input {
    width: 100%;
    padding: 0.6rem 0.8rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.9rem;
    background: var(--input-bg);
    color: var(--text-primary);
    box-sizing: border-box;
}

.modal-imagen .modal-footer {
    display: flex;
    gap: 0.8rem;
    justify-content: flex-end;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border);
}
</style>
@endpush

@section('contenido')
<div style="margin-bottom:1.5rem;">
    <h2 style="font-size:1.5rem;font-weight:700;color:var(--text-primary);margin:0;">
        <i class="fas fa-images" style="margin-right:0.5rem;"></i>Gestión de imágenes
    </h2>
    <p style="color:var(--text-secondary);font-size:0.9rem;margin:0.3rem 0 0;">
        Sube tus propias imágenes para personalizar la web. Si no subes una, se usará la imagen por defecto del tema.
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="imagenes-grid">
    @foreach($slots as $clave => $slot)
        @php $personalizada = isset($imagenes[$clave]); @endphp
        <div class="imagen-card">
            <div class="imagen-card-preview">
                @if($personalizada)
                    <img src="{{ asset('storage/' . $imagenes[$clave]->archivo) }}" alt="{{ $slot['titulo'] }}">
                @elseif($slot['default'])
                    <img src="{{ asset($slot['default']) }}" alt="{{ $slot['titulo'] }}">
                @else
                    <div class="placeholder">
                        <i class="fas fa-image"></i>
                        <span>Sin imagen</span>
                    </div>
                @endif
            </div>
            <div class="imagen-card-body">
                <h4>{{ $slot['titulo'] }}</h4>
                <p>{{ $slot['descripcion'] }}</p>
                <span class="custom-badge {{ $personalizada ? 'si' : 'no' }}">
                    <i class="fas {{ $personalizada ? 'fa-check-circle' : 'fa-circle' }}"></i>
                    {{ $personalizada ? 'Personalizada' : 'Por defecto' }}
                </span>
            </div>
            <div class="imagen-card-actions">
                <button class="btn-accion" style="flex:1;padding:0.4rem;border-radius:6px;background:var(--accent);color:#fff;border:none;font-size:0.78rem;font-weight:600;cursor:pointer;" onclick="abrirSubir('{{ $clave }}', '{{ $slot['titulo'] }}')">
                    <i class="fas fa-upload"></i> Subir
                </button>
                @if($personalizada)
                    <form action="{{ route('imagenes.eliminar', $clave) }}" method="POST" onsubmit="return confirm('¿Restaurar imagen por defecto?')">
                        @csrf @method('DELETE')
                        <button class="btn-accion" style="padding:0.4rem;border-radius:6px;background:#fee2e2;color:#991b1b;border:none;font-size:0.78rem;font-weight:600;cursor:pointer;" title="Restaurar por defecto">
                            <i class="fas fa-undo"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>

<div class="modal-imagen" id="modalSubir">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitulo">Subir imagen</h3>
            <button class="modal-close" onclick="cerrarSubir()">&times;</button>
        </div>
        <form method="POST" action="{{ route('imagenes.subir') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="clave" id="inputClave">
            <div class="form-group">
                <label id="labelSlot"></label>
            </div>
            <div class="form-group">
                <label>Seleccionar imagen *</label>
                <input type="file" name="imagen" accept="image/*" required>
                <small style="color:var(--text-secondary);font-size:0.75rem;">Formatos: JPG, PNG, WebP. Máximo 5MB.</small>
            </div>
            <div class="form-group">
                <label>Título (opcional)</label>
                <input type="text" name="titulo" placeholder="Texto alternativo de la imagen">
            </div>
            <div class="modal-footer">
                <button type="button" style="padding:0.5rem 1rem;border-radius:6px;border:1px solid var(--border);background:var(--card-bg);color:var(--text-primary);cursor:pointer;" onclick="cerrarSubir()">Cancelar</button>
                <button type="submit" style="padding:0.5rem 1rem;border-radius:6px;background:var(--accent);color:#fff;border:none;cursor:pointer;font-weight:600;">
                    <i class="fas fa-upload"></i> Subir imagen
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirSubir(clave, titulo) {
    document.getElementById('inputClave').value = clave;
    document.getElementById('labelSlot').textContent = titulo;
    document.getElementById('modalTitulo').textContent = 'Subir: ' + titulo;
    document.getElementById('modalSubir').classList.add('visible');
}

function cerrarSubir() {
    document.getElementById('modalSubir').classList.remove('visible');
}

document.getElementById('modalSubir')?.addEventListener('click', function(e) {
    if (e.target === this) cerrarSubir();
});
</script>
@endsection
