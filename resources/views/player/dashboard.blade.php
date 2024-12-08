@extends('layouts.header')

@section('title', 'Dashboard del Jugador')

@section('content')
<link href="{{ asset('css/playerdashboard.css') }}" rel="stylesheet">
<h1 class="title">Dashboard del Jugador</h1>

    <!-- Alerta para informar cuando se completa una creacion de usuario, borrado o editado del mismo -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

<div class="container mt-6">
    <div class="row g-4">
        <!-- bucle en el que transitamos por todas las sheets de los datos que pasamos por el compact del controlador
        de PlayerController en el que recogemos todas las fichas que compartan el id del usuario loggeado en el momento
        -->
        @foreach ($characterSheets as $sheet)
            @php
                $values = json_decode($sheet->valores, true);
                $imagePath = isset($values['image']) && file_exists(public_path($values['image'])) ? asset($values['image']) : asset('images/default.png');
            @endphp
            <!-- para cada uno buscamos si tienen valor de imagen y esta existe exactamente igual en la ruta pública de imagenes para poder ponerla
            En caso de que así sea ponemos la imagen que nos viene, en caso de que no, ponemos la default que está en images junto a otras como los logos
            -->
            <div class="col-md-4">
                <div class="card h-100 bg-dark text-white" style="background-color: #507687 !important;">
                    <!-- La imagen con el php que hemos hecho antes y las dimensiones que conozco para los tokens que normalmente se usan en
                    tokenstamp, una página de crear tokens
                    -->
                    <img src="{{ $imagePath }}" class="card-img-top" alt="Imagen del Personaje" style="height: 410px; object-fit: cover;">
                    <!-- lo separamos con un div para que la imagen así esté separada en otra fila y tenga mayor tamaño y protagonismo -->
                    <div class="card-body d-flex flex-column">
                        <!-- Rellenamso con los valores que nos vienen por el foreach del php -->
                        <h5 class="card-title">{{ $values['name'] }}</h5>
                        <p class="card-text">Raza: {{ $values['race'] }}</p>
                        <p class="card-text">Clase: {{ $values['class'] }}</p>
                        <p class="card-text">Nivel: {{ $values['level'] }}</p>
                        <div class="mt-auto">
                            <!-- botones que nos llevan a las distintas vistas del characterSheet, abriendo una página
                            en concreto feedeandole la id que del sheet al que estamos clickando a la funcion
                            -->
                            <a href="{{ route('player.characterSheets.show', $sheet->id) }}" class="btn btn-info">Ver</a>
                            <a href="{{ route('player.characterSheets.edit', $sheet->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('player.characterSheets.destroy', $sheet->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $sheet->id }}">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


                <!-- Ventana emergente por bootstrap con el modal para formalizar el sistema de ventanas emergentes y dandole con el aria la función que no tiene en el momento-->
            <div class="modal fade" id="deleteModal-{{ $sheet->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $sheet->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel-{{ $sheet->id }}">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas eliminar esta ficha de personaje?
                    </div>
                    <div class="modal-footer">
                        <!-- botón de dismiss o de cancelar para poder ir para atrás -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <!-- botón que formaliza el llamado al destroy del characterSheet proporcionandole la ID -->
                        <form action="{{ route('player.characterSheets.destroy', $sheet->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>


<!--boton que funcionará para hacer aparecer el panel de las sesiones activas,
 en una posición fija para que el usuario siempre lo tenga a mano-->
<div class="sessions-toggle" style="position: fixed; bottom: 30px; left: 25px; z-index: 1050;">
    <button class="btn btn-info">Sesiones</button>
</div>

<!-- Botón para llamar a la función de crear una nueva CharacterSheet-->
<a href="{{ route('player.characterSheets.create') }}" class="btn-add-new">
    <button type="button" class="btn btn-primary btn-rounded" data-mdb-ripple-init>Añadir personaje</button>
</a>

<!--Panel lateral que originalmente está oculto gracias al JS-->
<div class="sessions-panel">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Sesiones Activas</h3>
                <ul class="list-group">
                    <!-- hacemos de nuevo una consulta php mas instanciada en la que buscamos 
                    los datos del sheet que tengan contenido en el json de valores y lo decodificamos, entonces
                    Hacemos OTRA consulta a la tabla de sessiones por la funcion sessions donde tenemos
                    establecida la relacion 1 a muchos con la tabla session_characters

                    De ahí entonces cogemos el data de character del nombre y la fecha y hora de le sesión del session
                    -->
                    @foreach ($characterSheets as $sheet)
                        @php
                            $characterData = json_decode($sheet->valores, true);
                        @endphp
                        @foreach ($sheet->sessions as $session)
                        <li class="list-group-item">
                            Sesion: {{ $session->title }}<br>
                            Personaje: {{ $characterData['name'] }} |<br> Fecha y hora: {{ date('d-m-Y', strtotime($session->date)) }} a las {{ $session->time }}
                        </li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/playerdashboard.js') }}"></script>
@endsection
