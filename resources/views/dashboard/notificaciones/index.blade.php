@extends('dashboard.layout')

@section('titulo', 'Email y notificaciones')

@push('styles')
<style>
.email-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}
.email-card h3 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.email-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
@media (max-width: 768px) { .email-grid { grid-template-columns: 1fr; } }
.email-grid .full { grid-column: 1 / -1; }
.form-group { margin-bottom: 0.8rem; }
.form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.3rem; }
.form-group input, .form-group select {
    width: 100%; padding: 0.5rem 0.7rem; border: 1px solid var(--border);
    border-radius: 6px; font-size: 0.9rem; background: var(--input-bg); color: var(--text-primary); box-sizing: border-box;
}
.tutorial-box {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    font-size: 0.85rem;
    line-height: 1.6;
    color: var(--text-primary);
}
.tutorial-box ol { margin: 0.5rem 0; padding-left: 1.2rem; }
.tutorial-box li { margin-bottom: 0.3rem; }
</style>
@endpush

@section('contenido')
<div style="margin-bottom:1.5rem;">
    <h2 style="font-size:1.5rem;font-weight:700;color:var(--text-primary);margin:0;">
        <i class="fas fa-envelope" style="margin-right:0.5rem;"></i>Email y notificaciones
    </h2>
    <p style="color:var(--text-secondary);font-size:0.9rem;margin:0.3rem 0 0;">
        Configura el envío de notificaciones cuando un paciente reserve una cita.
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="tutorial-box">
    <strong><i class="fas fa-info-circle"></i> ¿Cómo configurar Gmail?</strong>
    <ol>
        <li>Ve a tu cuenta de Google y activa la <strong>verificación en dos pasos</strong> si no la tienes.</li>
        <li>Busca "Contraseñas de aplicación" en la configuración de Google.</li>
        <li>Crea una contraseña de aplicación para "Correo".</li>
        <li>Copia la contraseña generada (16 caracteres) y pégala en el campo "Contraseña SMTP".</li>
        <li>Guarda la configuración y activa las notificaciones.</li>
    </ol>
</div>

<form method="POST">
    @csrf
    <div class="email-card">
        <h3><i class="fas fa-bell"></i> Notificaciones de nuevas citas</h3>
        <div class="form-group">
            <label>Email para recibir notificaciones</label>
            <input type="email" name="email_notificaciones" value="{{ old('email_notificaciones', $config->email_notificaciones) }}" placeholder="tucorreo@gmail.com">
        </div>
    </div>

    <div class="email-card">
        <h3><i class="fas fa-server"></i> Configuración SMTP (Gmail)</h3>
        <div class="email-grid">
            <div class="form-group">
                <label>Host SMTP</label>
                <input type="text" name="email_smtp_host" value="{{ old('email_smtp_host', $config->email_smtp_host ?? 'smtp.gmail.com') }}" placeholder="smtp.gmail.com">
            </div>
            <div class="form-group">
                <label>Puerto</label>
                <input type="text" name="email_smtp_port" value="{{ old('email_smtp_port', $config->email_smtp_port ?? '587') }}" placeholder="587">
            </div>
            <div class="form-group">
                <label>Usuario (tu email)</label>
                <input type="text" name="email_smtp_user" value="{{ old('email_smtp_user', $config->email_smtp_user) }}" placeholder="tucorreo@gmail.com">
            </div>
            <div class="form-group">
                <label>Contraseña de aplicación</label>
                <input type="password" name="email_smtp_pass" value="{{ old('email_smtp_pass') }}" placeholder="Dejar en blanco para mantener">
            </div>
            <div class="form-group">
                <label>Encriptación</label>
                <select name="email_smtp_encryption">
                    <option value="tls" {{ ($config->email_smtp_encryption ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ ($config->email_smtp_encryption ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                </select>
            </div>
            <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:0.3rem;">
                <div class="toggle-wrap" style="display:flex;align-items:center;gap:0.8rem;">
                    <input type="checkbox" name="notificaciones_activas" class="toggle" id="notif_activas" value="1" {{ $config->notificaciones_activas ? 'checked' : '' }}>
                    <label for="notif_activas" style="margin:0;cursor:pointer;font-weight:600;">Notificaciones activas</label>
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:1rem;justify-content:flex-end;">
        <button type="submit" style="padding:0.6rem 1.5rem;border-radius:8px;background:var(--accent);color:#fff;border:none;font-weight:600;cursor:pointer;">
            <i class="fas fa-save"></i> Guardar configuración
        </button>
    </div>
</form>
@endsection
