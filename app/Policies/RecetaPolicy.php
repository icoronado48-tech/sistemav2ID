<?php

namespace App\Policies;

use App\Models\Receta;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class RecetaPolicy
{
    /**
     * Determina si el usuario puede realizar cualquier acción.
     * Este método se ejecuta antes que los específicos y puede permitir o denegar globalmente.
     * Si retorna true, la autorización es concedida. Si retorna null, se procede a los métodos específicos.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Un administrador siempre puede realizar cualquier acción.
        // Asumimos que el rol 'administrador' en tu sistema es siempre en minúsculas.
        // Si tu DB tiene 'Administrador' con mayúscula, cámbialo aquí también.
        if ($user->hasRole('administrador')) {
            Log::debug("RecetaPolicy: Usuario es ADMINISTRADOR. Acceso concedido por 'before'.");
            return true;
        }

        // Log para depuración si no es administrador y se pasa a los métodos específicos
        $rolesString = $user->roles ? $user->roles->pluck('nombre_rol')->implode(', ') : 'N/A o no cargados';
        Log::debug("RecetaPolicy: Usuario ID {$user->id} NO es administrador. Rol(es): {$rolesString}. Pasando a métodos específicos.");

        return null; // Deja que los métodos específicos de la política manejen la autorización para otros roles.
    }

    /**
     * Determina si el usuario puede ver cualquier receta (acceso al listado).
     */
    public function viewAny(User $user): bool
    {
        // Administradores, Gerentes Generales, Jefes de Producción y Operarios de Producción pueden ver las recetas.
        $allowedRoles = ['administrador', 'Gerente General', 'Jefe de Producción', 'Operario de Producción'];
        $canView = $user->hasAnyRole($allowedRoles);
        Log::debug("RecetaPolicy: viewAny para usuario ID {$user->id}. Roles permitidos: " . implode(', ', $allowedRoles) . ". Resultado: " . ($canView ? 'TRUE' : 'FALSE'));
        return $canView;
    }

    /**
     * Determina si el usuario puede ver una receta específica.
     */
    public function view(User $user, Receta $receta): bool
    {
        // Administradores, Gerentes Generales, Jefes de Producción y Operarios de Producción pueden ver una receta.
        $allowedRoles = ['administrador', 'Gerente General', 'Jefe de Producción', 'Operario de Producción'];
        $canView = $user->hasAnyRole($allowedRoles);
        Log::debug("RecetaPolicy: view para usuario ID {$user->id}. Roles permitidos: " . implode(', ', $allowedRoles) . ". Resultado: " . ($canView ? 'TRUE' : 'FALSE'));
        return $canView;
    }

    /**
     * Determina si el usuario puede crear nuevas recetas.
     */
    public function create(User $user): bool
    {
        // Solo administradores, Gerentes Generales y Jefes de Producción pueden crear recetas.
        $allowedRoles = ['administrador', 'Gerente General', 'Jefe de Producción'];
        $canCreate = $user->hasAnyRole($allowedRoles);
        Log::debug("RecetaPolicy: create para usuario ID {$user->id}. Roles permitidos: " . implode(', ', $allowedRoles) . ". Resultado: " . ($canCreate ? 'TRUE' : 'FALSE'));
        return $canCreate;
    }

    /**
     * Determina si el usuario puede actualizar una receta existente.
     */
    public function update(User $user, Receta $receta): bool
    {
        // Solo administradores, Gerentes Generales y Jefes de Producción pueden actualizar recetas.
        $allowedRoles = ['administrador', 'Gerente General', 'Jefe de Producción'];
        $canUpdate = $user->hasAnyRole($allowedRoles);
        Log::debug("RecetaPolicy: update para usuario ID {$user->id}. Roles permitidos: " . implode(', ', $allowedRoles) . ". Resultado: " . ($canUpdate ? 'TRUE' : 'FALSE'));
        return $canUpdate;
    }

    /**
     * Determina si el usuario puede eliminar una receta.
     * La eliminación es una acción delicada que impacta la integridad de datos.
     */
    public function delete(User $user, Receta $receta): bool
    {
        // Solo administradores y Gerentes Generales pueden eliminar recetas.
        $allowedRoles = ['administrador', 'Gerente General'];
        $canDelete = $user->hasAnyRole($allowedRoles);
        Log::debug("RecetaPolicy: delete para usuario ID {$user->id}. Roles permitidos: " . implode(', ', $allowedRoles) . ". Resultado: " . ($canDelete ? 'TRUE' : 'FALSE'));
        return $canDelete;
    }

    /**
     * Determina si el usuario puede restaurar una receta (si se usa Soft Deletes).
     */
    public function restore(User $user, Receta $receta): bool
    {
        // Generalmente solo administradores pueden restaurar elementos.
        $canRestore = $user->hasRole('administrador');
        Log::debug("RecetaPolicy: restore para usuario ID {$user->id}. Resultado: " . ($canRestore ? 'TRUE' : 'FALSE'));
        return $canRestore;
    }

    /**
     * Determina si el usuario puede eliminar permanentemente una receta (si se usa Soft Deletes).
     */
    public function forceDelete(User $user, Receta $receta): bool
    {
        // Generalmente solo administradores pueden eliminar permanentemente.
        $canForceDelete = $user->hasRole('administrador');
        Log::debug("RecetaPolicy: forceDelete para usuario ID {$user->id}. Resultado: " . ($canForceDelete ? 'TRUE' : 'FALSE'));
        return $canForceDelete;
    }
}
