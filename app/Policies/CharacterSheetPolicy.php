<?php

namespace App\Policies;

use App\Models\CharacterSheet;
use App\Models\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class CharacterSheetPolicy
{
    use HandlesAuthorization;

    // Permite ver la ficha si pertenece al jugador autenticado
    public function view(Client $user, CharacterSheet $characterSheet)
    {
        return $user->id === $characterSheet->client_id;
    }

    // Permite editar la ficha si pertenece al jugador autenticado
    public function update(Client $user, CharacterSheet $characterSheet)
    {
        return $user->id === $characterSheet->client_id;
    }

    // Permite eliminar la ficha si pertenece al jugador autenticado
    public function delete(Client $user, CharacterSheet $characterSheet)
    {
        return $user->id === $characterSheet->client_id;
    }
}
