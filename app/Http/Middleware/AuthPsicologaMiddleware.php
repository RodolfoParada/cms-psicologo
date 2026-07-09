<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthPsicologaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('psicologa')->check()) {
            return redirect('/acceso-psicologa');
        }

        return $next($request);
    }
}
