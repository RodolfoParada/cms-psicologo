<?php
if (isset($_SERVER['HTTP_HOST']) && str_contains($_SERVER['HTTP_HOST'], 'onrender.com')) {
    $_ENV['DB_CONNECTION'] = 'pgsql';
    $_ENV['DB_HOST']       = 'dpg-d97h4duq1p3s73epjig0-a';
    $_ENV['DB_PORT']       = '5432';
    $_ENV['DB_DATABASE']   = 'cms_db_77s9';
    $_ENV['DB_USERNAME']   = 'cms_db_77s9_user'; // <-- Escriba aquí su usuario real de Render
    $_ENV['DB_PASSWORD']   = 'Vj9rexGBdXYcuq0DYHaEOCqDHa3p0sm8'; // <-- Fijo aquí
    
    // Duplicar en $_SERVER para evitar fugas
    $_SERVER['DB_CONNECTION'] = 'pgsql';
    $_SERVER['DB_HOST']       = $_ENV['DB_HOST'];
    $_SERVER['DB_PORT']       = '5432';
    $_SERVER['DB_DATABASE']   = $_ENV['DB_DATABASE'];
    $_SERVER['DB_USERNAME']   = $_ENV['DB_USERNAME'];
    $_SERVER['DB_PASSWORD']   = $_ENV['DB_PASSWORD'];

    // === INTERCEPTOR MÁGICO PARA EJECUTAR LA MIGRACIÓN ===
    if (isset($_GET['migrar']) && $_GET['migrar'] == '1') {
        try {
            // Inicializar la consola de Laravel manualmente en este punto temprano
           
            $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
            $status = $kernel->handle(
                new \Symfony\Component\Console\Input\StringInput('migrate --force'),
                new \Symfony\Component\Console\Output\BufferedOutput()
            );
            echo "¡Estructura de base de datos creada con éxito en PostgreSQL, mi señor! Código de salida: " . $status;
            exit;
        } catch (\Exception $e) {
            echo "Error ejecutando la migración: " . $e->getMessage();
            exit;
        }
    }
    // =====================================================
}



use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'instalacion' => \App\Http\Middleware\InstalacionMiddleware::class,
            'auth.psicologa' => \App\Http\Middleware\AuthPsicologaMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
