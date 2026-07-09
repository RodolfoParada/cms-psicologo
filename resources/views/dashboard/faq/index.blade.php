@extends('dashboard.layout')

@section('titulo', 'Preguntas frecuentes')

@push('styles')
<style>
.faq-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.faq-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.faq-list {
    display: grid;
    gap: 0.8rem;
}

.faq-item {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
}

.faq-pregunta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.2rem;
    cursor: pointer;
    user-select: none;
    gap: 1rem;
}

.faq-pregunta:hover {
    background: var(--bg-secondary);
}

.faq-pregunta .texto {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.95rem;
    flex: 1;
}

.faq-pregunta .acciones {
    display: flex;
    gap: 0.4rem;
    align-items: center;
    flex-shrink: 0;
}

.faq-respuesta {
    padding: 0 1.2rem 1rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.6;
    display: none;
}

.faq-item.abierto .faq-respuesta {
    display: block;
}

.modal-faq {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 5000;
    align-items: center;
    justify-content: center;
}

.modal-faq.visible { display: flex; }

.modal-faq .modal-content {
    background: var(--card-bg);
    border-radius: 16px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 1.5rem;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modal-faq .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.2rem;
}

.modal-faq .modal-header h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.modal-faq .modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text-secondary);
}

.modal-faq .form-group {
    margin-bottom: 1rem;
}

.modal-faq .form-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.3rem;
}

.modal-faq .form-group input,
.modal-faq .form-group textarea {
    width: 100%;
    padding: 0.6rem 0.8rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.9rem;
    background: var(--input-bg);
    color: var(--text-primary);
    box-sizing: border-box;
}

.modal-faq .form-group textarea {
    min-height: 120px;
    resize: vertical;
}

.modal-faq .modal-footer {
    display: flex;
    gap: 0.8rem;
    justify-content: flex-end;
    margin-top: 1.2rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border);
}

.badge-faq {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-faq.activa { background: #d1fae5; color: #065f46; }
.badge-faq.inactiva { background: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('contenido')
<div class="faq-header">
    <h2><i class="fas fa-question-circle" style="margin-right:0.5rem;"></i>Preguntas frecuentes</h2>
    <button class="btn-accion" style="background:var(--accent);color:#fff;border:none;padding:0.6rem 1.2rem;border-radius:8px;font-size:0.9rem;font-weight:600;cursor:pointer;" onclick="abrirModal()">
        <i class="fas fa-plus"></i> Nueva pregunta
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($faqs->count() > 0)
    <div class="faq-list">
        @foreach($faqs as $faq)
            <div class="faq-item {{ $faq->activo ? '' : 'inactiva' }}">
                <div class="faq-pregunta" onclick="this.parentElement.classList.toggle('abierto')">
                    <span class="texto">
                        @if(!$faq->activo)
                            <span class="badge-faq inactiva" style="margin-right:0.5rem;">Borrador</span>
                        @endif
                        {{ $faq->pregunta }}
                        <span style="font-size:0.8rem;color:var(--text-secondary);margin-left:0.5rem;">(#{{ $faq->orden }})</span>
                    </span>
                    <span class="acciones">
                        <form action="{{ route('faq.toggle', $faq->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn-icono" title="{{ $faq->activo ? 'Desactivar' : 'Activar' }}">
                                <i class="fas {{ $faq->activo ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                            </button>
                        </form>
                        <button class="btn-icono" onclick="event.stopPropagation();editarFaq({{ $faq->id }}, '{{ addslashes($faq->pregunta) }}', '{{ addslashes($faq->respuesta) }}', {{ $faq->orden }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('faq.destroy', $faq->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta pregunta?');">
                            @csrf @method('DELETE')
                            <button class="btn-icono danger" onclick="event.stopPropagation()"><i class="fas fa-trash"></i></button>
                        </form>
                        <i class="fas fa-chevron-down" style="color:var(--text-secondary);font-size:0.85rem;"></i>
                    </span>
                </div>
                <div class="faq-respuesta">{!! nl2br(e($faq->respuesta)) !!}</div>
            </div>
        @endforeach
    </div>
@else
    <div style="text-align:center;padding:4rem 2rem;background:var(--card-bg);border-radius:12px;border:1px solid var(--border);">
        <div style="font-size:3rem;color:var(--text-secondary);margin-bottom:1rem;"><i class="fas fa-question-circle"></i></div>
        <h3 style="color:var(--text-primary);margin-bottom:0.5rem;">No hay preguntas frecuentes</h3>
        <p style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:1.5rem;">Añade preguntas frecuentes para ayudar a tus pacientes.</p>
        <button class="btn-accion" style="background:var(--accent);color:#fff;border:none;padding:0.7rem 1.5rem;border-radius:8px;font-weight:600;cursor:pointer;" onclick="abrirModal()">
            <i class="fas fa-plus"></i> Añadir pregunta
        </button>
    </div>
@endif

<div class="modal-faq" id="modalFaq">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitulo">Nueva pregunta frecuente</h3>
            <button class="modal-close" onclick="cerrarModal()">&times;</button>
        </div>
        <form id="formFaq" method="POST">
            @csrf
            <input type="hidden" name="_method" id="faqMethod" value="POST">
            <input type="hidden" name="id" id="faqId" value="">
            <div class="form-group">
                <label for="faqPregunta">Pregunta *</label>
                <input type="text" name="pregunta" id="faqPregunta" required>
            </div>
            <div class="form-group">
                <label for="faqRespuesta">Respuesta *</label>
                <textarea name="respuesta" id="faqRespuesta" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="faqOrden">Orden</label>
                <input type="number" name="orden" id="faqOrden" value="0" min="0">
            </div>
            <div class="modal-footer">
                <button type="button" style="padding:0.5rem 1rem;border-radius:6px;border:1px solid var(--border);background:var(--card-bg);color:var(--text-primary);cursor:pointer;" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" style="padding:0.5rem 1rem;border-radius:6px;background:var(--accent);color:#fff;border:none;cursor:pointer;font-weight:600;" id="btnGuardarFaq"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModal() {
    document.getElementById('modalTitulo').textContent = 'Nueva pregunta frecuente';
    document.getElementById('faqMethod').value = 'POST';
    document.getElementById('faqId').value = '';
    document.getElementById('faqPregunta').value = '';
    document.getElementById('faqRespuesta').value = '';
    document.getElementById('faqOrden').value = '0';
    document.getElementById('formFaq').action = '{{ route("faq.store") }}';
    document.getElementById('btnGuardarFaq').innerHTML = '<i class="fas fa-save"></i> Guardar';
    document.getElementById('modalFaq').classList.add('visible');
}

function editarFaq(id, pregunta, respuesta, orden) {
    document.getElementById('modalTitulo').textContent = 'Editar pregunta frecuente';
    document.getElementById('faqMethod').value = 'PUT';
    document.getElementById('faqId').value = id;
    document.getElementById('faqPregunta').value = pregunta;
    document.getElementById('faqRespuesta').value = respuesta;
    document.getElementById('faqOrden').value = orden;
    document.getElementById('formFaq').action = '{{ url("panel-psicologa/faq") }}/' + id;
    document.getElementById('btnGuardarFaq').innerHTML = '<i class="fas fa-save"></i> Actualizar';
    document.getElementById('modalFaq').classList.add('visible');
}

function cerrarModal() {
    document.getElementById('modalFaq').classList.remove('visible');
}

document.getElementById('modalFaq').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
@endsection
