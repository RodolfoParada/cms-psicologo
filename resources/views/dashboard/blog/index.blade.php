@extends('dashboard.layout')

@section('titulo', 'Blog')

@push('styles')
<style>
.blog-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.blog-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.blog-grid {
    display: grid;
    gap: 1rem;
}

.blog-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.2rem;
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    transition: box-shadow 0.2s;
}

.blog-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.blog-card-img {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
    background: var(--bg-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    font-size: 1.5rem;
}

.blog-card-body {
    flex: 1;
    min-width: 0;
}

.blog-card-body h3 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 0.3rem;
}

.blog-card-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin-bottom: 0.4rem;
    flex-wrap: wrap;
}

.blog-card-meta span {
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.blog-card-extracto {
    font-size: 0.85rem;
    color: var(--text-secondary);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-card-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

.badge-publicado {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-publicado.si {
    background: #d1fae5;
    color: #065f46;
}

.badge-publicado.no {
    background: #fee2e2;
    color: #991b1b;
}

.btn-icono {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--card-bg);
    color: var(--text-secondary);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-icono:hover {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.btn-icono.danger:hover {
    background: #fee2e2;
    color: #ef4444;
    border-color: #ef4444;
}

@media (max-width: 768px) {
    .blog-card {
        flex-direction: column;
    }
    .blog-card-img {
        width: 100%;
        height: 140px;
    }
    .blog-card-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>
@endpush

@section('contenido')
<div class="blog-header">
    <h2><i class="fas fa-blog" style="margin-right:0.5rem;"></i>Blog</h2>
    <div style="display:flex;gap:0.5rem;">
        <a href="{{ route('blog.categorias') }}" class="btn-accion" style="background:var(--bg-secondary);color:var(--text-primary);text-decoration:none;padding:0.6rem 1.2rem;border-radius:8px;font-size:0.9rem;font-weight:600;">
            <i class="fas fa-tags"></i> Categorías
        </a>
        <a href="{{ route('blog.crear') }}" class="btn-accion" style="background:var(--accent);color:#fff;text-decoration:none;padding:0.6rem 1.2rem;border-radius:8px;font-size:0.9rem;font-weight:600;">
            <i class="fas fa-plus"></i> Nuevo artículo
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success"> {{ session('success') }}</div>
@endif

@if($articulos->count() > 0)
    <div class="blog-grid">
        @foreach($articulos as $articulo)
            <div class="blog-card">
                <div class="blog-card-img">
                    @if($articulo->imagen)
                        <img src="{{ asset('storage/' . $articulo->imagen) }}" alt="{{ $articulo->titulo }}" style="width:100%;height:100%;border-radius:8px;object-fit:cover;">
                    @else
                        <i class="fas fa-image"></i>
                    @endif
                </div>
                <div class="blog-card-body">
                    <h3>{{ $articulo->titulo }}</h3>
                    <div class="blog-card-meta">
                        <span><i class="far fa-calendar"></i> {{ $articulo->created_at->format('d/m/Y') }}</span>
                        @if($articulo->categoria)
                            <span><i class="fas fa-tag"></i> {{ $articulo->categoria->nombre }}</span>
                        @endif
                        <span class="badge-publicado {{ $articulo->publicado ? 'si' : 'no' }}">
                            <i class="fas {{ $articulo->publicado ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $articulo->publicado ? 'Publicado' : 'Borrador' }}
                        </span>
                    </div>
                    @if($articulo->extracto)
                        <div class="blog-card-extracto">{{ $articulo->extracto }}</div>
                    @endif
                </div>
                <div class="blog-card-actions">
                    <form action="{{ route('blog.toggle', $articulo->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn-icono" title="{{ $articulo->publicado ? 'Despublicar' : 'Publicar' }}">
                            <i class="fas {{ $articulo->publicado ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                        </button>
                    </form>
                    <a href="{{ route('blog.editar', $articulo->id) }}" class="btn-icono" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('blog.eliminar', $articulo->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este artículo?')">
                        @csrf @method('DELETE')
                        <button class="btn-icono danger" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top:2rem;">
        {{ $articulos->links() }}
    </div>
@else
    <div style="text-align:center;padding:4rem 2rem;background:var(--card-bg);border-radius:12px;border:1px solid var(--border);">
        <div style="font-size:3rem;color:var(--text-secondary);margin-bottom:1rem;">
            <i class="fas fa-blog"></i>
        </div>
        <h3 style="color:var(--text-primary);margin-bottom:0.5rem;">No hay artículos todavía</h3>
        <p style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:1.5rem;">
            Crea tu primer artículo para empezar a compartir contenido con tus pacientes.
        </p>
        <a href="{{ route('blog.crear') }}" style="background:var(--accent);color:#fff;text-decoration:none;padding:0.7rem 1.5rem;border-radius:8px;font-weight:600;display:inline-flex;align-items:center;gap:0.5rem;">
            <i class="fas fa-plus"></i> Crear primer artículo
        </a>
    </div>
@endif
@endsection
