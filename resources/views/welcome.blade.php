<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DaNDo - Bienvenido</title>
    <!-- Vuelco de links para visuales y para el icono de referencia de la página -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/logoNoVector.png') }}" type="image/x-icon">
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Vuelco de alerta cuando un usuario se crea con éxito -->
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>    
        @endif
    </div>

    <!-- Primera parte de la vista, donde ponemos con unos spans para poner diferentes colores el patrón característico de DaNDo
    a su vez creamos los botones que nos llevarán al inicio o al registro. Las clases son las necesarias para que bootstrap pueda funcionar y por eso su nombre raro
    -->
    <div class="container text-center">
        <h1 class="logo">Bienvenido a <span class="uppercase">D</span>a<span class="uppercase">ND</span>o <img src="{{ asset('images/logoNoVector.png') }}" alt="Logo de DaNDo" style="height: 50px;"></h1>
        <p>Gestor personalizado orientado al sistema de rol DnD 5e.</p>
        <div class="animation"></div>
        <a href="{{ route('login') }}" class="btn btn-primary m-2">Iniciar Sesión</a>
        <a href="{{ route('register') }}" class="btn btn-secondary m-2">Registrarse</a>

        <!-- Primera tabla, donde se visualiza el resumen de aptitud para los DMs-->
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2">¿Qué implica ser un Dungeon Master?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><img src="images/dm.webp" alt="Dungeon Master" class="img-fluid"></td>
                            <td>
                                <ul>
                                    <li>Crear y organizar trepidantes sesiones de rol.</li>
                                    <li>Gestionar que jugadores entrarán en las mismas.</li>
                                    <li>Concertar fechas para reservar el lugar.</li>
                                    <li>Ser el guionista de las futuras historias a forjar en tu zona local.</li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <!-- Segunda tabla, esta explicamos el resumen de aptitud para los Jugadores-->
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2">¿Qué es ser un Jugador?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><img src="images/player.webp" alt="Jugador" class="img-fluid"></td>
                            <td>
                                <ul>
                                    <li>Crear con una guía fascinantes personajes de rol.</li>
                                    <li>Abrirte a ser seleccionado entre los profesionales DMs de tus locales de ocio y eventos.</li>
                                    <li>Saber con facilidad cuando será la próxima sesión de uno de tus personajes.</li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
