@extends('layouts.header')

@section('title', 'Crear Sesión de Rol')

@section('content')
<h1>Crear Nueva Sesión de Rol</h1>

<!-- El formulario ejecutará el store cuando se finalice -->
<form action="{{ route('dm.sessions.store') }}" method="POST" id="session-form">
    @csrf
    <div class="mb-3">
        <label for="title" class="form-label">Título de la Sesión</label>
        <input type="text" name="title" id="title" class="form-control" style="width: 240px;" value="{{ old('title') }}" required>
        <!-- De la misma forma que con el create de characterSheet, hacemos que haya siempre un xxxx-error para notificar de los
        problemas personalizados a cada campo desde el front

        También, debido a que tanto el nombre de la sesión como la descripción pueden ser o complicados o largos, le ponemos un old(xxxx)
        para que si el formulario vuelve con errores, nos diga por que.
        -->
        <p class="text-danger" id="title-error"></p>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Descripción de la Sesión</label>
        <textarea name="description" id="description" class="form-control" rows="3" >{{ old('description') }}</textarea>
    </div>
    <div class="mb-3">
        <label for="date" class="form-label">Fecha de la Sesión</label>
        <input type="date" name="date" id="date" style="width: 240px;"  class="form-control" required>
        <p class="text-danger" id="date-error"></p>
    </div>
    
    <div class="mb-3">
        <!-- Horas de las sesiones, por poner unos ejemplos de cuando podrían empezar ponemos un abanico en intervalos de media hora desde las 4 hasta las 6 -->
        <label for="time" class="form-label">Hora de la Sesión</label>
        <select name="time" id="time" class="form-control" style="width: 240px;"  required>
            @foreach (['16:00:00', '16:30:00', '17:00:00', '17:30:00', '18:00:00'] as $time)
                <option value="{{ $time }}">{{ $time }}</option>
            @endforeach
        </select>
    </div>

    <!-- Un campo oculto donde guardaremos la id de los characters que tenemos añadidos a la sesion para cuando hagamos el submit, al DM le da igual
    asi que no tiene por que verlo.
    -->
    <input type="hidden" name="character_ids" id="character_ids">


  
    <!-- Primera lista, en esta recogeremos con JS todos los players con la funcion del controllador de getplayers, dandonos los 
    usuarios que tengan el rol player, de aquí solo nos interesará el nombre y el email, la forma en la que reconocerlos
    -->
    <h2>Lista de Jugadores</h2>
    <table class="table" id="players-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody id="players-list">
           <!-- aquí se irán volcando con appendchild los jugadores -->
        </tbody>
    </table>

    <!-- Después sera AQUÍ donde se volcarán los pjs de dicho jugador, una vez más cogiendo solo lo que le podría interesar a un DM
    No considero necesario poner todas las skills por ejemplo, así que con el nombre, nivel raza y clase sería suficiente, ponemos de extra un
    botón para "añadir" y que el dm tenga el feedback de que está añadiendo los pjs
    -->
    <h2>Fichas de Personaje</h2>
    <table class="table" id="character-sheets-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Nivel</th>
                <th>Raza</th>
                <th>Clase</th>
                <th>Añadir</th>
            </tr>
        </thead>
        <tbody id="character-sheets-list">
            <!-- como la lista anterios, aquí se irían volcando -->
        </tbody>
    </table>

    <!-- Una última tabla que se encarga de mostrarle al DM los que serán los characterSheets a añadir, y con una opcion para borrarlos
    En caso de que así se desee.
    -->
    <h2>Personajes Seleccionados para la Sesión</h2>
    <table class="table" id="selected-characters-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Nivel</th>
                <th>Raza</th>
                <th>Clase</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody id="selected-characters-list">
            <!-- lo mismo que los otros 2 -->
        </tbody>
    </table>
</form>

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<!-- Un Div para informar de los errores por back al intentar hacer el login pero fallar, ya que aquí para mostrar diferencias
    No los restringimos por front tanto, dejándote darle al botón de crear sesión aunque este mal
-->
<button type="button" class="btn btn-primary" id="create-session-btn">Crear Sesión</button>
<a href="{{ route('dm.dashboard') }}" class="btn btn-secondary">Cancelar</a>
@endsection



