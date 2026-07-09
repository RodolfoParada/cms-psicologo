<div class="layout__couple-issues">
    <div class="couple-issues__container">
        <div class="couple-issues__list-issues">
            @foreach($especialidades as $e)
            <div class="list-issues__c-issue">
                <div class="c-issue__container-img">
                    <img class="c-issue__img" src="{{ asset("themes/{$carpeta}/img/psicologia.jpg") }}" alt="{{ $e->nombre }}">
                </div>
                <div class="c-issue__content">
                    <div class="c-issue__head">
                        <h3 class="c-issue__subtitle">{{ $e->nombre }}</h3>
                        <h2 class="c-issue__title">{{ Str::limit($e->descripcion, 60) }}</h2>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
