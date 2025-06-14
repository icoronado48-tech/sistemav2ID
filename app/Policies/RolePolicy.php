<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    /**
     * Permite a los administradores pasar todas las comprobaciones de política.
     * Este método se ejecuta antes que cualquier otro método de política.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Si el usuario tiene el rol 'Gerente General', se le permite realizar cualquier acción.
        // Asegúrate de que tu modelo User tenga el método hasRole o hasAnyRole.
        if ($user->hasRole('Gerente General')) { // CAMBIADO AQUÍ
            return true;
        }

        return null;
    }

    /**
     * Determina si el usuario puede ver cualquier modelo de rol.
     */
    public function viewAny(User $user): bool
    {
        // Permite ver la lista de roles a 'Gerente General' y, opcionalmente, a 'Jefe de Producción'
        // o cualquier otro rol que deba ver la gestión de roles.
        return $user->hasAnyRole(['Gerente General', 'Jefe de Producción']); // CAMBIADO AQUÍ
    }

    /**
     * Determina si el usuario puede ver el modelo de rol dado.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasAnyRole(['Gerente General', 'Jefe de Producción']); // CAMBIADO AQUÍ
    }

    /**
     * Determina si el usuario puede crear modelos de rol.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Gerente General']); // Solo Gerente General para crear roles
    }

    /**
     * Determina si el usuario puede actualizar el modelo de rol dado.
     */
    public function update(User $user, Role $role): bool
    {
        // El Gerente General puede actualizar roles, pero no puede actualizarse a sí mismo (opcional)
        return $user->hasAnyRole(['Gerente General']); // CAMBIADO AQUÍ
    }

    /**
     * Determina si el usuario puede eliminar el modelo de rol dado.
     */
    public function delete(User $user, Role $role): bool
    {
        // El Gerente General puede eliminar roles, pero no el rol de "Gerente General"
        return $user->hasAnyRole(['Gerente General']) && $role->nombre_rol !== 'Gerente General'; // CAMBIADO AQUÍ
    }

    /**
     * Determina si el usuario puede restaurar el modelo de rol dado.
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->hasRole('Gerente General'); // CAMBIADO AQUÍ
    }

    /**
     * Determina si el usuario puede eliminar permanentemente el modelo de rol dado.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return $user->hasRole('Gerente General'); // CAMBIADO AQUÍ
    }
}
