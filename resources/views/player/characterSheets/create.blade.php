@extends('layouts.header')

@section('title', 'Crear Ficha de Personaje')

@section('content')
<link href="{{ asset('css/playercreate.css') }}" rel="stylesheet">

<h1>Crear Nueva Ficha de Personaje</h1>
<h4>Información del Personaje</h4>
<form action="{{ route('player.characterSheets.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">

    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Nombre del Personaje</label>
        <input type="text" name="name" id="name" style="width: 240px;"class="form-control" required>
        <p class="text-danger" id="name-error"></p>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Imagen del Personaje</label>
        <input type="file" name="image" id="image" style="width: 400px;" class="form-control" accept="image/*">
        <small class="form-text text-muted">Sube una imagen de hasta 5 MB.</small>
    </div>

    <div class="mb-3">
        <label for="race" class="form-label">Raza</label>
        <select name="race" id="race" class="form-control"style="width: 240px;" required>
            <option value="">Selecciona una raza</option>
            <!-- no hace falta rellenar con nada porque nos saldrán gracia al JS con el fetch de la API -->
        </select>
        <p class="text-danger" id="race-error"></p>
    </div>

    <div class="mb-3">
        <label for="class" class="form-label">Clase</label>
        <select name="class" id="class" class="form-control"style="width: 240px;" required>
            <option value="">Selecciona una clase</option>
              <!-- no hace falta rellenar con nada porque nos saldrán gracia al JS con el fetch de la API -->
        </select>
        <p class="text-danger" id="class-error"></p>
    </div>

    <!-- Como vemos siempre ponemos un xxx-error para feedear por JS las notificacioens de error de cada campo cuando están mal -->
    

<div class="container">
    <div class="row">
  
        <div class="col-md-4 mb-3">
            <label for="level" class="form-label">Nivel</label>
            <input type="number" name="level" id="level" class="form-control text-center" min="1" max="20" required style="width: 80px;">
            <p class="text-danger" id="level-error"></p>
        </div>

    <!-- Nos aseguramos que estos dos sean readonly, para que de la misma manera que con el edit no puedan cambiarlo pero se vea bonito en el formulario -->
        <div class="col-md-4 mb-3">
            <label for="proficiency_bonus" class="form-label">Bono de Competencia</label>
            <input type="text" id="proficiency_bonus" name="proficiency_bonus"  class="form-control text-center" readonly style="width: 80px; background-color: #384B70">
        </div>

     
        <div class="col-md-4 mb-3">
            <label for="speed" class="form-label">Velocidad</label>
            <input type="number" name="speed" id="speed" class="form-control text-center" readonly style="width: 80px; background-color: #384B70">
        </div>
    </div>
</div>


    <h4>Atributos</h4>
<div class="container">
    <div class="row">
       
        <div class="col-md-2 mb-3">
            <label for="strength" class="form-label">Fuerza</label>
            <input type="number" name="strength" id="strength" class="form-control attribute-input text-center" min="1" max="20" required style="width: 60px;">
            <small id="strength-bonus" class="form-text text-muted">Bonificador: 0</small>
            <p class="text-danger" id="strength-error"></p>
        </div>

  
        <div class="col-md-2 mb-3">
            <label for="dexterity" class="form-label">Destreza</label>
            <input type="number" name="dexterity" id="dexterity" class="form-control attribute-input text-center" min="1" max="20" required style="width: 60px;">
            <small id="dexterity-bonus" class="form-text text-muted">Bonificador: 0</small>
            <p class="text-danger" id="dexterity-error"></p>
        </div>


        <div class="col-md-2 mb-3">
            <label for="constitution" class="form-label">Constitución</label>
            <input type="number" name="constitution" id="constitution" class="form-control attribute-input text-center" min="1" max="20" required style="width: 60px;">
            <small id="constitution-bonus" class="form-text text-muted">Bonificador: 0</small>
            <p class="text-danger" id="constitution-error"></p>
        </div>


        <div class="col-md-2 mb-3">
            <label for="intelligence" class="form-label">Inteligencia</label>
            <input type="number" name="intelligence" id="intelligence" class="form-control attribute-input text-center" min="1" max="20" required style="width: 60px;">
            <small id="intelligence-bonus" class="form-text text-muted">Bonificador: 0</small>
            <p class="text-danger" id="intelligence-error"></p>
        </div>


        <div class="col-md-2 mb-3">
            <label for="wisdom" class="form-label">Sabiduría</label>
            <input type="number" name="wisdom" id="wisdom" class="form-control attribute-input text-center" min="1" max="20" required style="width: 60px;">
            <small id="wisdom-bonus" class="form-text text-muted">Bonificador: 0</small>
            <p class="text-danger" id="wisdom-error"></p>
        </div>


        <div class="col-md-2 mb-3">
            <label for="charisma" class="form-label">Carisma</label>
            <input type="number" name="charisma" id="charisma" class="form-control attribute-input text-center" min="1" max="20" required style="width: 60px;">
            <small id="charisma-bonus" class="form-text text-muted">Bonificador: 0</small>
            <p class="text-danger" id="charisma-error"></p>
        </div>
    </div>
    <!-- En todos los atributos hacemos lo mismo, le ponemos los datos en valores en ingles, un minimo y maximo establecido
    un small para justo debajo ver el bono y como con lo de antes, un p para los errores
    -->
