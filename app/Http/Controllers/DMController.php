<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;
use App\Models\User;
use App\Models\CharacterSheet;
use Illuminate\Support\Facades\DB;

class DMController extends Controller
{
    //Función para llevar al dm al dashboard
    public function dashboard(){
        // Recogemos todas las sesiones que tengan la relación con el dm_id y los personajes que tienen en su interior para mostrarlos luego
        $sessions = Session::where('dm_id', auth()->id())->with('characters')->get();
        // mandamos al a vista con esos datos para jugar con ellos en vista
        return view('dm.dashboard', compact('sessions'));
    }

  

    //Función para llamar a la vista de crear una sesión
    public function create(){
        return view('dm.sessions.create');
    }

    //Función para guardar una sesión
    public function store(Request $request){
        // validamos todos los campos de registro de la sesión
        $request->validate([
            'title' => 'required|string|max:25', //el título de 25 letras como máximo
            'description' => 'nullable|string', //la descripción, que pueda no haber nada
            'date' => 'required|date|after_or_equal:today', //que no sea previo a hoy
            'time' => 'required|date_format:H:i:s', //formato de la hora para guardarlo
        ]);

        // Decodificamos el json de characters_ids que nos viene en la vista de los characterSheets escogidos en la tabla
        $characterIds = json_decode($request->character_ids, true);

        //Nos aseguramos que no haya una sesión que tenga el date y el time igual.
        $existingSession = Session::where('date', $request->date)->where('time', $request->time)->first();
        // Si por algún casual esa fecha ya existiera, pues que devuelva un error informandolo
        if ($existingSession) {
            return back()->with('error', 'Ya existe una sesión a esa hora en la fecha seleccionada, prueba con otra.');
        }

        //Creamos la sesión.
        $session = Session::create([
            'dm_id' => auth()->id(), //El ID del dm actual.
            'title' => $request->title, // el título
            'description' => $request->description, // la descripción
            'date' => $request->date, // la fecha 
            'time' => $request->time, // la hora
        ]);

        //del JSON decodificado de antes hacemos un recorrido con foreach y, por cada uno hacemos una inserción a una segunda tabla
        foreach ($characterIds as $characterId) {
            // Esta tabla hará las veces de relación entre las sesiones y los personajes
            \DB::table('session_characters')->insert([
                'session_id' => $session->id, // Cogemos la ID autoincremental de la sesión
                'character_sheet_id' => $characterId, // la ID del characterSheet
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Y devolvemos a la vista dashboard con una notificación de éxito.
        return redirect()->route('dm.dashboard')->with('success', 'Sesión creada con éxito ¿Por qué no pruebas a visualizarla?');
    }



    //Función para dirigir a la vista de show
    public function show($id){
        // Recogemos los personajes que pueda tener la sesión para mostarlos en la vista luego
        $session = Session::with('characters')->findOrFail($id);
        // Los mandamos compacctados a la vista
        return view('dm.sessions.show', compact('session'));
    }

    //Función para dirigir a la vista de editar la sesión
    public function edit($id){
        // Recogemos la sesión que coincida con la que hemos clickado
        $session = Session::findOrFail($id);
        return view('dm.sessions.edit', compact('session'));
    }

    //Función para actualizar los valores de la sesión
    public function update(Request $request, $id){
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            // No añadimos un campo de validación de personajes porque eso no estará en la versión gratuita, sino en la premium!
        ]);

        // Nos aseguramos que la sesión que están poniendo nueva no exista ya en la base de datos y, en caso de que si, lo informamos con un error.
        $existingSession = Session::where('date', $request->date)->where('time', $request->time)->first();
        if ($existingSession) {
            return back()->with('error', 'Ya existe una sesión a esa hora en la fecha seleccionada, prueba con otra.');
        }

        // Actualizamos los datos de la sesión que coincida por ID con los que hemos validado
        $session = Session::findOrFail($id);
        $session->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'time' => $request->time,
        ]);
        return redirect()->route('dm.dashboard')->with('success', 'Sesión actualizada ¿Por qué no revisas los cambios en Ver?.');
    }


    // Función para eliminar las sesiones.
    public function destroy($id){
        // Buscamos la sesion con la misma ID de la que estamos clickando.
        $session = Session::findOrFail($id);
        // Tambien eliminado la sesion de la tabla de session_characters que hacia de intermediaria.
        DB::table('session_characters')->where('session_id', $id)->delete();
        $session->delete();

        return redirect()->route('dm.dashboard')->with('success', 'Sesión eliminada, los aventureros tendrán que esperar otro día...');
    }

    //Función para obtener todos los jugadoes que tengan como role player, consiguiendo sus datos y guardandolos en un json.
    // Esto será usado para las tablas de lista de jugadores, donde mostraremos su nombre y email para que puedan seleccionarlos.
    public function getPlayers(){
            $players = User::where('role', 'player')->get(['id', 'name', 'email']);
            return response()->json($players);
    }

    //Funcion para conseguir las fichas de personaje que tenga ese jugador que hemos seleccionado.
    public function getCharacterSheets($playerId){
        try {
            //Hacemos una consulta a la tabla intermediaria, con pluck, sacando solo la columna character_sheet_id.
            $assignedCharacterIds = DB::table('session_characters')->pluck('character_sheet_id');
            // Variable donde guardamos los charactersheet cuyo client_id no esté como en el id de la variable que acabamos de sacar.
            // Es decir, que no exista en la tabla intermediaria, ergo, no sea un personaje en un rol todavia.
            $characterSheets = CharacterSheet::where('client_id', $playerId)->whereNotIn('id', $assignedCharacterIds)->get();

            // Cogemos los datos de dicha variable de pjs sin sesión y decodeamos su json con algunso de los valores del json que hay en valores 
            $sheets = $characterSheets->map(function ($sheet) {
                $data = json_decode($sheet->valores, true);
                return [
                    'id' => $sheet->id,
                    'name' => $data['name'] ?? 'Desconocido',
                    'level' => $data['level'] ?? 'Sin nivel',
                    'race' => $data['race'] ?? 'Sin raza',
                    'class' => $data['class'] ?? 'Sin clase',
                ];
                // El resto de campos que podría haber en la ficha, como sus skills, proficiencias etc, no considero que sean vitales para el DM de saber y, en caso de que así sea
                // sería algo reservado para el servicio premium!
            });

            return response()->json($sheets);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No hay fichas de personajes.'], 500);
        }
    }

}
