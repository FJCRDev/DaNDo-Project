<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CharacterSheet;
use App\Models\Session;
use App\Models\User;


class AdminController extends Controller
{
    // Dashboard del admin
    public function index(){
        $users = User::all(); //Coger todos los Usuarios para mostrarlos
        $sessions = Session::all(); //Coger todas las Sesiones para mostrarlas
        return view('admin.dashboard', compact('users', 'sessions'));//Devolverlos a la vista para tratarlos
    }
   
    // Destruir la sesión
    public function destroySession(Session $session)
    {
        $session->delete();

        return redirect()->route('admin.dashboard');
    }

    // Destruir el usuario
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.dashboard');
    }

}
