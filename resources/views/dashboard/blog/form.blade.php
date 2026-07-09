@extends('dashboard.layout')

@section('titulo', isset($articulo) ? 'Editar artículo' : 'Nuevo artículo')

@push('styles')
<link rel="stylesheet" href="{{ asset('dashboard/js/jodit/jodit.min.css') }}">
<style>
.blog-form {
    max-width: 900px;
}

.blog-form .form-group {
    margin-bottom: 1.2rem;
}

.blog-form .form-group label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.4rem;
}

.blog-form .form-group input[type="text"],
.blog-form .form-group select,
.blog-form .form-group textarea {
    width: 100%;
    padding: 0.65rem 0.9rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.9rem;
    color: var(--text-primary);
    background: var(--input-bg);
    box-sizing: border-box;
}

.blog-form .form-group input:focus,
.blog-form .form-group select:focus,
.blog-form .form-group textarea:focus {
    outline: none;
    border-color: var(--accent);
}

.blog-form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.blog-form .editor-wrapper {
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
}

.blog-form .image-preview {
    width: 100%;
    max-height: 200px;
    object-fit: cover;
    border-radius: 8px;
    margin-top: 0.5rem;
}

.blog-form .form-actions {
    display: flex;
    gap: 0.8rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
}

.btn-back {
    padding: 0.65rem 1.3rem;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    color: var(--text-primary);
    background: var(--bg-secondary);
    border: 1px solid var(--border);
}

.btn-back:hover {
    background: var(--border);
}

.btn-submit {
    padding: 0.65rem 1.5rem;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    background: var(--accent);
    color: #fff;
    border: none;
    cursor: pointer;
}

.btn-submit:hover {
    opacity: 0.9;
}

@media (max-width: 768px) {
    .blog-form .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('contenido')
<div class="blog-form" style="background:var(--card-bg);border-radius:12px;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
    <h2 style="font-size:1.4rem;font-weight:700;color:var(--text-primary);margin:0 0 1.5rem;">
        <i class="fas {{ isset($articulo) ? 'fa-edit' : 'fa-plus' }}" style="margin-right:0.5rem;"></i>
        {{ isset($articulo) ? 'Editar artículo' : 'Nuevo artículo' }}
    </h2>

    <form action="{{ isset($articulo) ? route('blog.actualizar', $articulo->id) : route('blog.guardar') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($articulo)) @method('PUT') @endif

        <div class="form-group">
            <label for="titulo">Título del artículo *</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $articulo->titulo ?? '') }}" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select name="categoria_id" id="categoria_id">
                    <option value="">Sin categoría</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ (old('categoria_id', $articulo->categoria_id ?? '') == $cat->id) ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="publicado">Estado</label>
                <select name="publicado" id="publicado">
                    <option value="0" {{ old('publicado', isset($articulo) ? ($articulo->publicado ? 1 : 0) : 0) == 0 ? 'selected' : '' }}>Borrador</option>
                    <option value="1" {{ old('publicado', isset($articulo) ? ($articulo->publicado ? 1 : 0) : 0) == 1 ? 'selected' : '' }}>Publicado</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="extracto">Extracto (breve descripción para la lista)</label>
            <textarea name="extracto" id="extracto" rows="2" maxlength="300">{{ old('extracto', $articulo->extracto ?? '') }}</textarea>
        </div>

        <div class="form-group">
            <label for="contenido">Contenido *</label>
            <div class="editor-wrapper">
                <textarea name="contenido" id="contenido" style="display:none;">{{ old('contenido', $articulo->contenido ?? '') }}</textarea>
                <div id="editor" style="min-height:400px;">{!! old('contenido', $articulo->contenido ?? '') !!}</div>
            </div>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen destacada</label>
            <input type="file" name="imagen" id="imagen" accept="image/jpeg,image/png,image/webp">
            @if(isset($articulo) && $articulo->imagen)
                <img src="{{ asset('storage/' . $articulo->imagen) }}" alt="Imagen actual" class="image-preview">
                <p style="font-size:0.8rem;color:var(--text-secondary);margin-top:0.3rem;">Imagen actual. Sube una nueva para reemplazarla.</p>
            @endif
        </div>

        <div class="form-actions">
            <a href="{{ route('blog.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> {{ isset($articulo) ? 'Actualizar artículo' : 'Guardar artículo' }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/js/jodit/jodit.min.js') }}"></script>
<script>
const editor = Jodit.make('#editor', {
    height: 450,
    language: 'es',
    uploader: { insertImageAsBase64URI: true },
    toolbarAdaptive: false,
    buttons: [
        'source', '|',
        'bold', 'italic', 'underline', 'strikethrough', '|',
        'ul', 'ol', '|',
        'outdent', 'indent', '|',
        'font', 'fontsize', 'brush', 'paragraph', '|',
        'image', 'link', '|',
        'align', 'undo', 'redo', '|',
        'hr', 'eraser', 'fullsize',
    ],
});

document.querySelector('form').addEventListener('submit', function () {
    document.getElementById('contenido').value = editor.value;
});
</script>
@endpush
