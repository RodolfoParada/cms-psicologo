@extends('dashboard.layout')

@section('titulo', 'Protección de datos')

@push('styles')
<link rel="stylesheet" href="{{ asset('dashboard/js/jodit/jodit.min.css') }}">
<style>
.pd-wrapper {
    max-width: 900px;
}

.pd-wrapper .header-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.pd-wrapper .header-card h2 {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.5rem;
}

.pd-wrapper .header-card p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin: 0;
}

.pd-wrapper .editor-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.pd-wrapper .editor-card .info-nota {
    display: flex;
    gap: 0.8rem;
    padding: 0.8rem;
    background: #dbeafe;
    border-radius: 8px;
    margin-bottom: 1rem;
    font-size: 0.85rem;
    color: #1e40af;
}

.pd-wrapper .editor-card .info-nota i {
    font-size: 1.2rem;
    flex-shrink: 0;
    margin-top: 0.05rem;
}

.pd-wrapper .editor-card .placeholders {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    margin-bottom: 1rem;
}

.pd-wrapper .editor-card .placeholder-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.2rem 0.6rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.15s;
}

.pd-wrapper .editor-card .placeholder-badge:hover {
    border-color: var(--accent);
    color: var(--accent);
}

.pd-wrapper .editor-wrapper {
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
}

.pd-wrapper .form-actions {
    display: flex;
    gap: 0.8rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
}

.btn-accion {
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    border: none;
    cursor: pointer;
    transition: opacity 0.2s;
}

.btn-accion:hover { opacity: 0.85; }
</style>
@endpush

@section('contenido')
<div class="pd-wrapper">
    <div class="header-card">
        <h2><i class="fas fa-shield-alt" style="margin-right:0.5rem;"></i>Protección de datos</h2>
        <p>Configura la plantilla del documento de información y consentimiento para protección de datos. Los placeholders se reemplazarán automáticamente al generar el PDF de cada paciente.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="editor-card">
        <div class="info-nota">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Placeholders disponibles:</strong> haz clic en uno para insertarlo en el editor en la posición del cursor.
            </div>
        </div>

        <div class="placeholders" id="placeholders">
            <span class="placeholder-badge" data-code="[NOMBRE_PACIENTE]">[NOMBRE_PACIENTE]</span>
            <span class="placeholder-badge" data-code="[TELEFONO_PACIENTE]">[TELEFONO_PACIENTE]</span>
            <span class="placeholder-badge" data-code="[EMAIL_PACIENTE]">[EMAIL_PACIENTE]</span>
            <span class="placeholder-badge" data-code="[DIRECCION_PACIENTE]">[DIRECCION_PACIENTE]</span>
            <span class="placeholder-badge" data-code="[NOMBRE_PSICOLOGA]">[NOMBRE_PSICOLOGA]</span>
            <span class="placeholder-badge" data-code="[NUMERO_COLEGIADO]">[NUMERO_COLEGIADO]</span>
            <span class="placeholder-badge" data-code="[EMAIL_PSICOLOGA]">[EMAIL_PSICOLOGA]</span>
            <span class="placeholder-badge" data-code="[TELEFONO_PSICOLOGA]">[TELEFONO_PSICOLOGA]</span>
            <span class="placeholder-badge" data-code="[FECHA_ACTUAL]">[FECHA_ACTUAL]</span>
        </div>

        <form method="POST" action="{{ route('proteccion-datos.guardar') }}">
            @csrf
            <div class="editor-wrapper">
                <textarea name="plantilla" id="plantilla" style="display:none;">{{ old('plantilla', $config->plantilla_proteccion_datos) }}</textarea>
                <div id="editor" style="min-height:500px;">{!! $config->plantilla_proteccion_datos !!}</div>
            </div>

            <div class="form-actions">
                <a href="{{ route('proteccion-datos.descargar') }}" class="btn-accion" style="background:var(--bg-secondary);color:var(--text-primary);">
                    <i class="fas fa-file-pdf"></i> Descargar plantilla vacía
                </a>
                <button type="submit" class="btn-accion" style="background:var(--accent);color:#fff;">
                    <i class="fas fa-save"></i> Guardar plantilla
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/js/jodit/jodit.min.js') }}"></script>
<script>
const editor = Jodit.make('#editor', {
    height: 550,
    language: 'es',
    uploader: { insertImageAsBase64URI: true },
    toolbarAdaptive: false,
    buttons: [
        'source', '|',
        'bold', 'italic', 'underline', '|',
        'ul', 'ol', '|',
        'font', 'fontsize', 'brush', 'paragraph', '|',
        'align', 'undo', 'redo', '|',
        'hr', 'eraser', 'fullsize',
    ],
});

document.querySelector('form').addEventListener('submit', function () {
    document.getElementById('plantilla').value = editor.value;
});

document.getElementById('placeholders').addEventListener('click', function (e) {
    const badge = e.target.closest('.placeholder-badge');
    if (!badge) return;
    editor.s.insertHTML(badge.dataset.code);
});
</script>
@endpush
