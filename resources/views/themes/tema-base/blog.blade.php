@extends("themes.{$carpeta}.layout")

@push('styles')
<link rel="stylesheet" href="{{ asset("themes/{$carpeta}/css/subpage.css") }}">
<style>
.blog-filter { margin-bottom: 2rem; display: flex; gap: 0.5rem; flex-wrap: wrap; justify-content: center; }
.blog-filter a { padding: 0.5rem 1.2rem; border: 1px solid var(--color-gray4); border-radius: 50px; text-decoration: none; color: var(--color-gray2); font-size: 0.9rem; transition: 0.2s; }
.blog-filter a:hover, .blog-filter a.active { background: var(--color-primary); color: #fff; border-color: var(--color-primary); }
</style>
@endpush

@section('content')
<div class="subpage">
    <div class="subpage__container">
        <div class="subpage__header">
            <h2>Blog</h2>
        </div>

        <div class="blog-filter">
            <a href="{{ route('web.blog') }}" class="{{ !request('categoria') ? 'active' : '' }}">Todas</a>
            @foreach($categorias as $cat)
                <a href="{{ route('web.blog', ['categoria' => $cat->slug]) }}" class="{{ request('categoria') === $cat->slug ? 'active' : '' }}">{{ $cat->nombre }}</a>
            @endforeach
        </div>

        <div class="subpage__articles">
            @forelse($articulos as $a)
            <article class="subpage__article">
                <div class="subpage__article-img">
                    @if($a->imagen)
                        <img src="{{ asset('storage/' . $a->imagen) }}" alt="{{ $a->titulo }}">
                    @else
                        <img src="{{ asset("themes/{$carpeta}/img/blog1.jpg") }}" alt="{{ $a->titulo }}">
                    @endif
                </div>
                <div class="subpage__article-info">
                    <h3><a href="{{ route('web.blog.articulo', $a->slug) }}">{{ $a->titulo }}</a></h3>
                    <p class="subpage__article-meta">{{ $a->created_at->format('d/m/Y') }} · {{ $a->categoria->nombre ?? 'Blog' }}</p>
                    <p>{{ Str::limit(strip_tags($a->contenido), 200) }}</p>
                    <a href="{{ route('web.blog.articulo', $a->slug) }}" class="subpage__article-link">Leer más →</a>
                </div>
            </article>
            @empty
            <p style="text-align:center;grid-column:1/-1;padding:2rem;color:var(--color-gray2);">No hay artículos publicados en esta categoría.</p>
            @endforelse
        </div>

        <div style="margin-top:2rem;">
            {{ $articulos->links() }}
        </div>
    </div>
</div>
@endsection
