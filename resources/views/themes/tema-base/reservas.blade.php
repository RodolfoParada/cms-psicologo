@extends("themes.{$carpeta}.layout")

@push('styles')
<link rel="stylesheet" href="{{ asset("themes/{$carpeta}/css/subpage.css") }}">
<style>
.reservas-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
@media (max-width: 768px) { .reservas-grid { grid-template-columns: 1fr; } }

.reservas-tipo-selector { display: flex; gap: 1rem; margin-bottom: 1.5rem; }
.reservas-tipo-btn { flex: 1; padding: 1rem; border: 2px solid var(--color-gray4); border-radius: 8px; background: #fff; cursor: pointer; text-align: center; font-size: 1rem; font-weight: 600; transition: 0.2s; color: var(--color-gray2); }
.reservas-tipo-btn:hover { border-color: var(--color-primary); color: var(--color-primary); }
.reservas-tipo-btn.active { border-color: var(--color-primary); background: var(--color-primary); color: #fff; }

.reservas-calendar { margin-bottom: 1.5rem; }
.reservas-calendar table { width: 100%; border-collapse: collapse; }
.reservas-calendar th, .reservas-calendar td { text-align: center; padding: 0.6rem; }
.reservas-calendar th { color: var(--color-gray2); font-weight: 600; }
.reservas-calendar td { cursor: pointer; border-radius: 6px; transition: 0.15s; }
.reservas-calendar td:hover { background: var(--color-gray4); }
.reservas-calendar td.available { color: var(--color-primary); font-weight: 600; }
.reservas-calendar td.available:hover { background: var(--color-primary); color: #fff; }
.reservas-calendar td.selected { background: var(--color-primary) !important; color: #fff; }
.reservas-calendar td.unavailable { color: #ccc; cursor: not-allowed; }
.reservas-calendar td.past { color: #ddd; cursor: not-allowed; }
.reservas-calendar .cal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem; }
.reservas-calendar .cal-header button { background: none; border: 1px solid var(--color-gray4); border-radius: 4px; padding: 0.3rem 0.8rem; cursor: pointer; font-size: 1rem; }
.reservas-calendar .cal-header span { font-weight: 700; font-size: 1.1rem; }

.reservas-slots { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; }
.reservas-slot { padding: 0.6rem 1rem; border: 1px solid var(--color-gray4); border-radius: 6px; cursor: pointer; font-size: 0.9rem; transition: 0.15s; color: var(--color-gray2); }
.reservas-slot:hover { border-color: var(--color-primary); }
.reservas-slot.selected { background: var(--color-primary); color: #fff; border-color: var(--color-primary); }

.reservas-form { display: flex; flex-direction: column; gap: 1rem; }
.reservas-form input, .reservas-form textarea { padding: 0.8rem 1rem; border: 1px solid var(--color-gray4); border-radius: 6px; font-size: 1rem; font-family: inherit; }
.reservas-form input:focus, .reservas-form textarea:focus { outline: none; border-color: var(--color-primary); }
.reservas-form .btn-submit { padding: 0.9rem; background: var(--color-primary); color: #fff; border: none; border-radius: 50px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: 0.2s; }
.reservas-form .btn-submit:hover { opacity: 0.85; }
.reservas-form .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }

.reservas-info { font-size: 0.9rem; color: var(--color-gray2); margin-bottom: 1rem; }

/* Modal */
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 9999; }
.modal-overlay.show { display: flex; }
.modal-content { background: #fff; border-radius: 16px; padding: 2.5rem; max-width: 440px; width: 90%; text-align: center; }
.modal-content h3 { color: var(--color-primary); margin-bottom: 0.5rem; }
.modal-content .modal-icon { font-size: 3rem; color: #22c55e; margin-bottom: 1rem; }
.modal-content .modal-details { margin: 1.5rem 0; color: var(--color-gray2); line-height: 1.7; }
.modal-content .modal-btns { display: flex; flex-direction: column; gap: 0.6rem; }
.modal-content .modal-btns a { padding: 0.8rem; border-radius: 50px; text-decoration: none; font-weight: 600; }
.modal-content .btn-google { background: #1a73e8; color: #fff; }
.modal-content .btn-cerrar { background: var(--color-gray4); color: var(--color-gray2); cursor: pointer; border: none; font-size: 0.95rem; }
</style>
@endpush

@section('content')
<div class="subpage">
    <div class="subpage__container">
        <div class="subpage__header">
            <h2>{{ $frases['reservas_titulo']->valor ?? 'Reserva tu cita' }}</h2>
            <p>{{ $frases['reservas_subtitulo']->valor ?? 'Elige el tipo de sesión, fecha y horario que mejor se adapte a ti' }}</p>
        </div>

        @if($modoVacaciones)
            <div style="text-align:center;padding:3rem;background:var(--color-gray4);border-radius:12px;color:var(--color-gray2);">
                <i class="fa-solid fa-umbrella-beach" style="font-size:3rem;margin-bottom:1rem;display:block;"></i>
                <h3 style="margin-bottom:0.5rem;">La psicóloga está de vacaciones</h3>
                <p>El sistema de reservas está desactivado temporalmente. Vuelve a intentarlo más tarde.</p>
            </div>
        @else
        <div class="subpage__contact" style="grid-template-columns:1fr 1fr;">
            <div class="reservas-form-col">
                <div class="reservas-tipo-selector">
                    <button type="button" class="reservas-tipo-btn active" data-tipo="online">Online</button>
                    <button type="button" class="reservas-tipo-btn" data-tipo="presencial">Presencial</button>
                </div>

                <div class="reservas-calendar" id="calendarWidget">
                    <div class="cal-header">
                        <button type="button" id="calPrev">←</button>
                        <span id="calMonthYear"></span>
                        <button type="button" id="calNext">→</button>
                    </div>
                    <table>
                        <thead><tr>
                            <th>Dom</th><th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th>
                        </tr></thead>
                        <tbody id="calBody"></tbody>
                    </table>
                </div>

                <div id="slotsContainer" style="display:none;">
                    <p class="reservas-info">Selecciona un horario disponible:</p>
                    <div class="reservas-slots" id="slotsList"></div>
                </div>
                <div id="noSlotsMsg" style="display:none;text-align:center;padding:1rem;color:var(--color-gray2);">No hay horarios disponibles para este día.</div>
                <div id="loadingSlots" style="display:none;text-align:center;padding:1rem;color:var(--color-gray2);"><i class="fa-solid fa-spinner fa-spin"></i> Cargando horarios...</div>
            </div>

            <div style="background:var(--color-gray4);padding:2rem;border-radius:12px;">
                <h3 style="margin-bottom:1rem;font-size:1.2rem;color:var(--color-primary);">Tus datos</h3>
                <form class="reservas-form" id="reservaForm">
                    @csrf
                    <input type="hidden" name="tipo" id="formTipo" value="online">
                    <input type="hidden" name="fecha" id="formFecha">
                    <input type="hidden" name="hora_inicio" id="formHora">

                    <input type="text" name="nombre" id="formNombre" placeholder="Nombre completo *" required>
                    <input type="tel" name="telefono" id="formTelefono" placeholder="Teléfono *" required>
                    <input type="email" name="email" id="formEmail" placeholder="Email (opcional)">
                    <textarea name="motivo" id="formMotivo" rows="3" placeholder="Motivo de la consulta (opcional)"></textarea>

                    <button type="submit" class="btn-submit" id="btnSubmit">Confirmar reserva</button>
                </form>
                <p style="font-size:0.8rem;color:var(--color-gray2);margin-top:0.8rem;">* Campos obligatorios</p>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal éxito --}}
<div class="modal-overlay" id="successModal">
    <div class="modal-content">
        <div class="modal-icon"><i class="fa-solid fa-circle-check"></i></div>
        <h3>¡Cita agendada!</h3>
        <p style="color:var(--color-gray2);">Tu cita ha sido registrada correctamente.</p>
        <div class="modal-details" id="modalDetails"></div>
        <div class="modal-btns">
            <a href="#" id="googleCalLink" class="btn-google" target="_blank">
                <i class="fa-regular fa-calendar-plus"></i> Añadir a Google Calendar
            </a>
            <button type="button" class="btn-cerrar" id="closeModal">Cerrar</button>
        </div>
    </div>
</div>

{{-- Loading overlay --}}
<div class="modal-overlay" id="loadingModal">
    <div class="modal-content" style="padding:1.5rem;">
        <i class="fa-solid fa-spinner fa-spin" style="font-size:2rem;color:var(--color-primary);"></i>
        <p style="margin-top:1rem;">Procesando tu reserva...</p>
    </div>
</div>

{{-- Error modal --}}
<div class="modal-overlay" id="errorModal">
    <div class="modal-content">
        <div class="modal-icon" style="color:#ef4444;"><i class="fa-solid fa-circle-xmark"></i></div>
        <h3>No se pudo agendar la cita</h3>
        <p style="color:var(--color-gray2);margin:1rem 0;" id="errorMsg">Ocurrió un error. Intenta de nuevo.</p>
        <div class="modal-btns">
            <button type="button" class="btn-cerrar" id="closeError">Cerrar</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const monthNames = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    const dayNames = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];

    let currentDate = new Date();
    let selectedDate = null;
    let selectedTipo = 'online';
    let availableDates = new Set();
    let slotsCache = {};
    let loadingCal = false;

    const calBody = document.getElementById('calBody');
    const calMonthYear = document.getElementById('calMonthYear');
    const calPrev = document.getElementById('calPrev');
    const calNext = document.getElementById('calNext');
    const slotsContainer = document.getElementById('slotsContainer');
    const slotsList = document.getElementById('slotsList');
    const noSlotsMsg = document.getElementById('noSlotsMsg');
    const loadingSlots = document.getElementById('loadingSlots');
    const formTipo = document.getElementById('formTipo');
    const formFecha = document.getElementById('formFecha');
    const formHora = document.getElementById('formHora');

    document.querySelectorAll('.reservas-tipo-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.reservas-tipo-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedTipo = this.dataset.tipo;
            formTipo.value = selectedTipo;
            slotsCache = {};
            selectedDate = null;
            slotsContainer.style.display = 'none';
            noSlotsMsg.style.display = 'none';
            loadAvailability();
        });
    });

    function loadAvailability() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth() + 1;

        availableDates.clear();
        loadingCal = true;
        renderCalendar();

        fetch('{{ route("reservas.calendario") }}?mes=' + month + '&anio=' + year + '&tipo=' + selectedTipo)
            .then(r => r.json())
            .then(data => {
                if (data.fechas) {
                    data.fechas.forEach(function(f) { availableDates.add(f); });
                }
                loadingCal = false;
                renderCalendar();
            })
            .catch(function() {
                loadingCal = false;
                renderCalendar();
            });
    }

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        calMonthYear.textContent = monthNames[month] + ' ' + year;

        const today = new Date();
        today.setHours(0,0,0,0);
        const firstDay = new Date(year, month, 1).getDay();

        calBody.innerHTML = '';
        let row = document.createElement('tr');
        for (let i = 0; i < firstDay; i++) {
            row.appendChild(document.createElement('td'));
        }

        const daysInMonth = new Date(year, month + 1, 0).getDate();
        for (let d = 1; d <= daysInMonth; d++) {
            const cellDate = new Date(year, month, d);
            if (row.children.length === 7) {
                calBody.appendChild(row);
                row = document.createElement('tr');
            }

            const td = document.createElement('td');
            td.textContent = d;

            const dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');

            if (cellDate <= today) {
                td.className = 'past';
            } else if (availableDates.has(dateStr)) {
                td.className = 'available';
                td.dataset.date = dateStr;
                td.addEventListener('click', function() {
                    document.querySelectorAll('#calBody td.selected').forEach(el => el.classList.remove('selected'));
                    this.classList.add('selected');
                    selectDate(this.dataset.date);
                });
            } else {
                td.className = 'unavailable';
            }

            if (selectedDate === dateStr) {
                td.classList.add('selected');
            }

            row.appendChild(td);
        }

        while (row.children.length < 7) {
            row.appendChild(document.createElement('td'));
        }
        calBody.appendChild(row);
    }

    function selectDate(dateStr) {
        selectedDate = dateStr;
        formFecha.value = dateStr;
        formHora.value = '';
        slotsContainer.style.display = 'none';
        noSlotsMsg.style.display = 'none';

        if (slotsCache[dateStr]) {
            renderSlots(slotsCache[dateStr]);
            return;
        }

        loadingSlots.style.display = 'block';
        fetch('{{ route("reservas.slots") }}?fecha=' + dateStr + '&tipo=' + selectedTipo)
            .then(r => r.json())
            .then(data => {
                loadingSlots.style.display = 'none';
                if (data.slots && data.slots.length > 0) {
                    slotsCache[dateStr] = data.slots;
                    renderSlots(data.slots);
                } else {
                    noSlotsMsg.style.display = 'block';
                }
            })
            .catch(() => {
                loadingSlots.style.display = 'none';
                noSlotsMsg.style.display = 'block';
            });
    }

    function renderSlots(slots) {
        slotsList.innerHTML = '';
        slotsContainer.style.display = 'block';
        noSlotsMsg.style.display = 'none';

        slots.forEach(s => {
            const div = document.createElement('div');
            div.className = 'reservas-slot';
            div.textContent = s.label;
            div.dataset.hora = s.hora;
            div.addEventListener('click', function() {
                document.querySelectorAll('.reservas-slot').forEach(el => el.classList.remove('selected'));
                this.classList.add('selected');
                formHora.value = this.dataset.hora;
            });
            slotsList.appendChild(div);
        });
    }

    calPrev.addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        if (currentDate.getMonth() < new Date().getMonth() && currentDate.getFullYear() <= new Date().getFullYear()) {
            currentDate.setMonth(new Date().getMonth());
            return;
        }
        slotsCache = {};
        selectedDate = null;
        slotsContainer.style.display = 'none';
        noSlotsMsg.style.display = 'none';
        loadAvailability();
    });

    calNext.addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        slotsCache = {};
        selectedDate = null;
        slotsContainer.style.display = 'none';
        noSlotsMsg.style.display = 'none';
        loadAvailability();
    });

    // Form submit
    document.getElementById('reservaForm').addEventListener('submit', function(e) {
        e.preventDefault();

        if (!formFecha.value || !formHora.value) {
            document.getElementById('errorMsg').textContent = 'Selecciona una fecha y un horario disponible.';
            document.getElementById('errorModal').classList.add('show');
            return;
        }

        document.getElementById('loadingModal').classList.add('show');

        const formData = new FormData(this);

        fetch('{{ route("reservas.store") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('loadingModal').classList.remove('show');
            if (data.success) {
                showSuccess(data.cita);
            } else {
                document.getElementById('errorMsg').textContent = data.error || 'Ocurrió un error al agendar la cita.';
                document.getElementById('errorModal').classList.add('show');
            }
        })
        .catch(() => {
            document.getElementById('loadingModal').classList.remove('show');
            document.getElementById('errorMsg').textContent = 'Error de conexión. Intenta de nuevo.';
            document.getElementById('errorModal').classList.add('show');
        });
    });

    function showSuccess(cita) {
        const tipoLabel = cita.tipo === 'online' ? 'Online' : 'Presencial';
        document.getElementById('modalDetails').innerHTML =
            '<strong>' + cita.nombre + '</strong><br>' +
            cita.fecha + ' · ' + cita.hora_inicio + ' - ' + cita.hora_fin + '<br>' +
            'Sesión ' + tipoLabel;

        // Google Calendar link
        const start = cita.fecha + 'T' + cita.hora_inicio + ':00';
        const end = cita.fecha + 'T' + cita.hora_fin + ':00';
        const gcalUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE' +
            '&text=' + encodeURIComponent('Cita con ' + '{{ $psicologa->nombre_completo }}') +
            '&dates=' + start.replace(/[-:]/g, '') + '/' + end.replace(/[-:]/g, '') +
            '&details=' + encodeURIComponent('Sesión ' + tipoLabel + ' de psicología');
        document.getElementById('googleCalLink').href = gcalUrl;

        document.getElementById('successModal').classList.add('show');
    }

    // Close modals
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('successModal').classList.remove('show');
        // Reset form
        document.getElementById('reservaForm').reset();
        formFecha.value = '';
        formHora.value = '';
        document.querySelectorAll('.reservas-slot').forEach(el => el.classList.remove('selected'));
        document.querySelectorAll('#calBody td.selected').forEach(el => el.classList.remove('selected'));
        selectedDate = null;
    });

    document.getElementById('closeError').addEventListener('click', function() {
        document.getElementById('errorModal').classList.remove('show');
    });

    // Init
    loadAvailability();
})();
</script>
@endpush
