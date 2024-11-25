@extends('layouts.header')

@section('title', 'Editar Ficha de Personaje')

@section('content')
<link href="{{ asset('css/playeredit.css') }}" rel="stylesheet">
<h1 class="title">Editar Ficha de Personaje</h1>
<h4>Información del Personaje</h4>
<form action="{{ route('player.characterSheets.update', $characterSheet->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Como vemos en el input ponemos ya de value los datos que tenemos de la characterSheet que hemos seleccionado y que nos vienen por la id de la funcion -->
    <div class="mb-3">
        <label for="name" class="form-label">Nombre del Personaje</label>
        <input type="text" name="name" id="name" style="width: 240px;" class="form-control" required value="{{ $data['name'] }}">
    </div>

    <div class="mb-3">
        <label for="race" class="form-label">Raza</label>
        <select name="race" id="race" style="width: 240px;" class="form-control" required>
            <option value="">Selecciona una raza</option>
                <!-- No hace falta que pongamos nada, ya que esto lo rellenamos con la API por JS -->
        </select>
    </div>

    <div class="mb-3">
        <label for="class" class="form-label">Clase</label>
        <select name="class" id="class" style="width: 240px;" class="form-control" required>
            <option value="">Selecciona una clase</option>
               <!-- No hace falta que pongamos nada, ya que esto lo rellenamos con la API por JS -->
        </select>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="level" class="form-label">Nivel</label>
                <input type="number" name="level" id="level" class="form-control text-center" min="1" max="20" required value="{{ $data['level'] }}" style="width: 80px;">
            </div>
            <!-- nos aseguramos que tenga readonly para que no puedan cambiarlo, pero que visualmente pareza un dato más que tener en cuenta
            Y puedan verlo modificarse a tiempo real gracias al JS
            -->
            <div class="col-md-4 mb-3">
                <label for="proficiency_bonus" class="form-label">Bono de Competencia</label>
                <input type="text" name="proficiency_bonus" id="proficiency_bonus" class="form-control text-center" readonly style="width: 80px; background-color: #384B70  ;" value="{{ $data['proficiency_bonus'] }}">
            </div>
            <div class="col-md-4 mb-3">
                <label for="speed" class="form-label">Velocidad</label>
                <input type="number" name="speed" id="speed" class="form-control text-center" readonly style="width: 80px; background-color: #384B70;" value="{{ $data['speed'] }}">
            </div>
        </div>
    </div>

    <h4>Atributos</h4>
    <div class="container">
        <div class="row">
            <!-- ya que por la buena praxis lo tenemos guardado en inglés, aquí hacemos un for each para poner cada atributo traducido
            pero con los valores originales
            -->
            @foreach(['strength' => 'Fuerza', 'dexterity' => 'Destreza', 'constitution' => 'Constitución', 'intelligence' => 'Inteligencia', 'wisdom' => 'Sabiduría', 'charisma' => 'Carisma'] as $key => $label)
                <div class="col-md-2 mb-3">
                    <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                    <input type="number" name="{{ $key }}" id="{{ $key }}" class="form-control attribute-input text-center" min="1" max="20" required style="width: 60px;" value="{{ $data['attributes'][$key] }}">
                    <small id="{{ $key }}-bonus" class="form-text text-muted">Bonificador: 0</small>
                </div>
            @endforeach

        </div>
    </div>

    <h4>Habilidades</h4>
    <div class="container">
        <div class="row">
            <!-- La misma lógica que con el show pero con checkboxes ya que pueden añadirse más o quitar competencias -->
            @php
                $skillsByAttribute = [
                    'Fuerza' => ['Atletismo'],
                    'Destreza' => ['Acrobacias', 'Juego de Manos', 'Sigilo'],
                    'Inteligencia' => ['Arcana', 'Historia', 'Investigación', 'Naturaleza', 'Religión'],
                    'Sabiduría' => ['Trato Animal', 'Perspicacia', 'Medicina', 'Percepción', 'Supervivencia'],
                    'Carisma' => ['Engaño', 'Intimidación', 'Interpretación', 'Persuasión']
                ];
            @endphp
            @foreach ($skillsByAttribute as $attribute => $skills)
                <div class="col-md-4">
                    <strong>{{ $attribute }}</strong>
                    @foreach ($skills as $skill)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="skills[]" value="{{ $skill }}" id="{{ $skill }}"
                                   @if(in_array($skill, $data['skills'])) checked @endif>
                            <label class="form-check-label" for="{{ $skill }}">{{ $skill }}</label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <!-- botón para aplicar los cambios -->
    <button type="submit" class="btn btn-primary fixed-bottom-right">Guardar Ficha</button>
    <a href="{{ route('player.dashboard') }}" class="btn btn-secondary fixed-bottom-left">Cancelar</a>
