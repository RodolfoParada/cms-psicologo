<div class="layout__therapies">
    <div class="therapies__container">
        @forelse($servicios->take(3) as $servicio)
        <div class="therapies__therapy">
            <div class="therapy__container-ico">
                <i class="fa-solid {{ $servicio->icono ?? 'fa-heart' }} therapy__ico-person"></i>
            </div>
            <div class="therapy__content">
                <p class="therapy__title">{{ $servicio->nombre }}</p>
                <p class="therapy__description">{{ Str::limit($servicio->descripcion, 80) }}</p>
            </div>
        </div>
        @empty
        <div class="therapies__therapy">
            <div class="therapy__container-ico"><i class="fa-solid fa-person therapy__ico-person"></i></div>
            <div class="therapy__content">
                <p class="therapy__title">Terapia individual</p>
                <p class="therapy__description">Espacio personalizado para trabajar tus objetivos terapéuticos.</p>
            </div>
        </div>
        <div class="therapies__therapy therapy-2">
            <div class="therapy__container-ico"><i class="fa-solid fa-children therapy__ico-person"></i></div>
            <div class="therapy__content">
                <p class="therapy__title">Terapia online</p>
                <p class="therapy__description">Sesiones desde la comodidad de tu hogar con total privacidad.</p>
            </div>
        </div>
        <div class="therapies__therapy therapy-3">
            <div class="therapy__container-ico"><i class="fa-solid fa-people-group therapy__ico-person"></i></div>
            <div class="therapy__content">
                <p class="therapy__title">Bienestar emocional</p>
                <p class="therapy__description">Herramientas para gestionar tus emociones y sentirte mejor.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
