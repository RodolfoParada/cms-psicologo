@extends('dashboard.layout')

@section('titulo', 'Mi perfil')

@push('styles')
<style>
.perfil-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
@media (max-width: 768px) { .perfil-grid { grid-template-columns: 1fr; } }
.perfil-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}
.perfil-card h3 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.form-group { margin-bottom: 0.8rem; }
.form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.3rem; }
.form-group input { width: 100%; padding: 0.5rem 0.7rem; border: 1px solid var(--border); border-radius: 6px; font-size: 0.9rem; background: var(--input-bg); color: var(--text-primary); box-sizing: border-box; }
.avatar-actual {
    width: 100px; height: 100px; border-radius: 50%; object-fit: cover;
    border: 3px solid var(--border); margin-bottom: 0.5rem;
}
.avatar-placeholder {
    width: 100px; height: 100px; border-radius: 50%;
    background: var(--accent); color: #fff; font-size: 2rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 0.5rem;
}
</style>
@endpush

@section('contenido')
<div style="margin-bottom:1.5rem;">
    <h2 style="font-size:1.5rem;font-weight:700;color:var(--text-primary);margin:0;">
        <i class="fas fa-user-circle" style="margin-right:0.5rem;"></i>Mi perfil
    </h2>
    <p style="color:var(--text-secondary);font-size:0.9rem;margin:0.3rem 0 0;">
        Administra tus datos privados de acceso al panel.
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form method="POST" enctype="multipart/form-data">
    @csrf

    <div class="perfil-card">
        <h3><i class="fas fa-user"></i> Datos personales</h3>
        <div class="perfil-grid">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre', $psicologa->nombre) }}" required>
            </div>
            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" name="apellidos" value="{{ old('apellidos', $psicologa->apellidos) }}" required>
            </div>
            <div class="form-group">
                <label>Email (acceso)</label>
                <input type="email" name="email" value="{{ old('email', $psicologa->email) }}" required>
            </div>
            <div class="form-group">
                <label>Teléfono (acceso)</label>
                <input type="text" name="telefono" value="{{ old('telefono', $psicologa->telefono) }}" required>
            </div>
        </div>
    </div>

    <div class="perfil-card">
        <h3><i class="fas fa-camera"></i> Avatar</h3>
        @if($psicologa->foto)
            <img src="{{ asset('storage/' . $psicologa->foto) }}" alt="Avatar" class="avatar-actual">
        @else
            <div class="avatar-placeholder">
                {{ strtoupper(substr($psicologa->nombre, 0, 1) . substr($psicologa->apellidos, 0, 1)) }}
            </div>
        @endif
        <div class="form-group">
            <label>Cambiar avatar</label>
            <input type="file" name="avatar" accept="image/*">
        </div>
    </div>

    <div class="perfil-card">
        <h3><i class="fas fa-lock"></i> Cambiar contraseña</h3>
        <p style="font-size:0.85rem;color:var(--text-secondary);margin:0 0 1rem;">Deja en blanco si no quieres cambiar la contraseña.</p>
        <div class="perfil-grid">
            <div class="form-group">
                <label>Contraseña actual</label>
                <input type="password" name="password_actual">
            </div>
            <div class="form-group">
                <label>Nueva contraseña</label>
                <input type="password" name="password">
            </div>
            <div class="form-group">
                <label>Confirmar nueva contraseña</label>
                <input type="password" name="password_confirmation">
            </div>
        </div>
    </div>

    <div style="display:flex;gap:1rem;justify-content:flex-end;">
        <button type="submit" style="padding:0.6rem 1.5rem;border-radius:8px;background:var(--accent);color:#fff;border:none;font-weight:600;cursor:pointer;">
            <i class="fas fa-save"></i> Guardar perfil
        </button>
    </div>
</form>
@endsection
