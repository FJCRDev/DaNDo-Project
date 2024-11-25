<?php

namespace App\Policies;

use App\Models\Session;
use App\Models\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class SessionPolicy
{
    use HandlesAuthorization;

    // Permite ver la sesión si pertenece al DM autenticado
    public function view(Client $user, Session $session)
    {
        return $user->id === $session->dm_id;
    }

    // Permite editar la sesión si pertenece al DM autenticado
    public function update(Client $user, Session $session)
    {
        return $user->id === $session->dm_id;
    }

    // Permite eliminar la sesión si pertenece al DM autenticado
    public function delete(Client $user, Session $session)
    {
        return $user->id === $session->dm_id;
    }
}
