<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - PsicoCMS</title>
</head>
<body>
    <h1>Dashboard PsicoCMS</h1>
    <p>Bienvenida, {{ Auth::guard('psicologa')->user()->nombre_completo }}</p>
    <form method="POST" action="{{ route('logout.psicologa') }}">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>
</body>
</html>
