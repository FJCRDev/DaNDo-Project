@extends('layouts.header')
@section('title', 'Iniciar Sesión')


@section('content')
<!-- llamamos el CSS para cambiar los pequeños detalles de esta parte -->
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
<h1>Iniciar Sesión</h1>
<!-- formulario para iniciar sesión -->
<form action="{{ route('login') }}" method="POST">
    <!-- necesitamos poner el @csrf para que laravel gestione a su manera las sseguridades de sesiones y nosotros olvidarnos -->
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Correo Electrónico</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
    <a href="/" class="btn btn-secondary">Volver</a>
    <!-- Honestamente no se que más comentar aqui, tipico login con email y pass y un botón para submit el form o para volver al / -->
</form>
@endsection
