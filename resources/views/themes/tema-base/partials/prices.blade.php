<div class="layout__prices">
    <div class="prices__container">
        <header class="prices__header">
            <h4 class="prices__subtitle">{{ $frases['precios_titulo']->valor ?? 'Planes y precios' }}</h4>
            <h2 class="prices__title">Selecciona el plan que mejor se adapte a ti</h2>
        </header>

        <div class="prices__list-prices">
            @foreach($preciosOnline as $precio)
            <div class="list-prices__price">
                <div class="price__content">
                    <div class="price__container-ico"><i class="fa-solid fa-globe container-ico__ico"></i></div>
                    <h2 class="price__online">{{ $precio->precio_mensual }}€</h2>
                    <p class="price__text">Online · {{ $precio->nombre }}</p>
                    <ul class="price__list-details">
                        <li class="list-details__item"><i class="fa-solid fa-circle"></i><span>{{ $precio->descripcion ?? 'Sesión online' }}</span></li>
                        <li class="list-details__item"><i class="fa-solid fa-circle"></i><span>Duración: {{ $precio->duracion ?? '50 min' }}</span></li>
                    </ul>
                </div>
                <div class="price__container-pay">
                    <a class="price__btn-pay" href="tel:{{ $psicologa->telefono_citas ?? $psicologa->telefono }}" style="text-decoration:none;display:block;">Solicitar</a>
                </div>
            </div>
            @endforeach

            @foreach($preciosPresencial as $precio)
            <div class="list-prices__price">
                <div class="price__content">
                    <div class="price__container-ico"><i class="fa-solid fa-user container-ico__ico"></i></div>
                    <h2 class="price__person">{{ $precio->precio_mensual }}€</h2>
                    <p class="price__text">Presencial · {{ $precio->nombre }}</p>
                    <ul class="price__list-details">
                        <li class="list-details__item"><i class="fa-solid fa-circle"></i><span>{{ $precio->descripcion ?? 'Sesión presencial' }}</span></li>
                        <li class="list-details__item"><i class="fa-solid fa-circle"></i><span>Duración: {{ $precio->duracion ?? '50 min' }}</span></li>
                    </ul>
                </div>
                <div class="price__container-pay">
                    <a class="price__btn-pay" href="tel:{{ $psicologa->telefono_citas ?? $psicologa->telefono }}" style="text-decoration:none;display:block;">Solicitar</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
