<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterSheet extends Model
{
    use HasFactory;

    //la tabla en la base de datos del los characterSheets
    protected $table = 'character_sheets';

    //Los campos rellenables de la base de datos (id es autoincremental)
    protected $fillable = [
        'client_id',
        'valores',
        'created_at',
        'updated_at',
    ];

    // Relación de que el el CharacterSheet pertenece a Uusarios por la client_id
    public function user(){
    return $this->belongsTo(User::class, 'client_id');
    }

    // Relación de que el el CharacterSheet pertenece a múltiples sesiones por la relación entre lo personajes de la sesión, ID de dichos personajes y la ID de la sesion.
    public function sessions(){
        return $this->belongsToMany(Session::class, 'session_characters', 'character_sheet_id', 'session_id')->withTimestamps();
    }
}
