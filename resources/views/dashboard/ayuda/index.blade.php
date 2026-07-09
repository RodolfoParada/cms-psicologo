@extends('dashboard.layout')

@section('titulo', 'Ayuda')

@push('styles')
<style>
.help-section {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.2rem;
}
.help-section h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.help-section p, .help-section li {
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.7;
}
.help-section ul { padding-left: 1.2rem; margin: 0.5rem 0; }
.help-section .step {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border);
}
.help-section .step:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
.help-section .step-num {
    width: 32px; height: 32px; border-radius: 50%;
    background: var(--accent); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 0.85rem; flex-shrink: 0;
}
</style>
@endpush

@section('contenido')
<div style="margin-bottom:1.5rem;">
    <h2 style="font-size:1.5rem;font-weight:700;color:var(--text-primary);margin:0;">
        <i class="fas fa-question-circle" style="margin-right:0.5rem;"></i>Ayuda y tutorial
    </h2>
    <p style="color:var(--text-secondary);font-size:0.9rem;margin:0.3rem 0 0;">
        Guía rápida para usar PsicoCMS.
    </p>
</div>

<div class="help-section">
    <h3><i class="fas fa-rocket"></i> Primeros pasos</h3>
    <div class="step">
        <div class="step-num">1</div>
        <div><strong>Configura tu disponibilidad</strong><br>Ve a Configuración > Disponibilidad para establecer tus horarios de atención online y presencial, duración de sesiones y periodos de vacaciones.</div>
    </div>
    <div class="step">
        <div class="step-num">2</div>
        <div><strong>Personaliza tu web</strong><br>En Configuración > Configuración web puedes cambiar todos tus datos públicos, el modo de visualización (landing o multipágina) y activar/desactivar secciones.</div>
    </div>
    <div class="step">
        <div class="step-num">3</div>
        <div><strong>Selecciona un tema visual</strong><br>En Configuración > Temas visuales puedes elegir entre 5 diseños diferentes. También puedes ver una vista previa antes de activarlo.</div>
    </div>
</div>

<div class="help-section">
    <h3><i class="fas fa-calendar-check"></i> Gestión de citas</h3>
    <p>Las citas se pueden gestionar de dos formas:</p>
    <ul>
        <li><strong>Listado:</strong> En Gestión principal > Citas puedes ver, filtrar, crear, editar y eliminar citas.</li>
        <li><strong>Calendario:</strong> En Gestión principal > Calendario tienes una vista tipo Google Calendar donde puedes arrastrar citas para cambiar su horario.</li>
    </ul>
    <p>Cuando un paciente reserva desde la web pública, la cita se crea automáticamente y se te notificará por email si tienes configuradas las notificaciones.</p>
</div>

<div class="help-section">
    <h3><i class="fas fa-users"></i> Pacientes e historias</h3>
    <ul>
        <li>Los pacientes se crean automáticamente al reservar una cita (con su teléfono como identificador).</li>
        <li>También puedes añadirlos manualmente desde Gestión principal > Pacientes.</li>
        <li>En la ficha de cada paciente puedes ver su historial de citas y descargar el documento de protección de datos.</li>
        <li>Las <strong>historias clínicas</strong> te permiten llevar un registro detallado de cada sesión con el paciente, pudiendo adjuntar archivos.</li>
    </ul>
</div>

<div class="help-section">
    <h3><i class="fas fa-blog"></i> Blog y contenido web</h3>
    <ul>
        <li>Crea artículos desde Contenido web > Blog. Usa el editor wysiwyg para dar formato al texto.</li>
        <li>Las categorías del blog se gestionan aparte en Contenido web > Categorías blog.</li>
        <li>Las preguntas frecuentes se gestionan en Contenido web > Preguntas frecuentes.</li>
    </ul>
</div>

<div class="help-section">
    <h3><i class="fas fa-palette"></i> Personalización</h3>
    <ul>
        <li>Usa el botón <i class="fas fa-palette"></i> del header para cambiar entre tema claro/oscuro y el color principal del dashboard.</li>
        <li>En Configuración > Imágenes puedes subir tus propias imágenes para la web pública.</li>
        <li>En Configuración > Frases públicas puedes personalizar todos los textos de la web.</li>
        <li>En Configuración > Redes sociales añade tus perfiles para que aparezcan en el footer.</li>
    </ul>
</div>

<div class="help-section">
    <h3><i class="fas fa-envelope"></i> Notificaciones por email</h3>
    <p>Para recibir notificaciones cuando un paciente reserve una cita:</p>
    <ol>
        <li>Ve a Configuración > Email y notificaciones.</li>
        <li>Configura los datos SMTP de Gmail (sigue el tutorial incluido).</li>
        <li>Activa las notificaciones y guarda.</li>
    </ol>
</div>

<div class="help-section">
    <h3><i class="fas fa-file-pdf"></i> Protección de datos</h3>
    <p>Desde Configuración > Protección de datos puedes editar la plantilla del documento de protección de datos. Luego, desde la ficha de cada paciente, puedes descargar el PDF ya relleno con sus datos.</p>
</div>

<div class="help-section" style="border-left:3px solid var(--accent);">
    <h3><i class="fas fa-lightbulb"></i> Consejos</h3>
    <ul>
        <li>Usa el buscador del header para encontrar rápidamente citas, pacientes, historias y artículos del blog.</li>
        <li>Revisa tu disponibilidad periódicamente y actualiza los periodos de vacaciones.</li>
        <li>Puedes desactivar secciones completas de la web (blog, FAQ, reservas) desde Configuración web.</li>
        <li>Si necesitas un diseño web personalizado, usa el enlace en la sección de temas visuales.</li>
    </ul>
</div>
@endsection
