<nav class="layout__nav">
    <div class="nav__left">
        <header class="nav__header">
            <div class="header__container-img">
                @php $logoUrl = \App\Models\ImagenWeb::where('psicologa_id', $psicologa->id)->where('clave', 'logo')->first(); @endphp
                <img class="header__img" src="{{ $logoUrl ? asset('storage/' . $logoUrl->archivo) : asset("themes/{$carpeta}/img/logo.png") }}" alt="Logo">
            </div>
            <div class="header__content">
                <h1 class="header__title">{{ $psicologa->nombre_completo }}</h1>
                <h2 class="header__subtitle">Psicología y asesoramiento</h2>
            </div>
        </header>

        @php $reservasLink = ($config?->mostrar_reservas && app('router')->has('reservas.index')) ? true : false; @endphp

        @if($config?->modo_visualizacion === 'multipagina')
        <ul class="nav__list-nav">
            <li class="list-nav__item"><a href="{{ route('web.index') }}" class="list-nav__link">Inicio</a></li>
            <li class="list-nav__item"><a href="{{ route('web.sobre-mi') }}" class="list-nav__link">Sobre mí</a></li>
            <li class="list-nav__item"><a href="{{ route('web.servicios') }}" class="list-nav__link">Servicios</a></li>
            @if($reservasLink)
            <li class="list-nav__item"><a href="{{ route('reservas.index') }}" class="list-nav__link">Reservar cita</a></li>
            @endif
            <li class="list-nav__item"><a href="{{ route('web.blog') }}" class="list-nav__link">Blog</a></li>
            <li class="list-nav__item"><a href="{{ route('web.faq') }}" class="list-nav__link">FAQ</a></li>
            <li class="list-nav__item"><a href="{{ route('web.contacto') }}" class="list-nav__link">Contacto</a></li>
        </ul>
        @else
        <ul class="nav__list-nav">
            <li class="list-nav__item"><a href="#home" class="list-nav__link">Home</a></li>
            <li class="list-nav__item"><a href="#about" class="list-nav__link">Sobre mí</a></li>
            <li class="list-nav__item"><a href="#services" class="list-nav__link">Servicios</a></li>
            @if($reservasLink)
            <li class="list-nav__item"><a href="#contacto" class="list-nav__link">Reservar cita</a></li>
            @endif
            <li class="list-nav__item"><a href="#blog" class="list-nav__link">Blog</a></li>
            <li class="list-nav__item"><a href="#contacto" class="list-nav__link">Contacto</a></li>
        </ul>
        @endif
    </div>

    <div class="nav__btn-menu-mobile">
        <i class="fa-solid fa-bars"></i>
    </div>

    <div class="nav__contact">
        @if($psicologa->telefono_citas)
        <a class="contact__container-ico" href="tel:{{ $psicologa->telefono_citas }}" title="Teléfono">
            <i class="fa-solid fa-phone-volume contact__phone-ico"></i>
        </a>
        @endif
        <div class="contact__phone">
            <p class="phone__title">¿Tienes alguna pregunta?</p>
            <h4 class="phone__number">{{ $psicologa->telefono_citas ?? $psicologa->telefono }}</h4>
        </div>
        <div class="contact__extra">
            @php $whatsapp = $redes->firstWhere('plataforma', 'whatsapp'); @endphp
            @if($whatsapp?->url)
            <a class="contact__container-ico" href="{{ $whatsapp->url }}" target="_blank" title="WhatsApp">
                <i class="fa-brands fa-whatsapp contact__whatsapp-ico"></i>
            </a>
            @endif
            @if($psicologa->email_citas)
            <a class="contact__container-ico" href="mailto:{{ $psicologa->email_citas }}" title="Correo electrónico">
                <i class="fa-regular fa-envelope contact__email-ico"></i>
            </a>
            @endif
        </div>
    </div>
</nav>

<nav class="layout__nav-mobile">
    <div class="nav-mobile__left">
        <header class="nav-mobile__header-mobile">
            <header class="header-mobile__left">
                <div class="header-mobile__container-img">
                    <img class="header-mobile__img" src="{{ asset("themes/{$carpeta}/img/logo.png") }}" alt="Logo">
                </div>
                <div class="header-mobile__content">
                    <h1 class="header-mobile__title">{{ $psicologa->nombre_completo }}</h1>
                    <h2 class="header-mobile__subtitle">Psicología y asesoramiento</h2>
                </div>
            </header>
            <button class="header__btn-close"><i class="fa-solid fa-xmark"></i></button>
        </header>

        @if($config?->modo_visualizacion === 'multipagina')
        <ul class="nav-mobile__list-mobile">
            <li class="list-mobile__item"><a href="{{ route('web.index') }}" class="list-mobile__link">Inicio</a></li>
            <li class="list-mobile__item"><a href="{{ route('web.sobre-mi') }}" class="list-mobile__link">Sobre mí</a></li>
            <li class="list-mobile__item"><a href="{{ route('web.servicios') }}" class="list-mobile__link">Servicios</a></li>
            @if($reservasLink)
            <li class="list-mobile__item"><a href="{{ route('reservas.index') }}" class="list-mobile__link">Reservar cita</a></li>
            @endif
            <li class="list-mobile__item"><a href="{{ route('web.blog') }}" class="list-mobile__link">Blog</a></li>
            <li class="list-mobile__item"><a href="{{ route('web.faq') }}" class="list-mobile__link">FAQ</a></li>
            <li class="list-mobile__item"><a href="{{ route('web.contacto') }}" class="list-mobile__link">Contacto</a></li>
        </ul>
        @else
        <ul class="nav-mobile__list-mobile">
            <li class="list-mobile__item"><a href="#home" class="list-mobile__link">Home</a></li>
            <li class="list-mobile__item"><a href="#about" class="list-mobile__link">Sobre mí</a></li>
            <li class="list-mobile__item"><a href="#services" class="list-mobile__link">Servicios</a></li>
            @if($reservasLink)
            <li class="list-mobile__item"><a href="#contacto" class="list-mobile__link">Reservar cita</a></li>
            @endif
            <li class="list-mobile__item"><a href="#blog" class="list-mobile__link">Blog</a></li>
            <li class="list-mobile__item"><a href="#contacto" class="list-mobile__link">Contacto</a></li>
        </ul>
        @endif
    </div>

    <div class="nav-mobile__contact-mobile">
        @if($psicologa->email_citas)
        <div class="contact-mobile__container">
            <i class="fa-solid fa-envelope"></i>
            <h4 class="contact-mobile__email">{{ $psicologa->email_citas }}</h4>
        </div>
        @endif
        <div class="contact-mobile__container">
            <i class="fa-solid fa-phone"></i>
            <h4 class="contact-mobile__phone">{{ $psicologa->telefono_citas ?? $psicologa->telefono }}</h4>
        </div>
    </div>

    <ul class="nav-mobile__list-m-social">
        @foreach($redes as $red)
        <li class="list-m-social__item">
            <a href="{{ $red->url }}" class="list-m-social__link" target="_blank">
                @php
                    $iconos = ['instagram' => 'fa-instagram', 'facebook' => 'fa-facebook', 'twitter' => 'fa-twitter', 'linkedin' => 'fa-linkedin', 'youtube' => 'fa-youtube', 'tiktok' => 'fa-tiktok', 'whatsapp' => 'fa-whatsapp', 'telegram' => 'fa-telegram'];
                    $icono = $iconos[$red->plataforma] ?? 'fa-globe';
                @endphp
                <i class="fa-brands {{ $icono }} list-m-social__ico"></i>
            </a>
        </li>
        @endforeach
    </ul>
</nav>
