@extends('dashboard.layout')

@section('titulo', 'Calendario de citas')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@calendarjs/ce/dist/style.min.css" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons" />
<style>
.calendario-wrapper {
    background: var(--card-bg);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.calendario-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.calendario-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.calendario-header .btn-accion {
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

#schedule-container {
    min-height: 550px;
}

.calendario-leyenda {
    display: flex;
    gap: 1.5rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
    flex-wrap: wrap;
}

.leyenda-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.leyenda-color {
    width: 14px;
    height: 14px;
    border-radius: 3px;
}

.modal-calendario {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 5000;
    align-items: center;
    justify-content: center;
}

.modal-calendario.visible {
    display: flex;
}

.modal-calendario .modal-content {
    background: var(--card-bg);
    border-radius: 16px;
    width: 90%;
    max-width: 520px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modal-calendario .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 1.5rem 0;
}

.modal-calendario .modal-header h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.modal-calendario .modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text-secondary);
    padding: 0.25rem;
    line-height: 1;
}

.modal-calendario .modal-body {
    padding: 1.5rem;
}

.modal-calendario .form-group {
    margin-bottom: 1rem;
}

.modal-calendario .form-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.35rem;
}

.modal-calendario .form-group input,
.modal-calendario .form-group select,
.modal-calendario .form-group textarea {
    width: 100%;
    padding: 0.6rem 0.8rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.9rem;
    color: var(--text-primary);
    background: var(--input-bg);
    transition: border-color 0.2s;
    box-sizing: border-box;
}

.modal-calendario .form-group input:focus,
.modal-calendario .form-group select:focus,
.modal-calendario .form-group textarea:focus {
    outline: none;
    border-color: var(--accent);
}

.modal-calendario .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.modal-calendario .modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.8rem;
    padding: 0 1.5rem 1.5rem;
}

.modal-calendario .modal-footer .btn {
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
}

.modal-calendario .modal-footer .btn-secondary {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.modal-calendario .modal-footer .btn-danger {
    background: #ef4444;
    color: #fff;
}

.modal-calendario .modal-footer .btn-primary {
    background: var(--accent);
    color: #fff;
}

.modal-calendario .modal-footer .btn:hover {
    opacity: 0.85;
}

.modal-calendario .form-error {
    color: #ef4444;
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

@media (max-width: 768px) {
    .modal-calendario .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('contenido')
<div class="calendario-wrapper">
    <div class="calendario-header">
        <h2><i class="fas fa-calendar-alt" style="margin-right:0.5rem;"></i>Calendario de citas</h2>
        <div>
            <a href="{{ route('citas.crear') }}" class="btn-accion" style="background:var(--accent);color:#fff;text-decoration:none;">
                <i class="fas fa-plus"></i> Nueva cita
            </a>
        </div>
    </div>

    <div id="schedule-container"></div>

    <div class="calendario-leyenda">
        <div class="leyenda-item"><span class="leyenda-color" style="background:#f59e0b;"></span> Pendiente</div>
        <div class="leyenda-item"><span class="leyenda-color" style="background:#10b981;"></span> Confirmada</div>
        <div class="leyenda-item"><span class="leyenda-color" style="background:#3b82f6;"></span> Completada</div>
        <div class="leyenda-item"><span class="leyenda-color" style="background:#ef4444;"></span> Cancelada</div>
    </div>
</div>

<div class="modal-calendario" id="modalCita">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitulo">Nueva cita</h3>
            <button class="modal-close" id="modalCitaClose">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="citaId" value="">
            <div class="form-group">
                <label for="pacienteNombre">Nombre del paciente *</label>
                <input type="text" id="pacienteNombre" placeholder="Nombre completo">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="pacienteTelefono">Teléfono *</label>
                    <input type="text" id="pacienteTelefono" placeholder="+56 9 1234 5678">
                </div>
                <div class="form-group">
                    <label for="pacienteEmail">Email</label>
                    <input type="email" id="pacienteEmail" placeholder="paciente@email.com">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="citaFecha">Fecha *</label>
                    <input type="date" id="citaFecha">
                </div>
                <div class="form-group">
                    <label for="citaTipo">Tipo *</label>
                    <select id="citaTipo">
                        <option value="online">Online</option>
                        <option value="presencial">Presencial</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="citaHoraInicio">Hora inicio *</label>
                    <input type="time" id="citaHoraInicio">
                </div>
                <div class="form-group">
                    <label for="citaHoraFin">Hora fin *</label>
                    <input type="time" id="citaHoraFin">
                </div>
            </div>
            <div class="form-group" id="estadoGroup" style="display:none;">
                <label for="citaEstado">Estado</label>
                <select id="citaEstado">
                    <option value="pendiente">Pendiente</option>
                    <option value="confirmada">Confirmada</option>
                    <option value="completada">Completada</option>
                    <option value="cancelada">Cancelada</option>
                </select>
            </div>
            <div class="form-group">
                <label for="citaMotivo">Motivo de la consulta</label>
                <textarea id="citaMotivo" rows="2" placeholder="Motivo de la consulta (opcional)"></textarea>
            </div>
            <div class="form-error" id="modalError" style="display:none;"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-danger" id="btnEliminar" style="display:none;margin-right:auto;">
                <i class="fas fa-trash"></i> Eliminar
            </button>
            <button class="btn btn-secondary" id="modalCancelar">Cancelar</button>
            <button class="btn btn-primary" id="modalGuardar">
                <i class="fas fa-save"></i> <span id="btnGuardarTexto">Guardar cita</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/lemonadejs/dist/lemonade.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@calendarjs/ce/dist/index.min.js"></script>
<script src="{{ asset('dashboard/js/calendario.js') }}"></script>
<script>
const citasData = @json($citas);
const vacacionesData = @json($vacaciones);
const modoVacaciones = @json($modoVacaciones);
const descansoOnline = @json($descansoOnline);
const descansoPresencial = @json($descansoPresencial);
const minMaxOnline = @json($minMaxOnline);
const minMaxPresencial = @json($minMaxPresencial);
</script>
@endpush