<!-- De la misma forma que el edit de player, cosas se pierden a la hora de traspasar el script a un .js, así que prefiero dejarlo
aquí y no perder tiempo al respecto.
-->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Con Set guardaremos varias instancias de lo que le vayamos a meter, en este caso los characters y no podrá meterse uno igual
    // aunque de todas formas eso no iba a pasar.
    const selectedCharacters = new Set();

    // Función que recogemso con un fetch de la funcion del controlador todos los usuarios que tengan role player.
    // Entonces recogemos toda esa información en un json y creamos una constante vacía para el campo de la tabla en el que iremos listando
    // los players, entonces, por cada uno vamos pasando por cada player y haciendo que vuelque en distintas columnas el id, el que pueda seleccionarse clickandole
    // y el nombre y email
    function fetchPlayers() {
        fetch("{{ route('dm.getPlayers') }}")
            .then(response => response.json())
            .then(players => {
                const playersList = document.getElementById('players-list');
                playersList.innerHTML = '';

                players.forEach(player => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-id', player.id);
                    row.classList.add('player-row');
                    row.style.cursor = 'pointer';
                    row.innerHTML = 
                    `
                        <td>${player.name}</td>
                        <td>${player.email}</td>
                    `;
                    playersList.appendChild(row);
                });
                // Entonces le añadimos a cada player-row que hemos creado un eventListener para que cuando se clicke una fila en concreto
                // nos lo marque como la activa y haga el fetch de characters sheets de ESE player Id.

                document.querySelectorAll('.player-row').forEach(row => {
                    row.addEventListener('click', function () {
                        document.querySelectorAll('.player-row').forEach(r => r.classList.remove('table-active'));
                        this.classList.add('table-active');
                        const playerId = this.getAttribute('data-id');
                        fetchCharacterSheets(playerId);
                    });
                });
            })
            .catch(error => console.error('Error al obtener jugadores:', error));
    }
    
    fetchPlayers();
    // Eh aquí el siguiente fetch, usando una lógica similar cogemos el controlador para coger las characterSheets con la Id que tenemos
    // obetenida previamente
    // creamos variable para guardar crear los datos y volcarlos con un appendchild en la tabla
    function fetchCharacterSheets(playerId) {
        fetch(`/dm/players/${playerId}/character-sheets`)
            .then(response => response.json())
            .then(sheets => {
                const sheetsList = document.getElementById('character-sheets-list');
                sheetsList.innerHTML = '';

                sheets.forEach(sheet => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${sheet.name}</td>
                        <td>${sheet.level}</td>
                        <td>${sheet.race}</td>
                        <td>${sheet.class}</td>
                        <td><button type="button" class="btn btn-success btn-sm add-character-btn" data-id="${sheet.id}" data-name="${sheet.name}" data-level="${sheet.level}" data-race="${sheet.race}" data-class="${sheet.class}">Añadir personaje</button></td>
                    `;
                    sheetsList.appendChild(row);
                });

                // Añadimos la función que hará que el botón cree una constante array con la ID del character y los datos a mostrar
                // De aquí lo mas vital es la id, el resto realmente es solo para una rápida visualización, pero esta ID es lo que nos
                // permitirá esa relación entre 3 con session_characters y que el admin podrá manipualr y eliminar cuando elimine usuarios o 
                // sesiones, porque será trackeable por dicha id.

                document.querySelectorAll('.add-character-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const characterData = {
                            id: this.getAttribute('data-id'),
                            name: this.getAttribute('data-name'),
                            level: this.getAttribute('data-level'),
                            race: this.getAttribute('data-race'),
                            class: this.getAttribute('data-class')
                        };
                        addCharacterToSession(characterData);
                    });
                });
            })
            .catch(error => console.error('Error al obtener fichas de personaje:', error));
    }

    // con esta función cogemos los datos que hemso extraido en el final de la anterior y los volcamos a la siguiente tabla
    function addCharacterToSession(character) {
        // cogemos el campo de la tabla vacío y a este le haremos los appendchild a continuacion
        const selectedCharactersList = document.getElementById('selected-characters-list');

        // Si por algún casual, el dm intentase añadir de nuevo el mismo character, que le salga una alerta informandole de ello
        if (selectedCharacters.has(character.id)) {
            alert(`El Personaje ${character.name} ya se encuentra en la lista de personajes.`);
            return;
        }

        // Volcamos todos los datos que queremos en la tabla junto al botón eliminar que tenga la id del character de la tabla para que se pueda eliminar en la siguiente
        const characterRow = document.createElement('tr');
        characterRow.setAttribute('data-id', character.id);
        characterRow.innerHTML = `
            <td>${character.name}</td>
            <td>${character.level}</td>
            <td>${character.race}</td>
            <td>${character.class}</td>
            <td><button type="button" class="btn btn-danger btn-sm remove-character-btn" data-id="${character.id}">Eliminar</button></td>
        `;

        // hacemso un appendchild a la tabla con el characterRow
        selectedCharactersList.appendChild(characterRow);
        selectedCharacters.add(character.id);
        // y actualizamos lo que será en si para la database de verdad los pjs que estarán en la sesión.
        updateSelectedCharactersInput();

        // al botón que hemos creado antes, le añadimos la funcion para que cuando sea clickado, el character con esa id, desaparezca su row
        // y actualice de nuevo el dato de input para la database
        characterRow.querySelector('.remove-character-btn').addEventListener('click', function () {
            selectedCharacters.delete(character.id);
            characterRow.remove();
            updateSelectedCharactersInput();
        });
    }

    // esta función se encarga de que hacer un JSON de los characters que irán relacionados con esta sesión
    function updateSelectedCharactersInput() {
    const characterIds = Array.from(selectedCharacters);
        document.getElementById('character_ids').value = JSON.stringify(characterIds);
    }


    // Al botón de crear la sesión le añadimos la función de que coja todos los datos del formulario junto a las Ids de los characters
    // si es que hay (aunque no podrá continuar sin ello pero bueno, verificaciones de seguridad)
    document.getElementById('create-session-btn').addEventListener('click', function (event) {
        // Log de datos antes del envío
        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        const date = document.getElementById('date').value;
        const characterIds = document.getElementById('character_ids').value;
        // Verificar si hay personajes seleccionados
        if (!characterIds || characterIds === '[]') {
            alert("Seleccione al menos un personaje para la sesión.");
            return;
        }

        // y ejecuta el submit
        document.getElementById('session-form').submit();
    });




    // guardamos constantes del contenido escrito en el formulario
    const form = document.getElementById('session-form');
    const titleInput = document.getElementById('title');
    const dateInput = document.getElementById('date');
    // y una constante de los errores que pueden haber, en este caso solo la fecha y el título.
    const errors = {
        title: document.getElementById('title-error'),
        date: document.getElementById('date-error')
    };

    // Los validamos, haciendo que por ejemplo el nombre de la sesión deba haber algo y que no sea muy grande
    function validateInput() {
        errors.title.textContent = '';
        errors.date.textContent = '';

        if (titleInput.value.length === 0 || titleInput.value.length > 25) {
            errors.title.textContent = 'El título debe tener entre 1 y 25 caracteres.';
        }


        // Y que la fecha es obligatoria
        if (!dateInput.value) {
            errors.date.textContent = 'La fecha de la sesión es obligatoria.';
        }
        // Una forma de verificar, con un create date nos guardamos una variable de la fecha del día de ejecucion del DOM
        // si la sesion puesta es anterior, que de error.
        const today = new Date().toISOString().split('T')[0];
        if (dateInput.value < today) {
            errors.date.textContent = 'La fecha de la sesión no puede ser anterior a la fecha actual.';
        }
    }

    // hacemos que estos campos tengan un event listener con cada input en el titulo o cambio en el date, ya que no escribimos sino clickamos
    // en el desplegabel y corremos la función una vez para que desde el principio muestre lo que le falta al DM
    titleInput.addEventListener('input', validateInput);
    dateInput.addEventListener('change', validateInput);
    validateInput();
});
</script>


<!-- simple estilo apra que tenga un sombreado llamativo y que el color del texto sea claro -->
<style>
table {
	width: 800px;
	border-collapse: collapse;
	overflow: hidden;
	box-shadow: 0 0 15px rgba(0,0,0,0.5);
}

th,td {
	padding: 15px;
	background-color: #FCFAEE;
	color: #fff;
}
</style>