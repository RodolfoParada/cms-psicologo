@extends('dashboard.layout')

@section('titulo', isset($historia) ? 'Editar historia clínica' : 'Nueva historia clínica')

@push('styles')
<link rel="stylesheet" href="{{ asset('dashboard/js/jodit/jodit.min.css') }}">
<style>
.historia-form {
    max-width: 800px;
    background: var(--card-bg);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.historia-form h2 {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 1.5rem;
}

.historia-form .form-group {
    margin-bottom: 1.2rem;
}

.historia-form .form-group label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.4rem;
}

.historia-form .form-group input,
.historia-form .form-group select {
    width: 100%;
    padding: 0.65rem 0.9rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.9rem;
    color: var(--text-primary);
    background: var(--input-bg);
    box-sizing: border-box;
}

.historia-form .form-group input:focus,
.historia-form .form-group select:focus {
    outline: none;
    border-color: var(--accent);
}

.historia-form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.historia-form .editor-wrapper {
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
}

.historia-form .file-upload-area {
    border: 2px dashed var(--border);
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: var(--bg-secondary);
}

.historia-form .file-upload-area:hover {
    border-color: var(--accent);
    background: var(--card-bg);
}

.historia-form .file-upload-area i {
    font-size: 2rem;
    color: var(--text-secondary);
    display: block;
    margin-bottom: 0.5rem;
}

.historia-form .file-upload-area p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.historia-form .file-list {
    display: grid;
    gap: 0.5rem;
    margin-top: 1rem;
}

.historia-form .file-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.6rem 0.8rem;
    background: var(--bg-secondary);
    border-radius: 6px;
    font-size: 0.85rem;
    color: var(--text-primary);
}

.historia-form .file-item .file-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.historia-form .file-item .file-remove {
    background: none;
    border: none;
    color: #ef4444;
    cursor: pointer;
    font-size: 1.1rem;
    padding: 0;
}

