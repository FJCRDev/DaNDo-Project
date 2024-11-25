<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    // Middleware para redirigir al player dashboard a cualquier persona que intente entrar sin ser admin
    public function handle($request, Closure $next){
        // Comprueba si el usuario está autenticado y si su rol es 'admin'
        if (auth()->check() && auth()->user()->role == 'admin') {
            return $next($request);
        }
        return redirect('/player/dashboard');
    }
}

