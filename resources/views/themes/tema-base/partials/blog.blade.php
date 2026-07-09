<div class="layout__blog" id="blog">
    <div class="blog__container">
        <header class="blog__header">
            <h4 class="blog__subtitle">{{ $frases['blog_titulo']->valor ?? 'Últimas publicaciones' }}</h4>
            <h2 class="blog__title">Artículos del blog</h2>
        </header>

        <div class="blog__articles">
            @forelse($articulos as $articulo)
            <article class="articles__article">
                <div class="article__container-img">
                    <div class="article__container-date">
                        <p class="article__date">{{ \Carbon\Carbon::parse($articulo->created_at)->format('d') }}<br>{{ \Carbon\Carbon::parse($articulo->created_at)->locale('es')->isoFormat('MMM') }}</p>
                    </div>
                    @if($articulo->imagen)
                        <img class="article__img" src="{{ asset('storage/' . $articulo->imagen) }}" alt="{{ $articulo->titulo }}">
                    @elseif($articulo->imagen_url)
                        <img class="article__img" src="{{ $articulo->imagen_url }}" alt="{{ $articulo->titulo }}">
                    @else
                        @php $imgBlog = \App\Models\ImagenWeb::where('psicologa_id', $psicologa->id)->where('clave', 'blog_1')->first(); @endphp
                        <img class="article__img" src="{{ $imgBlog ? asset('storage/' . $imgBlog->archivo) : asset("themes/{$carpeta}/img/blog1.jpg") }}" alt="{{ $articulo->titulo }}">
                    @endif
                    <h2 class="article__title">{{ $articulo->titulo }}</h2>
                </div>
                <div class="article__bottom-content">
                    <div class="article__comments">
                        <i class="fa-solid fa-comment comments__ico"></i>
                        <span class="comments__text">{{ $articulo->categoria->nombre ?? 'Blog' }}</span>
                    </div>
                    <div class="article__container-btn">
                        @if($config?->modo_visualizacion === 'multipagina')
                        <a href="{{ route('web.blog.articulo', $articulo->slug) }}" class="article__btn-more" style="text-decoration:none;display:flex;align-items:center;gap:0.3rem;">
                            <i class="fa-solid fa-arrow-right btn-more__arrow"></i>
                            <span class="btn-more__text">Ver más</span>
                        </a>
                        @else
                        <span class="article__btn-more" style="cursor:default;opacity:0.6;">
                            <i class="fa-solid fa-arrow-right btn-more__arrow"></i>
                            <span class="btn-more__text">Ver más</span>
                        </span>
                        @endif
                    </div>
                </div>
            </article>
            @empty
            <p style="text-align:center;padding:2rem;color:#666;grid-column:1/-1;">Próximamente nuevos artículos.</p>
            @endforelse
        </div>
    </div>
</div>
