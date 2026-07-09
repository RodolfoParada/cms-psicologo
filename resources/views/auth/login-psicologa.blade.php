<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso psicóloga - PsicoCMS</title>
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
        .login-container {
            background: #fff;
            border-radius: 1.6rem;
            box-shadow: 0 2rem 6rem rgba(0,0,0,0.1);
            width: 100%;
            max-width: 42rem;
            overflow: hidden;
        }
        .login-header {
            background: #976147;
            color: #fff;
            padding: 3rem 3rem 2rem;
            text-align: center;
        }
        .login-header h1 {
            font-size: 2.4rem;
            margin: 0 0 0.3rem;
            font-weight: 700;
        }
        .login-header p {
            font-size: 1.3rem;
            opacity: 0.9;
            margin: 0;
        }
        .login-body {
            padding: 3rem;
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
        .form-group input {
            width: 100%;
            padding: 1rem 1.2rem;
            font-size: 1.4rem;
            border: 0.1rem solid #d4c8bf;
            border-radius: 0.8rem;
            background: #faf8f6;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #976147;
            box-shadow: 0 0 0 0.2rem rgba(151,97,71,0.2);
        }
        .btn-login {
            width: 100%;
            padding: 1.2rem;
            font-size: 1.5rem;
            font-weight: 600;
            background: #976147;
            color: #fff;
            border: none;
            border-radius: 0.8rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-login:hover {
            background: #7d4f39;
        }
        .remember-group {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 1.8rem;
        }
        .remember-group input {
            width: 1.6rem;
            height: 1.6rem;
            accent-color: #976147;
        }
        .remember-group label {
            font-size: 1.3rem;
            color: #5b4034;
            cursor: pointer;
        }
        .error-msg {
            background: #fdecea;
            color: #d32f2f;
            padding: 1rem 1.5rem;
            border-radius: 0.8rem;
            font-size: 1.3rem;
            margin-bottom: 1.8rem;
            text-align: center;
        }
        .login-footer {
            text-align: center;
            padding: 1.5rem 3rem;
            font-size: 1.2rem;
            color: #a6948a;
            border-top: 0.1rem solid #f0e8e2;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>PsicoCMS</h1>
            <p>Panel de administración</p>
        </div>

        <div class="login-body">
            @if ($errors->any())
                <div class="error-msg">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.psicologa.post') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" value="{{ old('telefono') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="remember-group">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Recordar sesión</label>
                </div>

                <button type="submit" class="btn-login">Acceder al panel</button>
            </form>
        </div>

        <div class="login-footer">
            PsicoCMS &mdash; Panel de administración para psicólogas
        </div>
    </div>
</body>
</html>
