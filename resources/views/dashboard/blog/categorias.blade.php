@extends('dashboard.layout')

@section('titulo', 'Categorías del blog')

@push('styles')
<style>
.cat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.cat-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.cat-grid {
    display: grid;
    gap: 0.8rem;
}

.cat-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 1rem 1.2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cat-info h3 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 0.2rem;
}

.cat-info p {
    font-size: 0.85rem;
    color: var(--text-secondary);
    margin: 0;
}

.cat-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.btn-sm {
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    border: 1px solid var(--border);
    background: var(--card-bg);
    color: var(--text-primary);
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.btn-sm:hover { background: var(--bg-secondary); }
.btn-sm.danger:hover { background: #fee2e2; color:#ef4444; border-color:#ef4444; }
.btn-sm.primary { background: var(--accent); color:#fff; border-color:var(--accent); }

.modal-categoria {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 5000;
    align-items: center;
    justify-content: center;
}

.modal-categoria.visible { display: flex; }

.modal-categoria .modal-content {
    background: var(--card-bg);
    border-radius: 16px;
    width: 90%;
    max-width: 440px;
    padding: 1.5rem;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modal-categoria .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.modal-categoria .modal-header h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.modal-categoria .form-group {
    margin-bottom: 1rem;
}

.modal-categoria .form-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 0.3rem;
    color: var(--text-primary);
}

.modal-categoria .form-group input,
.modal-categoria .form-group textarea {
    width: 100%;
    padding: 0.6rem 0.8rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.9rem;
    background: var(--input-bg);
    color: var(--text-primary);
    box-sizing: border-box;
}

.modal-categoria .modal-footer {
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
<div class="cat-header">
    <h2><i class="fas fa-tags" style="margin-right:0.5rem;"></i>Categorías del blog</h2>
    <button class="btn-sm primary" onclick="abrirModal()">
        <i class="fas fa-plus"></i> Nueva categoría
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success"> {{ session('success') }}</div>
@endif

@if($categorias->count() > 0)
    <div class="cat-grid">
        @foreach($categorias as $cat)
            <div class="cat-card">
                <div class="cat-info">
                    <h3>{{ $cat->nombre }}</h3>
                    <p>{{ $cat->articulos_count }} artículo(s) · Creada {{ $cat->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="cat-actions">
                    <button class="btn-sm" onclick="editarCat({{ $cat->id }}, '{{ $cat->nombre }}', '{{ $cat->descripcion }}')">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <form action="{{ route('blog.categorias.destroy', $cat->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta categoría? Los artículos quedarán sin categoría.');">
                        @csrf @method('DELETE')
                        <button class="btn-sm danger"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div style="text-align:center;padding:4rem 2rem;background:var(--card-bg);border-radius:12px;border:1px solid var(--border);">
        <div style="font-size:3rem;color:var(--text-secondary);margin-bottom:1rem;"><i class="fas fa-tags"></i></div>
        <h3 style="color:var(--text-primary);margin-bottom:0.5rem;">No hay categorías</h3>
        <p style="color:var(--text-secondary);font-size:0.9rem;">Crea tu primera categoría para organizar los artículos.</p>
    </div>
@endif

<div class="modal-categoria" id="modalCat">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalCatTitulo">Nueva categoría</h3>
            <button style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--text-secondary);" onclick="cerrarModal()">&times;</button>
        </div>
        <form id="formCat" method="POST">
            @csrf
            <input type="hidden" name="_method" id="catMethod" value="POST">
            <input type="hidden" name="id" id="catId" value="">
            <div class="form-group">
                <label for="catNombre">Nombre *</label>
                <input type="text" name="nombre" id="catNombre" required>
            </div>
            <div class="form-group">
                <label for="catDescripcion">Descripción</label>
                <textarea name="descripcion" id="catDescripcion" rows="2"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-sm" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-sm primary" id="btnGuardarCat"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModal() {
    document.getElementById('modalCatTitulo').textContent = 'Nueva categoría';
    document.getElementById('catMethod').value = 'POST';
    document.getElementById('catId').value = '';
    document.getElementById('catNombre').value = '';
    document.getElementById('catDescripcion').value = '';
    document.getElementById('formCat').action = '{{ route("blog.categorias.store") }}';
    document.getElementById('btnGuardarCat').innerHTML = '<i class="fas fa-save"></i> Guardar';
    document.getElementById('modalCat').classList.add('visible');
}

function editarCat(id, nombre, descripcion) {
    document.getElementById('modalCatTitulo').textContent = 'Editar categoría';
    document.getElementById('catMethod').value = 'PUT';
    document.getElementById('catId').value = id;
    document.getElementById('catNombre').value = nombre;
    document.getElementById('catDescripcion').value = descripcion;
    document.getElementById('formCat').action = '{{ url("panel-psicologa/blog/categorias") }}/' + id;
    document.getElementById('btnGuardarCat').innerHTML = '<i class="fas fa-save"></i> Actualizar';
    document.getElementById('modalCat').classList.add('visible');
}

function cerrarModal() {
    document.getElementById('modalCat').classList.remove('visible');
}

document.getElementById('modalCat').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
@endsection
