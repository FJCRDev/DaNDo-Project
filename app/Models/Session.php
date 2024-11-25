<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

     //la tabla en la base de datos de las sesiones (tuve que añadir una game_sessions porque la tabla sessions es usada por laravel para las verificaciones de seguridad)
    protected $table = 'game_sessions';

    //Los campos rellenables de la base de datos (id es autoincremental)
    protected $fillable = [
        'dm_id',
        'title',
        'description',
        'date',
        'time',
        'created_at',
        'updated_at',
    ];

    // Relación de que el dm pertenece a Users por el dm_id
    public function dm(){
        return $this->belongsTo(User::class, 'dm_id');
    }

    // Relación de que los personajes pertenecen a varias sesiones por sus datos
    public function characters()
    {
        return $this->belongsToMany(CharacterSheet::class, 'session_characters', 'session_id', 'character_sheet_id');
    }
}