</form>










<!-- No se por qué no me coge bien el archivo JS así que tengo que dejar aquí puesto el script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // constante que nos calcula el bono de competencia segun el nivel del personaje, a diferencia del bono, no vi ninguna
        // lógica al cálculo y creo que es algo más mecánico por ajustes de balance asi que tuve que hacer una serie de ifs
        // según el rango de nivel del pj.
        const calculateProficiencyBonus = (level) => {
            if (level >= 1 && level <= 4) return 2;
            if (level >= 5 && level <= 8) return 3;
            if (level >= 9 && level <= 12) return 4;
            if (level >= 13 && level <= 16) return 5;
            if (level >= 17 && level <= 20) return 6;
            return 0;
        };

        // aquí sin embargo era más fácil, primero nos aseguramos que si de alguna forma el usuario es capaz de poner un valor
        // que no ea un numero o vacío, que para nosotros sea un 0.

        // Si es algo, le restamos 10 al número y entonces lo dividimos entre dos, de la misma forma que en el ejemplo de show
        //  de Esta forma por ejemplo un 18 - 10 = 8 / 2 = 4. Un +4

        const calculateBonus = (value) => {
            if (isNaN(value) || value === "") return 0;
            return Math.floor((value - 10) / 2);
        };

        let totalProficiencies = 0;
        // Cogemos del documento el campo del nivel (y si no hay, que sea 1). le pasamos la funcion de antes para calcular
        // el bono y el resultado se lo metemos al campo de proficiencia con un +, porque siempre será positivo.
        const updateProficiencyBonus = () => {
            const level = parseInt(document.getElementById('level').value) || 1;
            const proficiencyBonus = calculateProficiencyBonus(level);
            document.getElementById('proficiency_bonus').value = `+${proficiencyBonus}`;
        };

        // Cogemos del documento el campo del atributo y por cada uno, ya que son varios le pasamos la función de calcular el bono
        // según sea el id de ese foreach hacemos el xxxx-bonus de id y le ponemos como contexto el resultado del calculo,
        // aquí no ponemos el + porque se da por sentando Y porque en caso de que el calculo mátematico sea negativo, saldria como
        // +-2 y queda feo.
        const updateAttributeBonuses = () => {
            document.querySelectorAll('.attribute-input').forEach(input => {
                const bonus = calculateBonus(parseInt(input.value) || 0);
                document.getElementById(`${input.id}-bonus`).textContent = `Bonificador: ${bonus}`;
            });
        };

        // Fetch donde cogemos, por la api de dnd5e todas las razas que hay y las ponemos como datos por appendchild al selector
        fetch('https://www.dnd5eapi.co/api/races')
            .then(response => response.json())
            .then(data => {
                const raceSelect = document.getElementById('race');
                data.results.forEach(race => {
                    const option = document.createElement('option');
                    option.value = race.index;
                    option.textContent = race.name;
                    raceSelect.appendChild(option);
                });
                // de todas formas, le ponemos de value de primeras que salga de primeras el que ya es el charactersheet.
                raceSelect.value = "{{ $data['race'] }}";
        });

        // lo mismo con las clases
        fetch('https://www.dnd5eapi.co/api/classes')
        .then(response => response.json())
        .then(data => {
            const classSelect = document.getElementById('class');
            data.results.forEach(cls => {
                const option = document.createElement('option');
                option.value = cls.index;
                option.textContent = cls.name;
                classSelect.appendChild(option);
            });
            classSelect.value = "{{ $data['class'] }}";
        });


        // 2 event listeners ara updatear las cosas conforme se han inputs, por una parte que cada vez que un atributo
        // se actualice te haga el cálculo
        document.querySelectorAll('.attribute-input').forEach(input => {
            input.addEventListener('input', updateAttributeBonuses);
        });

        // que cuando se suba o baje el nivel ,te haga el cálculo
    document.getElementById('level').addEventListener('input', updateProficiencyBonus);

    //llamamos los updates desde el principio de la carga de la página para asegurarnos que los bonos salgan nada mas empezar
    updateProficiencyBonus();
    updateAttributeBonuses();
});
</script>
@endsection
