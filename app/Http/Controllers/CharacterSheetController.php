<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CharacterSheet;
use Illuminate\Support\Facades\Storage;



class CharacterSheetController extends Controller
{
    //función para llevar al crear una ficha de personaje
    public function create(){
        return view('player.characterSheets.create');
    }


    //Función para guardar el personaje
    public function store(Request $request){
        $request->validate([
            // Validamos todos los campos
            'name' => 'required|string|max:25',//requerido y maximo de 25 
            'race' => 'required|string',///requerido y sin más porque solo pueden ser una de las opciones que nos vienen por API
            'class' => 'required|string',//requerido y sin más porque solo pueden ser una de las opciones que nos vienen por API
            'level' => 'required|integer|min:1|max:20',//Aunque lo revisemos en front, que solo pueda ser entre 1 y 20
            'image' => 'image|mimes:jpeg,png,jpg,webp|max:5120',//requerido y solo sea una imagen .jpeg, png, jpg o webp de 5 MB 
            'strength' => 'required|integer|min:1|max:20',//Aunque lo revisemos en front, que solo pueda ser entre 1 y 20
            'dexterity' => 'required|integer|min:1|max:20',//Aunque lo revisemos en front, que solo pueda ser entre 1 y 20
            'constitution' => 'required|integer|min:1|max:20',//Aunque lo revisemos en front, que solo pueda ser entre 1 y 20
            'intelligence' => 'required|integer|min:1|max:20',//Aunque lo revisemos en front, que solo pueda ser entre 1 y 20
            'wisdom' => 'required|integer|min:1|max:20',//Aunque lo revisemos en front, que solo pueda ser entre 1 y 20
            'charisma' => 'required|integer|min:1|max:20',//Aunque lo revisemos en front, que solo pueda ser entre 1 y 20
            'proficiency_bonus' => 'required|integer', //Sin más porque es un campo autocalculado nuestro
        ]);

        
        //Guardamos en la variable imagePath la imagen
        
        if ($request->hasFile('image')) {
            // Si hay archivo, lo guarda en el directorio de imágenes de personajes y genera la ruta pública.
            $imagePath = $request->file('image')->store('character_images', 'public');
            $imagePublicPath = Storage::url($imagePath);
        } else {
            // Si no hay archivo, asigna la ruta de la imagen predeterminada.
            $imagePublicPath = '/images/default.png';
        }
        //Creamos la estructura de todos los datos para que se almacenen como JSON en la base de datos en el campo "valores"
        $data = [
            'name' => $request->input('name'),
            'race' => $request->input('race'),
            'class' => $request->input('class'),
            'level' => $request->input('level'),
            'attributes' => [
                'strength' => $request->input('strength'),
                'dexterity' => $request->input('dexterity'),
                'constitution' => $request->input('constitution'),
                'intelligence' => $request->input('intelligence'),
                'wisdom' => $request->input('wisdom'),
                'charisma' => $request->input('charisma'),
            ],
            'proficiency_bonus' => $request->input('proficiency_bonus'),
            'speed' => $request->input('speed'),
            'skills' => $request->input('skills') ?? [], //que guarde las skills si hay, si no que de un array vacío en ese campo
            'image' => $imagePublicPath //la variable que tenemos antes con la ruta publica de acceso
        ];
        // Una vez está todo bien, creamos  la character sheet pasándole los valores de la ID del usuario registrado y el JSON de los valores
        CharacterSheet::create([
            'client_id' => auth()->id(),
            'valores' => json_encode($data),
        ]);

        // devolver al dashboard una vez está bien hecho
        return redirect()->route('player.dashboard')->with('success', 'Personaje creado ¿Por qué no pruebas a darle a Ver?');
    }







    //Función para mostar el personaje que coincida con la id que estamos clickando.
    public function show($id){
        // Variable que busque el characterSheet con la misma ID en la base de datos que estamos buscando.
        $characterSheet = CharacterSheet::findOrFail($id);
        // decodifica todo el json de ese characterSheet valores en una variable
        $data = json_decode($characterSheet->valores, true);

        // Nos vayamos a esa vista con esos datos
        return view('player.characterSheets.show', compact('data', 'characterSheet'));
    }





    // Relación muchos a muchos para el apartado de la vista de las sesiones que tendrán los personajes, necesitamso tener la relación de las sesiones
    public function sessions(){
        return $this->belongsToMany(Session::class, 'session_characters', 'character_sheet_id', 'session_id');
    }






