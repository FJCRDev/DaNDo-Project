<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DmMiddleware
{
    // Middleware para redirigir al player dashboard a cualquier persona que intente entrar sin ser dm
    public function handle($request, Closure $next){
        // Comprueba si el usuario está autenticado y si su rol es 'dm'
        if (Auth::check() && Auth::user()->role === 'dm') {
            return $next($request);
        }
        return redirect('/player/dashboard');
    }
}

