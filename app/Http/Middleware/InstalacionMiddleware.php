<?php

namespace App\Http\Middleware;

use App\Models\Psicologa;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InstalacionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Psicologa::count() > 0) {
            return redirect('/acceso-psicologa');
        }

        return $next($request);
    }
}
