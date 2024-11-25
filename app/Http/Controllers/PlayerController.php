<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CharacterSheet;

class PlayerController extends Controller
{
    //Función para llevar al jugador al dashboard
    public function dashboard(){
        // recogemos todos los personajes del jugador, con los datos de las sesiones que pueda tener para usarlo en el "Sesiones Activas" de la vista luego
        // Como? buscamos en la tabla de character_sheets las que coincidan su client_id con el auth id, es decir, el id del usuario actual.
        // De esta forma conseguimos tanto que solo aparezcan las fichas de personaje del usuario y sus sesiones que esten relacionadas en la tabla.
    $characterSheets = CharacterSheet::with(['sessions'])->where('client_id', auth()->id())->get();
    return view('player.dashboard', compact('characterSheets'));
    }
}
