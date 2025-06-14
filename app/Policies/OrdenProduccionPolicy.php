<?php

namespace App\Policies;

use App\Models\OrdenProduccion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrdenProduccionPolicy
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
        // Administradores, Producción y Logística pueden ver órdenes de producción
        return $user->hasAnyRole(['administrador', 'produccion', 'inventario']);
    }

    public function view(User $user, OrdenProduccion $ordenProduccion): bool
    {
        return $user->hasAnyRole(['administrador', 'produccion', 'inventario']);
    }

    public function create(User $user): bool
    {
        // Administradores y Producción pueden crear órdenes
        return $user->hasAnyRole(['administrador', 'produccion']);
    }

    public function update(User $user, OrdenProduccion $ordenProduccion): bool
    {
        // Administradores y Producción pueden actualizar órdenes, si no están ya completadas/canceladas
        return $user->hasAnyRole(['administrador', 'produccion']) &&
            $ordenProduccion->estado !== 'completada' &&
            $ordenProduccion->estado !== 'cancelada';
    }

    public function delete(User $user, OrdenProduccion $ordenProduccion): bool
    {
        // Administradores pueden eliminar órdenes si no tienen lotes asociados
        return $user->hasRole('administrador') && !$ordenProduccion->lotes()->exists();
    }

    public function restore(User $user, OrdenProduccion $ordenProduccion): bool
    {
        return $user->hasRole('administrador');
    }

    public function forceDelete(User $user, OrdenProduccion $ordenProduccion): bool
    {
        return $user->hasRole('administrador');
    }

    /**
     * Determine whether the user can update the status of the production order.
     * This is a custom ability.
     */
    public function updateStatus(User $user, OrdenProduccion $ordenProduccion): bool
    {
        // Solo administradores o personal de producción pueden cambiar el estado
        // Y solo si la orden no está ya completada o cancelada (a menos que se permita cambiar de cancelada a otra)
        return $user->hasAnyRole(['administrador', 'produccion']) &&
            $ordenProduccion->estado !== 'completada' &&
            $ordenProduccion->estado !== 'cancelada';
    }
}