    //función para llevar a la vista de editar el characterSheet que estamos clickando
    public function edit($id){
        // Variable que busque el characterSheet con la misma ID en la base de datos que estamos buscando.
        $characterSheet = CharacterSheet::findOrFail($id);
        // decodifica todo el json de ese characterSheet valores en una variable
        $data = json_decode($characterSheet->valores, true);

        // Nos vayamos a esa vista con esos datos
        return view('player.characterSheets.edit', compact('data', 'characterSheet'));
    }









    //Función para updatear el characterSheet
    public function update(Request $request, $id){
        //Guardamos en una variable el characterSheet que estamos tratando, buscado por su ID.
        $characterSheet = CharacterSheet::findOrFail($id);

        // Validación de los datos
        $request->validate([
            'name' => 'required|string|max:25',//requerido y maximo de 25 
            'race' => 'required|string',///requerido y sin más porque solo pueden ser una de las opciones que nos vienen por API
            'class' => 'required|string',///requerido y sin más porque solo pueden ser una de las opciones que nos vienen por API
            'level' => 'required|integer|min:1|max:20',//Aunque lo revisemos en front, que solo pueda ser entre 1 y 20
            'strength' => 'required|integer|min:1|max:20',//Aunque lo revisemos en front, que solo pueda ser entre 1 y 20
            'dexterity' => 'required|integer|min:1|max:20',//Aunque lo revisemos en front, que solo pueda ser entre 1 y 20
            'constitution' => 'required|integer|min:1|max:20',//Aunque lo revisemos en front, que solo pueda ser entre 1 y 20
            'intelligence' => 'required|integer|min:1|max:20',//Aunque lo revisemos en front, que solo pueda ser entre 1 y 20
            'wisdom' => 'required|integer|min:1|max:20',//Aunque lo revisemos en front, que solo pueda ser entre 1 y 20
            'charisma' => 'required|integer|min:1|max:20',//Aunque lo revisemos en front, que solo pueda ser entre 1 y 20
            'proficiency_bonus' => 'required|integer', //Sin más porque es un campo autocalculado nuestro
            
            //No ponemos validación de imagen ya que el edit gratuito no tendrá esa funcionalidad, eso sería un extra del sector de pago!
        ]);

        //De la variable con los datos de antes, decodeamos el json
        $currentData = json_decode($characterSheet->valores, true);
        // guardamos la imagen ya que, por ahora, no se actualizará asi que no podemos cogerlo del input ya que no hay, nos guardamos
        // la que ya viene en el json.


        //creamos el array con todos los datos de nuevo
        $data = [
            'name' => $request->input('name'),
            'race' => $request->input('race'),
            'class' => $request->input('class'),
            'level' => $request->input('level'),
            'attributes' => [
                'strength' => $request->input('strength'),
                'dexterity' => $request->input('dexterity'),
                'constitution' => $request->input('constitution'),
                'intelligence' => $request->input('intelligence'),
                'wisdom' => $request->input('wisdom'),
                'charisma' => $request->input('charisma'),
            ],
            'proficiency_bonus' => $request->input('proficiency_bonus'),
            'speed' => $request->input('speed'),
            'skills' => $request->input('skills') ?? [], //que guarde las skills si hay, si no que de un array vacío en ese campo
            'image' => $currentData['image'],
        ];

        // Actualizar la ficha de personaje en la base de datos
        $characterSheet->update(['valores' => json_encode($data)]);

        // mandamos al dashboard de nuevo con una notificación
        return redirect()->route('player.dashboard')->with('success', 'Cambios realizados con éxito ¿Por qué no los revisas en Ver?');
    }
            





        

    public function destroy($id){
        //Buscamos el characterSheet en la base de datos por el ID que estamos clickando.
        $characterSheet = CharacterSheet::findOrFail($id);

        // decodificamos todo el json de ese characterSheet para poder recoger la identidad de la foto
        $values = json_decode($characterSheet->valores, true);
        // lo guardamos en una variable con posibilidad de que sea null en caso de que no tenga foto porque
        // el usuario no tenía.
        $imagePath = $values['image'] ?? null;
        // le añadimos la ruta relativa desde storage para que podamos destruirlo
        $imagePath = str_replace('/storage/', '', $imagePath);
        Storage::disk('public')->delete($imagePath);

        //Después ya destruimos el sheet en si de la base de datos
        $characterSheet->delete();

        //Mandamos al dashbaord de nuevo con una notificacion
        return redirect()->route('player.dashboard')->with('success', 'Personaje eliminado... Será recordado como un héroe.');
    }
}