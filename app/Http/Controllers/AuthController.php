<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    //función para llamar a la función de registro
    public function showRegistrationForm(){
        return view('auth.register');
    }

    //función de registro
    public function register(Request $request){
        // Validamos todos los campos primero con las restricciones por back
        $request->validate([
            'name' => 'required|string|max:25', //máximo de 25 letras
            'email' => 'required|string|email|max:40|unique:clients', //máximo de 40 letras y que sea único
            'password' => 'required|string|min:8|confirmed', //minimo de 8 y que sea igual que el _confirmation
            'role' => 'required|in:player,dm', //que sea dm o jugador (admins no pueden ser creados a salvo de un insert en la base de datos)
        ]);
        // Creamos el usuario una vez todos los campos están validados
        $client = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), //haciendo un hash de la contraseña para seguridad
            'role' => $request->role,
        ]);

        return redirect()->route('home')->with('success', '¡Registro con éxito! Prueba a Iniciar Sesión ahora.');
    }
    
    //función para llamar a la función de login
    public function showLoginForm(){
        return view('auth.login');
    }

    //función de login
    public function login(Request $request){
        //validamos que nos llega algo
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if (Auth::attempt($credentials)) {
            // Método de seguridad de laravel para regenerar la ID de la sesión y evitar sesiones deprecadas puedan entrar y atacar
            $request->session()->regenerate();

            //Llevar a un usuario a una vista u otra según su rol
            $role = Auth::user()->role;
            if ($role === 'admin') {
                // Si el rol es admin, al admin dahsboard
                return redirect()->route('admin.dashboard');
             
            } elseif ($role === 'dm') {
                // Si el rol es dm, al dm dashboard
                return redirect()->route('dm.dashboard');
            } else {
                // Si es cualquier otro (solo hay 3 opciones), al player (también, si consiguen superar mi seguridad
                // es el rol que no puede alterar al resto ergo el más seguro.)
                return redirect()->route('player.dashboard');
            }
        }
    }

    //función para cerrar sesión
    public function logout(Request $request){
        // Eliminamos información del usuario actual
        Auth::logout();
        // Invalidamos su sesión para evitar que peuda entrar con ella volviendo atrás en la página o similar.
        $request->session()->invalidate();
        // regeneramos el token CSRF de Laravel
        $request->session()->regenerateToken();

        return redirect()->route('home'); // Redirigir a la página de inicio
    }
}
