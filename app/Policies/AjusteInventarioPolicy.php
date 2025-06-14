<?php

namespace App\Policies;

use App\Models\AjusteInventario;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AjusteInventarioPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('administrador')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        // Solo administradores o personal de inventario/logística.
        return $user->hasAnyRole(['administrador', 'inventario']);
    }

    public function view(User $user, AjusteInventario $ajusteInventario): bool
    {
        return $user->hasAnyRole(['administrador', 'inventario']);
    }

    public function create(User $user): bool
    {
        // Solo administradores o personal de inventario/logística pueden crear ajustes.
        return $user->hasAnyRole(['administrador', 'inventario']);
    }

    // Si no permites update/delete, puedes omitir estos métodos o hacer que retornen false.
    // public function update(User $user, AjusteInventario $ajusteInventario): bool { return false; }
    // public function delete(User $user, AjusteInventario $ajusteInventario): bool { return false; }
}
