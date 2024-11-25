@extends('layouts.header')

@section('title', 'Detalles de la sesión')

@section('content')
<link href="{{ asset('css/dmshow.css') }}" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>

<div class="container">
    <h2>{{ $session->title }}</h2>

    <div class="row">
        <!-- Dividimos por un lado el calendario de Carbon chulo -->
        <div class="col-md-6">
            
            <div class="card">
                <div class="card-header">
                    <strong>Calendario</strong>
                </div>
                <div class="card-body">
                    <div class="calendar">
                        <!-- Al cual le hacemos con Carbon que se cree y tenga la forma y simplemente le ponemos como día inicial
                        la fecha de hoy

                        y como fecha final la de la session
                        -->
                        @php
                            \Carbon\Carbon::setLocale('es'); // Establece el locale a español
                            $sessionDate = \Carbon\Carbon::parse($session->date);
                            $today = \Carbon\Carbon::now();
                            $startDay = $sessionDate->copy()->startOfMonth()->startOfWeek();
                            $endDay = $sessionDate->copy()->endOfMonth()->endOfWeek();
                        @endphp

                        <!-- Formato español para no liarnos con los meses -->
                        <p><strong>{{ $sessionDate->isoFormat('MMMM YYYY') }}</strong></p> 
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Lun</th>
                                    <th>Mar</th>
                                    <th>Mié</th>
                                    <th>Jue</th>
                                    <th>Vie</th>
                                    <th>Sáb</th>
                                    <th>Dom</th>
                                </tr>
                            </thead>
                            <!-- Para marcar la fecha correcta, vamos pasando por td de 7 es decir una semana y verificamos con los
                             con los valores que tenemos atrás que el día no es igual al de la sesión o que es hoy
                            -->
                            <tbody>
                                @while ($startDay <= $endDay)
                                    <tr>
                                        @for ($i = 0; $i < 7; $i++)
                                            <td class="{{ $startDay->isSameDay($sessionDate) ? 'bg-danger text-white' : '' }} {{ $startDay->isToday() ? 'bg-primary text-white' : '' }}">
                                                {{ $startDay->day }}
                                            </td>
                                            @php $startDay->addDay(); @endphp
                                        @endfor
                                    </tr>
                                @endwhile
                            </tbody>
                        </table>
                    </div>

                    <!-- Una leyenda para que se entienda que es cada color -->
                    <div class="mt-2">
                        <p><span class="badge bg-primary text-dark">&nbsp;</span> Fecha de hoy</p>
                        <p><span class="badge bg-danger">&nbsp;</span> Fecha de la sesión</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- La otra columna para poner tanto la descripción de la sesión -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <strong>Descripción</strong>
                </div>
                <div class="card-body">
                    <p>{{ $session->description }}</p>
                </div>
            </div>
            <div class="mt-4">


       <!-- Como los personajes que hay en ella, para ello de nuevo hacemos el forelse como en el dashboard y sacamos los valores del pj -->
        <h6>Personajes en la sesión:</h6>
            <ul>
            @forelse ($session->characters as $character)
        @php
            $characterData = json_decode($character->valores, true);
        @endphp
        <p>
            Nombre: {{ $characterData['name'] ?? 'Aventurero sin nombre' }} | 
            Nivel: {{ $characterData['level'] ?? 'Sin nivel' }} | 
            Raza: {{ $characterData['race'] ?? 'Sin raza' }} | 
            Clase: {{ $characterData['class'] ?? 'Sin clase' }}
        </p>
    @empty
        <p>No hay personajes en esta sesión.</p>
    @endforelse
            </ul>
    </div>
        </div>
    </div>
    <!-- salir de la vista -->
    <a href="{{ route('dm.dashboard') }}" class="btn btn-primary mt-3">Volver al Dashboard</a>
</div>
@endsection

