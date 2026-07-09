<div class="layout__appointment" id="contacto">
    <div class="appointment__container">
        <h2 class="appointment__title">{{ $frases['cta_texto']->valor ?? '¿Listo para empezar tu camino hacia el bienestar?' }}</h2>
        @php $reservasDisponible = ($config?->mostrar_reservas && app('router')->has('reservas.index')); @endphp
        @if($reservasDisponible)
        <a class="appointment__btn-appointment" href="{{ route('reservas.index') }}">{{ $frases['cta_btn']->valor ?? 'Solicita tu primera cita' }}</a>
        @else
        <a class="appointment__btn-appointment" href="tel:{{ $psicologa->telefono_citas ?? $psicologa->telefono }}">{{ $frases['cta_btn']->valor ?? 'Solicita tu primera cita' }}</a>
        @endif
    </div>
</div>
