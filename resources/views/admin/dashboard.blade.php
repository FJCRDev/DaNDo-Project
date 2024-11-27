@extends('layouts.header')

@section('title', 'Dashboard de Administración')

@section('content')

<h1>Dashboard de Administración</h1>

<!-- Creamos solo 2 tablas, una para la lista de todos los usuarios y otra para la de todas las sesiones
    Una tabla extra, para gestionar a los CharacterSheets sería evaluado en la versión premium.
-->
<h2>Usuarios</h2>
<table class="table">
    <thead>
        <!-- como admin te interesa saber el número de ID, para en caso hipotético de que esto fuese a mayor escala
        saber gestionar los IDs locales de tus usuarios etc etc, como es una plantilla de prueba pensado para la gestión
        de un solo local o un solo evento, no funcionará tan grande pero igualmente lo colocamos para dar mas impresión de
        informacion de base de datos.
        -->
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>¿Eliminar usuario?</th>
        </tr>
    </thead>
    <tbody>
        <!-- por medio de las formas de php hacemos una consulta que ciclamos todos los users que hay y en cada uno creamos
        en la tabla una fila y rellenamos los campos de la columna en orden
        -->
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
            <td>
                <!-- añadiendo al final un valor para eliminar dicho usuario llamando a la funcion de destruir USUARIO. -->
            <form action="{{ route('admin.destroyUser', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Confirmar</button>
            </form>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>


<h2>Character Sheets</h2>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre del Personaje</th>
            <th>Cliente ID</th>
            <th>Detalles</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($charactersSheet as $character)
        <tr>
            <td>{{ $character->id }}</td>
            <td>{{ json_decode($character->valores)->name ?? 'No Name' }}</td>
            <td>{{ $character->client_id }}</td>
            <td>
                <!-- añadiendo al final un valor para eliminar dicho usuario llamando a la funcion de destruir USUARIO. -->
            <form action="{{ route('admin.destroyCharacterSheet', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Confirmar</button>
            </form>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>






<!-- La misma lógica la copiamos y pegamos para crear las sesiones, aquí especificando las IDs de la sesion y la del DM
    Que la gestiona.
-->
<h2>Sesiones</h2>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>ID DM</th>
            <th>Título</th>
            <th>Fecha</th>
            <th>Hora</th>

            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <!-- de nuevo, con un for each de php pasamos todas las sesiones y recogemos lo que no interesa mostrarle al admin-->
        @foreach ($sessions as $session)
        <tr>
            <td>{{ $session->id }}</td>
            <td>{{ $session->dm_id }}</td> 
            <td>{{ $session->description }}</td>
            <td>{{ $session->date }}</td>
            <td>{{ $session->time }}</td>

            <td>
                <!-- de nuevo el botón para llamar la funcion de destruir SESION -->
                <form action="{{ route('admin.destroySession', $session->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection
<!-- Honestamente solo para esto no quería hacer otro archivo css -->
<style>
th,td {
	color: #FCFAEE;
}
</style>
