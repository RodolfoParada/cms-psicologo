<footer class="layout__footer">
    <div class="footer__footer-top">
        <div class="footer__container">
            <div class="footer-top__left">
                <div class="footer-top__container-ico"><i class="fa-solid fa-clock"></i></div>
                <div class="footer-top__schedule">
                    <h4 class="schedule__text">Horario de atención:</h4>
                    <p class="schedule__time">{{ $psicologa->horario_atencion ?? 'De lunes a viernes 9:00 - 18:00' }}</p>
                </div>
            </div>

            <div class="footer-top__social">
                <p class="social__label">Sígueme en:</p>
                <ul class="social__list-social">
                    @foreach($redes as $red)
                    @php
                        $iconos = ['instagram' => 'fa-instagram', 'facebook' => 'fa-facebook', 'twitter' => 'fa-twitter', 'linkedin' => 'fa-linkedin', 'youtube' => 'fa-youtube', 'tiktok' => 'fa-tiktok', 'whatsapp' => 'fa-whatsapp', 'telegram' => 'fa-telegram'];
                        $icono = $iconos[$red->plataforma] ?? 'fa-globe';
                    @endphp
                    <li class="list-social__item">
                        <a href="{{ $red->url }}" class="list-social__link" target="_blank">
                            <i class="fa-brands {{ $icono }} list-social__ico"></i>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="footer__footer-middle">
        <div class="footer__container">
            <div class="footer-middle__container">
                <div class="footer-middle__logo">
                    @php $logoUrl = \App\Models\ImagenWeb::where('psicologa_id', $psicologa->id)->where('clave', 'logo')->first(); @endphp
                    <img class="logo__img" src="{{ $logoUrl ? asset('storage/' . $logoUrl->archivo) : asset("themes/{$carpeta}/img/logo.png") }}" alt="Logo" style="max-width:60px;">
                    <div class="logo__container-text">
                        <h1 class="logo___title">{{ $psicologa->nombre_completo }}</h1>
                        <p class="logo___subtitle">Psicología y asesoramiento</p>
                    </div>
                </div>
                <p class="footer-middle__description">
                    {{ $psicologa->slogan ?? 'La mejor psicología y asesoramiento para ayudarte.' }}
                </p>
            </div>

            <div class="footer-middle__container">
                <h3 class="footer-middle__title">Explorar</h3>
                <ul class="footer-middle__list-explore">
                    @if($config?->modo_visualizacion === 'multipagina')
                    <li class="list-explore__item"><a href="{{ route('web.sobre-mi') }}" class="list-explore__link">Sobre mí</a></li>
                    <li class="list-explore__item"><a href="{{ route('web.servicios') }}" class="list-explore__link">Servicios</a></li>
                    @if($reservasLink ?? ($config?->mostrar_reservas && app('router')->has('reservas.index')))
                    <li class="list-explore__item"><a href="{{ route('reservas.index') }}" class="list-explore__link">Reservar cita</a></li>
                    @endif
                    <li class="list-explore__item"><a href="{{ route('web.blog') }}" class="list-explore__link">Blog</a></li>
                    <li class="list-explore__item"><a href="{{ route('web.contacto') }}" class="list-explore__link">Contacto</a></li>
                    @else
                    <li class="list-explore__item"><a href="#about" class="list-explore__link">Sobre mí</a></li>
                    <li class="list-explore__item"><a href="#services" class="list-explore__link">Servicios</a></li>
                    @if($reservasLink ?? ($config?->mostrar_reservas && app('router')->has('reservas.index')))
                    <li class="list-explore__item"><a href="#contacto" class="list-explore__link">Reservar cita</a></li>
                    @endif
                    <li class="list-explore__item"><a href="#blog" class="list-explore__link">Blog</a></li>
                    <li class="list-explore__item"><a href="#contacto" class="list-explore__link">Contacto</a></li>
                    @endif
                </ul>
            </div>

            <div class="footer-middle__container">
                <h3 class="footer-middle__title">Contacto</h3>
                <ul class="footer-middle__list-contact">
                    @if($psicologa->direccion)
                    <li class="list-contact__item">
                        <div class="list-contact__container-ico"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="list-contact__content">
                            <p class="list-contact__label">Dirección</p>
                            <h4 class="list-contact__data">{{ $psicologa->direccion }}{{ $psicologa->ciudad ? ', ' . $psicologa->ciudad : '' }}</h4>
                        </div>
                    </li>
                    @endif
                    @if($psicologa->email_citas)
                    <li class="list-contact__item">
                        <div class="list-contact__container-ico"><i class="fa-solid fa-envelope"></i></div>
                        <div class="list-contact__content">
                            <p class="list-contact__label">Email</p>
                            <h4 class="list-contact__data">{{ $psicologa->email_citas }}</h4>
                        </div>
                    </li>
                    @endif
                    <li class="list-contact__item">
                        <div class="list-contact__container-ico"><i class="fa-solid fa-phone"></i></div>
                        <div class="list-contact__content">
                            <p class="list-contact__label">Teléfono</p>
                            <h4 class="list-contact__data">{{ $psicologa->telefono_citas ?? $psicologa->telefono }}</h4>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="footer__footer-bottom">
        <div class="footer__container">
            <p>&copy; {{ date('Y') }} {{ $psicologa->nombre_completo }}. {{ $frases['footer_texto']->valor ?? 'Todos los derechos reservados.' }}</p>
            <p style="font-size:0.85rem;opacity:0.7;">{{ $psicologa->numero_colegiado ? 'Nº Colegiada: ' . $psicologa->numero_colegiado : '' }}</p>
        </div>
    </div>
</footer>

{{-- Scroll to top --}}
<div class="scroll-top" id="scrollTop">
    <i class="fa-solid fa-arrow-up"></i>
</div>
