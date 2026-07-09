<div class="layout__services" id="services">
    <div class="services__container">
        <div class="services__header">
            <div class="services__title-box">
                <h3 class="services__subtitle">{{ $frases['servicios_titulo']->valor ?? 'Servicios que ofrecemos' }}</h3>
                <h2 class="services__title">{{ $frases['servicios_desc']->valor ?? 'Terapia mágica para las personas que necesitan ayuda' }}</h2>
            </div>
        </div>

        <div class="services__container-services">
            @forelse($servicios as $servicio)
            <div class="services__service">
                <div class="service__container-img">
                    <div class="service__overflow-img">
                        @php $clave = 'servicio_' . $loop->iteration; $imgSrv = \App\Models\ImagenWeb::where('psicologa_id', $psicologa->id)->where('clave', $clave)->first(); @endphp
                        <img src="{{ $imgSrv ? asset('storage/' . $imgSrv->archivo) : asset("themes/{$carpeta}/img/servicio{$loop->iteration}.jpg") }}" alt="{{ $servicio->nombre }}" class="service__img" onerror="this.src='{{ asset("themes/{$carpeta}/img/servicio1.jpg") }}'">
                    </div>
                    <i class="fa-solid {{ $servicio->icono ?? 'fa-heart' }} service__ico"></i>
                </div>
                <div class="service__body">
                    <h3 class="body__title">{{ $servicio->nombre }}</h3>
                    <p class="body__description">{{ Str::limit($servicio->descripcion, 100) }}</p>
                </div>
            </div>
            @empty
            <p style="text-align:center;padding:2rem;color:#666;">Próximamente más información sobre servicios.</p>
            @endforelse
        </div>
    </div>
</div>
