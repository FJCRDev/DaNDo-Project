@extends('layouts.header')
@section('title', 'Editar Sesión de Rol')

@section('content')
<h1>Editar Sesión de Rol</h1>

<!-- formulario que posee los datos de la sesion que coincida con la id del que hemos clickado, siendo invocado por la funcion del controlador
update
-->
<form action="{{ route('dm.sessions.update', $session->id) }}" method="POST" id="session-form">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="title" class="form-label">Título de la Sesión</label>
        <input type="text" name="title" id="title" class="form-control" style="width: 240px; background-color: #384B70  ;" value="{{ $session->title }}" required readonly>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Descripción de la Sesión</label>
        <textarea name="description" id="description" class="form-control" rows="3">{{ $session->description }}</textarea>
    </div>
    <div class="mb-3">
        <label for="date" class="form-label">Fecha de la Sesión</label>
        @php
        $date = \Carbon\Carbon::parse($session->date);
        @endphp

        <input type="date" name="date" id="date" class="form-control" value="{{ $date->format('Y-m-d') }}" required>

    </div>
    <!-- en todos, ponemos que el value de base sea el que nos viene por la variable session, ya que así ya están rellenados los campos -->

    <!-- para el de la hora, creamos de nuevo las opciones de hora escogidas y ponemos de valor el que nos viene. -->
    <div class="mb-3">
        <label for="time" class="form-label">Hora de la Sesión</label>
        <select name="time" id="time" class="form-control" required>
            @foreach (['16:00:00', '16:30:00', '17:00:00', '17:30:00', '18:00:00'] as $time)
                <option value="{{ $time }}" {{ $session->time == $time ? 'selected' : '' }}>{{ $time }}</option>
            @endforeach
        </select>
    </div>

    <!-- div para informar de los errores cuando intentas actualizar con algo mal, como por ejemplo que haya una sesion ese día a a esa hora -->
    @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
    @endif
    <button type="submit" class="btn btn-primary">Actualizar Sesión</button>
    <a href="{{ route('dm.dashboard') }}" class="btn btn-secondary">Cancelar</a>
</form>


@endsection
