@extends('instalacion.layout', ['pasoActual' => 6])

@section('contenido')
<h2>Foto de perfil</h2>
<p class="descripcion">Sube una foto tuya profesional. Recomendamos una foto sin fondo para mejor integración visual.</p>

<form method="POST" action="{{ route('instalacion.paso6.post') }}" enctype="multipart/form-data">
    @csrf

    <div class="file-upload" onclick="document.getElementById('foto').click()">
        <div class="icono">📷</div>
        <p>Haz clic para seleccionar tu foto</p>
        <p style="font-size:1.1rem; color:#a6948a;">PNG, JPG o WebP. Máximo 2MB. Preferiblemente sin fondo.</p>
        <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/webp" onchange="previewFoto(event)">
    </div>

    <div id="preview" style="display:none; margin-top:1.5rem; text-align:center;">
        <img id="preview-img" style="max-width:20rem; border-radius:1rem; box-shadow:0 0.4rem 1.2rem rgba(0,0,0,0.1);">
    </div>

    <div class="btn-group" style="margin-top:2rem;">
        <a href="{{ route('instalacion.paso5') }}" class="btn btn-secondary">Anterior</a>
        <button type="submit" class="btn btn-primary">Siguiente</button>
    </div>
</form>

<script>
    function previewFoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
