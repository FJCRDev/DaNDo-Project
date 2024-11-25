@extends('layouts.header')

@section('title', 'Ver Ficha de Personaje')

@section('content')
<link href="{{ asset('css/playershow.css') }}" rel="stylesheet">

<h1>Ficha de Personaje</h1>

<div class="container d-flex justify-content-center mt-4">
    <div class="card" style="width: 100%;">
        <div class="row g-0">
            <!-- De la misma forma que en dashboard, buscamos con php los data que tenga de la imagen y si dicho archivo existe en la carpeta public
            en caso de que así sea, que ponga la imagen, en caso de que no, que ponga la default
            -->
            <div class="col-md-4 d-flex align-items-center justify-content-center p-3">
                @php
                    $imagePath = isset($data['image']) && file_exists(public_path($data['image'])) ? asset($data['image']) : asset('images/default.png');
                @endphp
                <img src="{{ $imagePath }}" class="img-fluid rounded" alt="Imagen del Personaje" style="max-height: 410px;">
            </div>
            
            <!-- volcamos todos los datos que tenemos desde la funcion show en el $data del characterSheet que hemos seleccionado-->
            <div class="col-md-8 p-4">
                <h4>{{ $data['name'] }}</h4>
                <p><strong>Raza:</strong> {{ $data['race'] }}</p>
                <p><strong>Clase:</strong> {{ $data['class'] }}</p>
                <p><strong>Nivel:</strong> {{ $data['level'] }}</p>
                <p><strong>Bono de Competencia:</strong> {{ $data['proficiency_bonus'] }}</p>
                <p><strong>Velocidad:</strong> {{ $data['speed'] }} pies</p>

                <!-- Para los atributos cogemos el array que hay dentor del array de valores ya que así lo tenemos ordenado -->
                <h5 class="mt-3">Atributos</h5>
                <ul class="list-group">
                    @foreach ($data['attributes'] as $attribute => $value)
                        <li class="list-group-item">
                            <strong>{{ ucfirst($attribute) }}:</strong> {{ $value }} 
                            <span class="text-muted">(Bonificador: {{ ($value - 10) / 2 >= 0 ? '+' : '' }}{{ floor(($value - 10) / 2) }})</span>
                        </li>
                    @endforeach
                    <!-- Para cada uno vamos on ucfirst que convierte el primer string que encuentra, la primera letra en mayúscula
                    De esta forma lo ponemos sin complicarnos bonito en la vista, después para el bono hacemos el cálculo matemático
                    para saber el bono, que es restarle 10 al valor y dividirlo entre dos,
                     de Esta forma por ejemplo un 18 - 10 = 8 / 2 = 4. Un +4
                     Y un 8 - 10 = -2 / 2 = -1. Al ser ya negativo no tenemos que decir que ponga un - entre las opciones del calculo,
                     Sino nos saldria como un --2. Luego volvemos a hacer el calculo pero para poner el número en si.
                    -->
                </ul>
            </div>
        </div>
    </div>

    <!-- Para las habilidades hacemos primero un array con todas las skills diferenciadas en que stat van
    para poder así ponerlo en la tabla ordenado clasificando dicho array, es un poco coñazo pero al ser algo
    limitado y que sabemso a ciencia cierta que no cambiará porque es algo core de las normas del juego, lo dejamos
    

    ciclamos sobre dicho array tambien subclasificando cada subcategoria como skills.
    De esta forma ordenamos en h5 el atributo y debajo le ponemos en una lista no ordenada sus skills
    A las cuales, si están en los datos del character sheet lo ponemos en bold, si no, hacemos que esté muffled
    y tenga tachado, dando una visual representación del as skills que tiene el character.
    -->
    <div class="card mt-4 w-100">
        <div class="card-body">
            <h5 class="card-title">Habilidades</h5>
            <div class="row">
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
                        <h5>{{ $attribute }}</h5>
                        <ul class="list-unstyled">
                        @foreach ($skills as $skill)
                            <li class="{{ in_array($skill, $data['skills']) ? 'font-weight-bold' : 'text-muted text-decoration-line-through' }}">
                                {{ $skill }}
                            </li>
                        @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- simplemente un botón para volver al dashboard -->
        <div class="mt-4 text-center">
            <a href="{{ route('player.dashboard') }}" class="btn btn-secondary">Volver al Dashboard</a>
        </div>
    </div>
</div>
@endsection
