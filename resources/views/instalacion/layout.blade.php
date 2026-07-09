<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalación - PsicoCMS</title>
    <link rel="stylesheet" href="{{ asset('themes/tema-base/css/reset.css') }}">
    <style>
        * { box-sizing: border-box; }
        html { font-size: 10px; }
        body {
            font-family: system-ui, -apple-system, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f0eb 0%, #e8ddd5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            margin: 0;
        }
        .instalacion-container {
            background: #fff;
            border-radius: 1.6rem;
            box-shadow: 0 2rem 6rem rgba(0,0,0,0.1);
            width: 100%;
            max-width: 72rem;
            overflow: hidden;
        }
        .instalacion-header {
            background: #976147;
            color: #fff;
            padding: 3rem 4rem;
            text-align: center;
        }
        .instalacion-header h1 {
            font-size: 2.4rem;
            margin: 0 0 0.5rem;
            font-weight: 700;
        }
        .instalacion-header p {
            font-size: 1.4rem;
            opacity: 0.9;
            margin: 0;
        }
        .instalacion-progress {
            display: flex;
            justify-content: center;
            gap: 0.8rem;
            padding: 2rem 4rem 0;
        }
        .instalacion-progress .step {
            width: 3.2rem;
            height: 3.2rem;
            border-radius: 50%;
            background: #e8ddd5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: 600;
            color: #976147;
            transition: all 0.3s;
            position: relative;
        }
        .instalacion-progress .step.active {
            background: #976147;
            color: #fff;
            transform: scale(1.1);
        }
        .instalacion-progress .step.completed {
            background: #5b4034;
            color: #fff;
        }
        .instalacion-progress .step + .step::before {
            content: '';
            position: absolute;
            left: -1.6rem;
            top: 50%;
            width: 1.2rem;
            height: 0.2rem;
            background: #e8ddd5;
        }
        .instalacion-progress .step.completed + .step::before,
        .instalacion-progress .step.active + .step::before {
            background: #976147;
        }
        .instalacion-body {
            padding: 3rem 4rem 2rem;
        }
        .instalacion-body h2 {
            font-size: 2rem;
            color: #3d2b24;
            margin: 0 0 0.5rem;
        }
        .instalacion-body .descripcion {
            font-size: 1.4rem;
            color: #7a6b63;
            margin: 0 0 2.5rem;
        }
        .form-group {
            margin-bottom: 1.8rem;
        }
        .form-group label {
            display: block;
            font-size: 1.3rem;
            font-weight: 600;
            color: #3d2b24;
            margin-bottom: 0.4rem;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 1rem 1.2rem;
            font-size: 1.4rem;
            border: 0.1rem solid #d4c8bf;
            border-radius: 0.8rem;
            background: #faf8f6;
            transition: border-color 0.3s;
            font-family: inherit;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #976147;
            box-shadow: 0 0 0 0.2rem rgba(151,97,71,0.2);
        }
        .form-group textarea {
            resize: vertical;
            min-height: 10rem;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 2.5rem;
        }
        .btn {
            padding: 1.2rem 2.8rem;
            font-size: 1.4rem;
            font-weight: 600;
            border: none;
            border-radius: 0.8rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #976147;
            color: #fff;
        }
        .btn-primary:hover {
            background: #7d4f39;
        }
        .btn-secondary {
            background: #e8ddd5;
            color: #5b4034;
        }
        .btn-secondary:hover {
            background: #d4c8bf;
        }
        .error {
            color: #d32f2f;
            font-size: 1.2rem;
            margin-top: 0.3rem;
        }
        .instalacion-footer {
            text-align: center;
            padding: 1.5rem 4rem;
            font-size: 1.2rem;
            color: #a6948a;
            border-top: 0.1rem solid #f0e8e2;
        }
        .input-group {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .input-group input {
            flex: 1;
        }
        .input-group .btn-remove {
            background: #f44336;
            color: #fff;
            border: none;
            border-radius: 0.6rem;
            padding: 0.6rem 1.2rem;
            cursor: pointer;
            font-size: 1.3rem;
        }
        .btn-add {
            background: transparent;
            border: 0.2rem dashed #d4c8bf;
            padding: 0.8rem 1.6rem;
            border-radius: 0.8rem;
            cursor: pointer;
            font-size: 1.3rem;
            color: #7a6b63;
            width: 100%;
            margin-top: 0.5rem;
        }
        .btn-add:hover {
            border-color: #976147;
            color: #976147;
        }
        .file-upload {
            border: 0.2rem dashed #d4c8bf;
            padding: 3rem;
            text-align: center;
            border-radius: 0.8rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .file-upload:hover {
            border-color: #976147;
            background: #faf8f6;
        }
        .file-upload input {
            display: none;
        }
        .file-upload .icono {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }
        .file-upload p {
            font-size: 1.3rem;
            color: #7a6b63;
            margin: 0;
        }
        .tema-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(18rem, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }
        .tema-card {
            border: 0.2rem solid #e8ddd5;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .tema-card:hover {
            border-color: #976147;
        }
        .tema-card.selected {
            border-color: #976147;
            background: #faf8f6;
            box-shadow: 0 0 0 0.2rem rgba(151,97,71,0.3);
        }
        .tema-card .preview {
            width: 100%;
            height: 10rem;
            border-radius: 0.6rem;
            margin-bottom: 1rem;
        }
        .tema-card h4 {
            font-size: 1.4rem;
            margin: 0 0 0.3rem;
            color: #3d2b24;
        }
        .tema-card p {
            font-size: 1.2rem;
            color: #7a6b63;
            margin: 0;
        }
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 0.8rem;
            font-size: 1.3rem;
            margin-bottom: 2rem;
        }
        .alert-info {
            background: #e3f2fd;
            color: #1565c0;
        }
        @media (max-width: 600px) {
            .instalacion-body { padding: 2rem; }
            .instalacion-header { padding: 2rem; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="instalacion-container">
        <div class="instalacion-header">
            <h1>PsicoCMS</h1>
            <p>Asistente de instalación</p>
        </div>

        <div class="instalacion-progress">
            @foreach ([
                1 => 'Datos',
                2 => 'Web',
                3 => 'Servicios',
                4 => 'Precios',
                5 => 'Ubicación',
                6 => 'Foto',
                7 => 'Tema'
            ] as $num => $label)
                <div class="step {{ $num == $pasoActual ? 'active' : '' }} {{ $num < $pasoActual ? 'completed' : '' }}" title="{{ $label }}">
                    {{ $num }}
                </div>
            @endforeach
        </div>

        <div class="instalacion-body">
            @if ($errors->any())
                <div class="alert alert-info">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('contenido')
        </div>

        <div class="instalacion-footer">
            PsicoCMS &mdash; Panel de administración para psicólogas
        </div>
    </div>
</body>
</html>
