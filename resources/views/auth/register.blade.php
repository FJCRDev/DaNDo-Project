@extends('layouts.header')
@section('title', 'Registro')

@section('content')
<!-- Formulario para crear un usuario -->
<div class="container">
    <h2>Registrar Nuevo Usuario</h2>
    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nombre:</label>
            <input type="text" class="form-control" id="name" name="name" required>
            <!-- aquí dejamos como en todos un div con una clase de bootstrap y un id para tratar con los scripts luego
            de forma que también defendamos los formularios por la parte de front-end
            -->
            <div class="invalid-feedback" id="nameError"></div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico:</label>
            <input type="email" class="form-control" id="email" name="email" required>

            <div class="invalid-feedback" id="emailError"></div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" required>

            <div class="invalid-feedback" id="passwordError"></div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña:</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>

            <div class="invalid-feedback" id="passwordConfirmationError"></div>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Rol:</label>
            <select class="form-select" id="role" name="role">
                <!-- aseguramos que las únicas opciones sean o player o dm como tenemso en el enum de la base de datos
                (en la base de datos también está admin pero obivamente no queremos que puedan crearse admins)
                ese se metería unicamente con un insert por la base de datos
                -->
                <option value="player">Jugador</option>
                <option value="dm">Dungeon Master</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" disabled id="submitBtn">Registrar</button>
        <a href="/" class="btn btn-secondary">Volver</a>
    </form>
</div>

<!-- cualquier error que de a la hora de insertar el formulario y que el backend encuentre y se nos haya escapado
    por front, como por ejemplo que el correo ya exista, saldrá aquí, de nuevo una clase bootstrap
-->
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<script src="{{ asset('js/register.js') }}"></script>


@endsection
