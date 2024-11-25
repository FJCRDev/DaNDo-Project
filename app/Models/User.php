<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    //la tabla en la base de datos de las sesiones
    protected $table = 'clients';

    //Los campos rellenables de la base de datos (id es autoincremental)
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relacion de que un usuario puede tener muchas sesiones segun su client id
    public function characterSheets(){
    return $this->hasMany(CharacterSheet::class, 'client_id');
    }

    // Relacion de que un usuario puede tener muchas sesiones segun su dm id
    public function sessions(){
        return $this->hasMany(Session::class, 'dm_id');
    }

}