</div>



<!-- parte del formulario para las skills, de primeras ponemos una p de info de cuantas skills puede ponerse mas
este campo lo editaremos con JS para que vaya subiendo o bajando, pensé en usar span pero realmente da igual por la forma
en la que lo trato en el JS, aquí es más bien una plantilla sin mas
-->
<h4>Habilidades</h4>
<p id="skills-info">Tienes 0 competencias para escoger!</p>

<div class="container">
    <div class="row">
        <!-- en cada stats le ponemos unos checkbox de las skills posibles, guardandolas somo skills[] ya que pueden ser varias
        las seleccionadas
        -->
        <div class="col-md-4">
            <strong>Fuerza</strong>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Atletismo" id="athletics">
                <label class="form-check-label" for="athletics">Atletismo</label>
            </div>
        </div>


        <div class="col-md-4">
            <strong>Destreza</strong>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Acrobacias" id="acrobatics">
                <label class="form-check-label" for="acrobatics">Acrobacias</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Juego de Manos" id="sleight_of_hand">
                <label class="form-check-label" for="sleight_of_hand">Juego de Manos</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Sigilo" id="stealth">
                <label class="form-check-label" for="stealth">Sigilo</label>
            </div>
        </div>


        <div class="col-md-4">
            <strong>Inteligencia</strong>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Arcana" id="arcana">
                <label class="form-check-label" for="arcana">Arcana</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Historia" id="history">
                <label class="form-check-label" for="history">Historia</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Investigación" id="investigation">
                <label class="form-check-label" for="investigation">Investigación</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Naturaleza" id="nature">
                <label class="form-check-label" for="nature">Naturaleza</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Religión" id="religion">
                <label class="form-check-label" for="religion">Religión</label>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-4">
            <strong>Sabiduría</strong>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Trato Animal" id="animal_handling">
                <label class="form-check-label" for="animal_handling">Trato Animal</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Perspicacia" id="insight">
                <label class="form-check-label" for="insight">Perspicacia</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Medicina" id="medicine">
                <label class="form-check-label" for="medicine">Medicina</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Percepción" id="perception">
                <label class="form-check-label" for="perception">Percepción</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Supervivencia" id="survival">
                <label class="form-check-label" for="survival">Supervivencia</label>
            </div>
        </div>


        <div class="col-md-4">
            <strong>Carisma</strong>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Engaño" id="deception">
                <label class="form-check-label" for="deception">Engaño</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Intimidación" id="intimidation">
                <label class="form-check-label" for="intimidation">Intimidación</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Interpretación" id="performance">
                <label class="form-check-label" for="performance">Interpretación</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="skills[]" value="Persuasión" id="persuasion">
                <label class="form-check-label" for="persuasion">Persuasión</label>
            </div>
        </div>
    </div>
</div>

</div>
<!-- hacemos el commit -->
<button id="save-button" type="submit" class="btn btn-primary fixed-bottom-right" disabled>Guardar Ficha</button>
<a href="{{ route('player.dashboard') }}" class="btn btn-secondary fixed-bottom-left">Cancelar</a>

</form>
@endsection
<script src="{{ asset('js/playercreate.js') }}"></script>

