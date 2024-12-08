@extends('layouts.header')

@section('title', 'Dashboard del DM')

@section('content')
<link href="{{ asset('css/dmdashboard.css') }}" rel="stylesheet">
<h1>Dashboard del DM</h1>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<!-- Alerta para cuando se crea, modifica, o borra con éxito una sesión. -->

<!-- En el DM, para que sea una visualización distinta al dashbaord del jugador y porque su preview sería puramente
En portatil, debido a lo que ncesita un DM para poder llevar sus sesiones, es improbable que lo haga en movil

Igualmente, para ello, estaría la versión premium!
-->
<a href="{{ route('dm.sessions.create') }}" class="btn btn-primary mb-3">Crear Nueva Sesión de Rol</a>


<div class="dashboard-dm">
    <!-- Para cada sesión que nos viene por el controlador del dashbaord hacemos lo siguiente -->
@foreach ($sessions as $session)
    <div class="card mb-3">
        <div class="card-body">
            <!-- una tarjeta donde volcamos el título de la sesión -->
            <h5 class="card-title">{{ $session->title }}</h5>
            <!-- con su descripción -->
            <p class="card-text"><strong>Descripción:</strong> {{ $session->description }}</p>
            <!-- dia y fecha de la misma -->
            <p class="card-text"><strong>Fecha:</strong> {{ date('d-m-Y', strtotime($session->date)) }} a las {{ $session->time }}</p>

            <h6>Personajes en la sesión:</h6>
            <!-- Y una lista no ordenada de los personajes, para ello hay que hacer otro foreach dentro del mismo
            así que se tiene que llamar forelse, en este, pasamos por todos los personajes que tiene la sesión y decodificamos
            su JSON para poder manipular la información que nos interesa
            -->
            <ul>
            @forelse ($session->characters as $character)
                @php
                    $characterData = json_decode($character->valores, true);
                @endphp
                <li>
                    <!-- Poniendo los campos del array y una alternativa por si hubiese un problema y alguna información se hubiese perdido-->
                    Nombre: {{ $characterData['name'] ?? 'Aventurero sin nombre' }} | 
                    Nivel: {{ $characterData['level'] ?? 'Sin nivel' }} | 
                    Raza: {{ $characterData['race'] ?? 'Sin raza' }} | 
                    Clase: {{ $characterData['class'] ?? 'Sin clase' }}
                </li>
            @empty
            <!-- En el remoto caso de que no hubiera, porque por ejemplo, se borrase un jugador y su characterSheet desapareciera con el
            Ponemos una p Alternativa
            -->
                <p>No hay personajes en esta sesión.</p>
            @endforelse
            </ul>

            <!-- Botones para llamar a las vistas -->
            <a href="{{ route('dm.sessions.show', $session->id) }}" class="btn btn-info">Ver</a>
            <a href="{{ route('dm.sessions.edit', $session->id) }}" class="btn btn-warning">Editar</a>
  
            <!-- Y uno para, igual que el characterSheet, llamar al modal de confirmar eliminación -->
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSessionModal-{{ $session->id }}">Eliminar</button>
        </div>
    </div>






    <!-- Modal que funciona de forma similar al del characterSheet pidiendo confirmación, pudiendo cancelar o efectuar el destroy del controller -->

    <div class="modal fade" id="deleteSessionModal-{{ $session->id }}" tabindex="-1" aria-labelledby="deleteSessionModalLabel-{{ $session->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color: #384B70;" id="deleteSessionModalLabel-{{ $session->id }}">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div style="color: #384B70;" class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta sesión?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('dm.sessions.destroy', $session->id) }}" method="POST" style="display:inline;">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection
