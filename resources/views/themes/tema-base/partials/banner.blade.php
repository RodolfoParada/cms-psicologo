<div class="layout__banner" id="home">
    <div class="banner__content">
        <div class="banner__sandwich">
            <p class="sandwich__text">{{ $psicologa->slogan ?? 'Bienvenido a tu espacio de confianza' }}</p>
        </div>
        <h1 class="banner__title">
            {{ $frases['hero_titulo']->valor ?? 'Tu espacio de confianza para el bienestar emocional' }}
        </h1>

        <div class="banner__appointment">
            @php $reservasDisponible = ($config?->mostrar_reservas && app('router')->has('reservas.index')); @endphp
            @if($reservasDisponible)
            <a class="appointment__btn" href="{{ route('reservas.index') }}">{{ $frases['hero_btn']->valor ?? 'Pide tu cita' }}</a>
            @else
            <a class="appointment__btn" href="tel:{{ $psicologa->telefono_citas ?? $psicologa->telefono }}">{{ $frases['hero_btn']->valor ?? 'Pide tu cita' }}</a>
            @endif
            <p class="appointment__psico-name">{{ $psicologa->nombre_completo }} — {{ $psicologa->numero_colegiado ? 'Nº Col. ' . $psicologa->numero_colegiado : 'Psicóloga' }}</p>
        </div>
    </div>

    <div class="banner__container-img">
        @php $fotoWeb = \App\Models\ImagenWeb::where('psicologa_id', $psicologa->id)->where('clave', 'psicologa')->first(); @endphp
        <img class="banner__img" src="{{ $fotoWeb ? asset('storage/' . $fotoWeb->archivo) : ($psicologa->foto ? asset('storage/' . $psicologa->foto) : asset("themes/{$carpeta}/img/psicologa.png")) }}" alt="{{ $psicologa->nombre_completo }}">
    </div>

    <div class="banner__shapes">
        <div class="shapes__shape1"><img class="shape1__img" src="{{ asset("themes/{$carpeta}/img/shape1.png") }}" alt="Shape"></div>
        <div class="shapes__shape2"><img class="shape2__img" src="{{ asset("themes/{$carpeta}/img/shape2.png") }}" alt="Shape"></div>
    </div>
</div>
