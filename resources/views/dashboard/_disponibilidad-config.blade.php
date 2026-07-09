<div class="card" style="margin-bottom:1.6rem;">
    <div class="card-header">
        <h2>{{ $titulo }}</h2>
    </div>
    <div class="card-body">
        {{-- Duración y descanso --}}
        <form method="POST" action="{{ route('disponibilidad.descanso.guardar') }}" style="margin-bottom:2rem; padding-bottom:2rem; border-bottom:0.1rem solid var(--color-border);">
            @csrf
            <input type="hidden" name="tipo" value="{{ $tipo }}">

            <div class="form-row">
                <div class="form-group">
                    <label>Duración de la sesión (minutos)</label>
                    <input type="number" name="duracion_sesion" class="form-control" value="{{ $descanso->duracion_sesion }}" min="15" max="240" required>
                </div>
                <div class="form-group">
                    <label>Descanso entre sesiones</label>
                    <div style="display:flex; gap:0.8rem; align-items:center; flex-wrap:wrap;">
                        <label class="toggle">
                            <input type="checkbox" name="descanso_activo" value="1" {{ $descanso->descanso_activo ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                        <span style="font-size:1.3rem;">Activar descanso</span>
                    </div>
                </div>
            </div>
            <div class="form-row" style="align-items:end;">
                <div class="form-group">
                    <label>Minutos de descanso</label>
                    <input type="number" name="descanso_minutos" class="form-control" value="{{ $descanso->descanso_minutos }}" min="0" max="60">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar configuración</button>
                </div>
            </div>
        </form>

        {{-- Horario semanal --}}
        <h3 style="font-size:1.5rem; margin:0 0 0.5rem;">Horario semanal</h3>
        <p style="font-size:1.3rem; color:var(--color-text-light); margin:0 0 1rem;">
            Haz clic en "+" para añadir un bloque horario. Haz clic en un bloque para eliminarlo.
            @php
                $slotMinutos = $descanso->duracion_sesion + ($descanso->descanso_activo ? $descanso->descanso_minutos : 0);
            @endphp
            <br><strong>Cada bloque disponible dura {{ $slotMinutos }} minutos</strong>
            ({{ $descanso->duracion_sesion }}min sesión
            @if($descanso->descanso_activo)
                + {{ $descanso->descanso_minutos }}min descanso
            @endif).
        </p>

        <div class="horario-grid">
            @foreach ($diasSemana as $i => $diaNombre)
                <div class="horario-dia">
                    <h4>{{ $diaNombre }}</h4>
                    @php
                        $slots = $disponibilidad->get($i) ?? collect();
                    @endphp
                    @foreach ($slots as $slot)
                        <div class="slot" data-tipo="{{ $tipo }}" data-dia="{{ $i }}"
                             data-inicio="{{ $slot->hora_inicio }}" data-fin="{{ $slot->hora_fin }}"
                             onclick="eliminarSlot('{{ $tipo }}', {{ $i }}, '{{ $slot->hora_inicio }}')"
                             title="Click para eliminar">
                            {{ substr($slot->hora_inicio, 0, 5) }} - {{ substr($slot->hora_fin, 0, 5) }}
                        </div>
                    @endforeach
                    <div class="slot-add" onclick="agregarSlot('{{ $tipo }}', {{ $i }})">
                        + Añadir
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
