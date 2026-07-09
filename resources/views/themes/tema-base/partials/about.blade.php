<div class="layout__characteristics" id="about">
    <div class="characteristics__container">
        <div class="characteristics__details">
            <h3 class="details__subtitle">{{ $frases['sobre_mi_titulo']->valor ?? 'Sobre mí' }}</h3>
            <h2 class="details__title">{{ $psicologa->slogan ?? 'Brindando terapias psicológicas de la mejor calidad.' }}</h2>
            <p class="details__description">{{ $psicologa->sobre_mi ?? 'Aquí aparecerá tu información personal.' }}</p>

            @if($especialidades->count() > 0)
            <ul class="details__list-services">
                @foreach($especialidades as $e)
                <li class="list-services__item">
                    <i class="fa-solid fa-check list-services__ico"></i>
                    <p class="list-services__text">{{ $e->nombre }}</p>
                </li>
                @endforeach
            </ul>
            @endif

            <a class="details__btn-appointment" href="tel:{{ $psicologa->telefono_citas ?? $psicologa->telefono }}">Pedir una cita</a>
        </div>

        <div class="characteristics__right">
            <div class="characteristics__container-img">
                @php $imgAbout = \App\Models\ImagenWeb::where('psicologa_id', $psicologa->id)->where('clave', 'sobre_mi')->first(); @endphp
                <img class="characteristics__img" src="{{ $imgAbout ? asset('storage/' . $imgAbout->archivo) : asset("themes/{$carpeta}/img/why.jpg") }}" alt="Sobre mí">
            </div>
        </div>
    </div>
</div>