.historia-form .archivos-subidos {
    display: grid;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.historia-form .archivo-subido {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0.8rem;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 0.85rem;
}

.historia-form .archivo-subido a {
    color: var(--accent);
    text-decoration: none;
}

.historia-form .archivo-subido a:hover {
    text-decoration: underline;
}

.historia-form .form-actions {
    display: flex;
    gap: 0.8rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
}

.archivo-input-wrapper {
    display: none;
}

@media (max-width: 768px) {
    .historia-form .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('contenido')
<div class="historia-form">
    <h2><i class="fas {{ isset($historia) ? 'fa-edit' : 'fa-plus' }}" style="margin-right:0.5rem;"></i>
        {{ isset($historia) ? 'Editar historia clínica' : 'Nueva historia clínica' }}
    </h2>

    <form method="POST" action="{{ isset($historia) ? route('historias.actualizar', $historia->id) : route('historias.guardar') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($historia)) @method('PUT') @endif

        <div class="form-row">
            <div class="form-group">
                <label for="paciente_id">Paciente *</label>
                <select name="paciente_id" id="paciente_id" required>
                    <option value="">Seleccionar paciente</option>
                    @foreach($pacientes as $p)
                        <option value="{{ $p->id }}" {{ old('paciente_id', $historia->paciente_id ?? $pacienteId ?? '') == $p->id ? 'selected' : '' }}>
                            {{ $p->nombre }} ({{ $p->telefono }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_sesion">Fecha de la sesión *</label>
                <input type="date" name="fecha_sesion" id="fecha_sesion"
                       value="{{ old('fecha_sesion', isset($historia) ? $historia->fecha_sesion->format('Y-m-d') : date('Y-m-d')) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="contenido">Notas de la sesión *</label>
            <div class="editor-wrapper">
                <textarea name="contenido" id="contenido" style="display:none;">{{ old('contenido', $historia->contenido ?? '') }}</textarea>
                <div id="editor" style="min-height:350px;">{!! old('contenido', $historia->contenido ?? '') !!}</div>
            </div>
        </div>

        <div class="form-group">
            <label>Archivos adjuntos (imágenes o PDF)</label>
            <div class="file-upload-area" id="fileUploadArea">
                <i class="fas fa-cloud-upload-alt"></i>
                <p><strong>Haz clic para subir archivos</strong> o arrastra y suelta</p>
                <p style="font-size:0.8rem;margin-top:0.3rem;">JPG, PNG, WEBP, PDF — Máx 10MB por archivo</p>
            </div>
            <div class="archivo-input-wrapper">
                <input type="file" name="archivos[]" id="archivosInput" multiple accept=".jpg,.jpeg,.png,.gif,.webp,.pdf">
            </div>
            <div class="file-list" id="fileList"></div>

            @if(isset($historia) && $historia->archivos->count() > 0)
                <div style="margin-top:1rem;">
                    <p style="font-size:0.85rem;font-weight:600;color:var(--text-secondary);margin-bottom:0.5rem;">Archivos actuales:</p>
                    <div class="archivos-subidos">
                        @foreach($historia->archivos as $archivo)
                            <div class="archivo-subido">
                                <span class="file-info">
                                    <i class="fas {{ $archivo->tipo == 'pdf' ? 'fa-file-pdf' : 'fa-image' }}" style="color:var(--text-secondary);"></i>
                                    <a href="{{ asset('storage/' . $archivo->archivo_path) }}" target="_blank">{{ $archivo->nombre_original }}</a>
                                </span>
                                <a href="{{ route('historias.archivo.eliminar', $archivo->id) }}" style="color:#ef4444;text-decoration:none;font-size:0.85rem;"
                                   onclick="return confirm('¿Eliminar este archivo?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="form-actions">
            <a href="{{ route('historias.index') }}" style="padding:0.65rem 1.3rem;border-radius:8px;font-size:0.9rem;font-weight:600;text-decoration:none;color:var(--text-primary);background:var(--bg-secondary);border:1px solid var(--border);">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
            <button type="submit" style="padding:0.65rem 1.5rem;border-radius:8px;font-size:0.9rem;font-weight:600;background:var(--accent);color:#fff;border:none;cursor:pointer;">
                <i class="fas fa-save"></i> {{ isset($historia) ? 'Actualizar historia' : 'Guardar historia' }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/js/jodit/jodit.min.js') }}"></script>
<script>
const editor = Jodit.make('#editor', {
    height: 400,
    language: 'es',
    uploader: { insertImageAsBase64URI: true },
    toolbarAdaptive: false,
    buttons: [
        'source', '|',
        'bold', 'italic', 'underline', 'strikethrough', '|',
        'ul', 'ol', '|',
        'outdent', 'indent', '|',
        'font', 'fontsize', 'brush', 'paragraph', '|',
        'link', '|',
        'align', 'undo', 'redo', '|',
        'hr', 'eraser', 'fullsize',
    ],
});

document.querySelector('form').addEventListener('submit', function () {
    document.getElementById('contenido').value = editor.value;
});

const fileArea = document.getElementById('fileUploadArea');
const fileInput = document.getElementById('archivosInput');
const fileList = document.getElementById('fileList');

fileArea.addEventListener('click', function () {
    fileInput.click();
});

fileInput.addEventListener('change', function () {
    fileList.innerHTML = '';
    const files = Array.from(this.files);
    files.forEach(function (file) {
        const size = (file.size / 1024 / 1024).toFixed(1);
        const item = document.createElement('div');
        item.className = 'file-item';
        item.innerHTML = '<span class="file-info"><i class="fas ' + (file.type === 'application/pdf' ? 'fa-file-pdf' : 'fa-image') + '" style="color:var(--text-secondary);"></i> ' + file.name + ' (' + size + ' MB)</span>';
        fileList.appendChild(item);
    });
});

fileArea.addEventListener('dragover', function (e) {
    e.preventDefault();
    this.style.borderColor = 'var(--accent)';
    this.style.background = 'var(--card-bg)';
});

fileArea.addEventListener('dragleave', function () {
    this.style.borderColor = 'var(--border)';
    this.style.background = 'var(--bg-secondary)';
});

fileArea.addEventListener('drop', function (e) {
    e.preventDefault();
    this.style.borderColor = 'var(--border)';
    this.style.background = 'var(--bg-secondary)';
    fileInput.files = e.dataTransfer.files;
    fileInput.dispatchEvent(new Event('change'));
});
</script>
@endpush
