<?php

namespace App\Policies;

use App\Models\ProductoTerminado;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductoTerminadoPolicy
{
    /**
     * Run before any other policy method to grant full access to administrators.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('administrador')) {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Solo administradores, inventario o producción pueden ver productos terminados.
        return $user->hasAnyRole(['administrador', 'inventario', 'produccion', 'ventas']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductoTerminado $productoTerminado): bool
    {
        // Solo administradores, inventario o producción pueden ver un producto terminado específico.
        return $user->hasAnyRole(['administrador', 'inventario', 'produccion', 'ventas']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Solo administradores o personal de producción/inventario pueden crear productos terminados.
        return $user->hasAnyRole(['administrador', 'inventario', 'produccion']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductoTerminado $productoTerminado): bool
    {
        // Solo administradores o personal de producción/inventario pueden actualizar productos terminados.
        return $user->hasAnyRole(['administrador', 'inventario', 'produccion']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductoTerminado $productoTerminado): bool
    {
        // Solo administradores pueden eliminar productos terminados, y solo si no tienen dependencias.
        return $user->hasRole('administrador') &&
            !$productoTerminado->recetas()->exists() &&
            !$productoTerminado->ordenesProduccion()->exists() &&
            !$productoTerminado->lotes()->exists() &&
            !$productoTerminado->stockAlertas()->exists() &&
            !$productoTerminado->ajustesInventario()->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductoTerminado $productoTerminado): bool
    {
        return $user->hasRole('administrador');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProductoTerminado $productoTerminado): bool
    {
        return $user->hasRole('administrador');
    }
}
