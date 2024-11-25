<!DOCTYPE html>
<!-- Aquí haremos toda la lógica del header ya que el botón de cierre de sesión el logo y el indicativo del usuario lo quiero
en todos lados (salvo el welcome)
-->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Todo el css que se usara, tanto bootstrap como el nuestro para retocar -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <!-- el icono de la pestaña -->
    <link rel="icon" href="{{ asset('images/logoNoVector.png') }}" type="image/x-icon">
</head>

<body>
    <header class="d-flex justify-content-between align-items-center p-3 bg-light border-bottom">
        <h1 class="h4 mb-0">
            <!-- Estructura del "logo" con el título con su forma característica al ladito -->
            <img src="{{ asset('images/logoSiVector.svg') }}" alt="Logo de DaNDo" style="height: 50px;">
            <!-- le ponemos unos spans con clase para luego ponerles un color a ellos en particular -->
            <span class="logo"><span class="uppercase">D</span>a<span class="uppercase">ND</span>o</span> 
        </h1>
        <!-- con php cogemos el username del usuario registrado. Así siempre saldrá el nombre del jugador o DM registrado. -->
        <div class="d-flex align-items-center">
            @auth
                <span class="me-3">Saludos intrépido/a, {{ auth()->user()->name }} ¿Preparado para crear historia?</span>
                <form action="{{ route('logout') }}" method="POST" class="mb-0">
                    <!-- un cerrar sesión con el CSRF que usa laravel para lo de las sesiones -->
                    @csrf
                    <button type="submit" id="close" class="btn btn-outline-danger btn-sm">Cerrar sesión</button>
                </form>
            @endauth
        </div>
    </header>

    <!-- Aqui, despues del heder, meteremos en este div de contenedor TODO lo que sería ESA página. Asi nos aseguramos que está
    separado de todo el código el header y el cuerpo y queda como debería
    -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- js de bootstrap para si hiciera falta -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<style>