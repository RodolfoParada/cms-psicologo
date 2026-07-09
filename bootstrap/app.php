<?php
if (isset($_SERVER['HTTP_HOST']) && str_contains($_SERVER['HTTP_HOST'], 'onrender.com')) {
    // Forzar a Laravel a leer los valores frescos del entorno de Render en tiempo de ejecución
    $host = getenv('DB_HOST') ?: 'dpg-d97h4duq1p3s73epjig0-a';
    $db   = getenv('DB_DATABASE') ?: 'cms_db_77s9';
    $user = getenv('DB_USERNAME');
    $pass = getenv('DB_PASSWORD');

    config([
        'database.connections.pgsql.host'     => $host,
        'database.connections.pgsql.database' => $db,
        'database.connections.pgsql.username' => $user,
        'database.connections.pgsql.password' => $pass,
    ]);
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
