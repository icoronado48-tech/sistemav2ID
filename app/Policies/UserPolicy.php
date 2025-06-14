<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    // Opcional: El método `before` se ejecuta antes que cualquier otro método de política.
    // Es útil para otorgar permisos a un "super-admin" sin tener que comprobarlo en cada método.
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('Gerente General')) { // Usamos el método hasRole que sugerimos en el modelo User
            return true;
        }

        return null; // Si devuelve null, Laravel continuará con los demás métodos de la política.
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Solo administradores o usuarios de producción/inventario/ventas pueden ver la lista de usuarios.
        // Aquí puedes ajustar qué roles tienen acceso a ver la lista de usuarios.
        return $user->hasAnyRole(['Gerente General', 'Jefe Produccion', 'Supervisor de Logistica', 'Gerente de ventas']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Un usuario puede ver su propio perfil, o un administrador puede ver cualquier perfil.
        return $user->id === $model->id || $user->hasRole('Gerente General');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Solo un administrador puede crear nuevos usuarios.
        return $user->hasRole('Gerente General');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Un usuario puede actualizar su propio perfil.
        // Un administrador puede actualizar cualquier perfil (ya cubierto por `before`).
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Solo un administrador puede eliminar usuarios.
        // Un usuario no puede eliminarse a sí mismo (para evitar problemas de sesión).
        return $user->hasRole('Gerente General') && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        // Generalmente, solo los administradores pueden restaurar usuarios (soft deletes).
        return $user->hasRole('Gerente General');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Generalmente, solo los administradores pueden eliminar permanentemente.
        return $user->hasRole('Gerente General');
    }

    /**
     * Determine whether the user can assign a role to a user.
     * This is a custom ability if `assignRole` is a separate action.
     */
    public function assignRole(User $user, User $model): bool
    {
        // Solo un administrador puede asignar roles.
        // Un administrador no puede cambiar el rol de sí mismo a algo que lo despoje de su rol de administrador.
        return $user->hasRole('Gerente General') && $user->id !== $model->id;
    }
}
