<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;

// Función para los middlewares y permisos de rutas
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // Lo único que hice aquí fue añadir los alias de middleware de dm y admin
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'dm' => \App\Http\Middleware\DmMiddleware::class,
            'admin' => AdminMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
