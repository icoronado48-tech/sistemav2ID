<?php

namespace App\Policies;

use App\Models\Lote;
use App\Models\User; // Asegúrate de importar el modelo User
use Illuminate\Auth\Access\Response;

class LotePolicy
{
    // Opcional: El método `before` se ejecuta antes que cualquier otro método de política.
    // Es útil para otorgar permisos a un "super-admin" sin tener que comprobarlo en cada método.
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('administrador')) { // Usamos el método hasRole del modelo User
            return true;
        }

        return null; // Si devuelve null, Laravel continuará con los demás métodos de la política.
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Solo administradores, producción, inventario o calidad pueden ver la lista de lotes.
        return $user->hasAnyRole(['administrador', 'produccion', 'inventario', 'calidad']); // Asumiendo un rol 'calidad'
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lote $lote): bool
    {
        // Solo administradores, producción, inventario o calidad pueden ver un lote específico.
        return $user->hasAnyRole(['administrador', 'produccion', 'inventario', 'calidad']);
    }

    /**
     * Determine whether the user can create models.
     * (Los lotes se crean a través de OrdenProduccion, no directamente por el usuario)
     */
    public function create(User $user): bool
    {
        return false; // No se permite la creación directa de lotes
    }

    /**
     * Determine whether the user can update the model.
     * (Solo se actualiza el estado de calidad, no el registro del lote en sí)
     */
    public function update(User $user, Lote $lote): bool
    {
        return false; // No se permite la actualización estándar de lotes
    }

    /**
     * Determine whether the user can delete the model.
     * (Los lotes no se eliminan una vez creados para mantener trazabilidad)
     */
    public function delete(User $user, Lote $lote): bool
    {
        return false; // No se permite la eliminación de lotes
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lote $lote): bool
    {
        return false; // No se usa Soft Deletes para Lotes
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lote $lote): bool
    {
        return false; // No se usa Soft Deletes para Lotes
    }

    /**
     * Determine whether the user can update the quality status of the lot.
     * This is a custom ability.
     */
    public function updateQualityStatus(User $user, Lote $lote): bool
    {
        // Solo administradores o personal de calidad pueden actualizar el estado de calidad.
        // Y solo si el lote no está ya en un estado final de calidad como 'rechazado' o 'aprobado'
        // Puedes ajustar esta lógica según tus necesidades (ej., permitir re-evaluación).
        return $user->hasAnyRole(['administrador', 'calidad']) &&
            $lote->estado_calidad !== 'rechazado'; // Ejemplo: no se puede cambiar si ya fue rechazado
    }
}
