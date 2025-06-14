<?php

namespace App\Policies;

use App\Models\DetalleVentaDespacho;
use App\Models\User; // Asegúrate de importar el modelo User
use Illuminate\Auth\Access\Response;

class DetalleVentaDespachoPolicy
{
    // Opcional: El método `before` se ejecuta antes que cualquier otro método de política.
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('administrador')) { // Usamos el método hasRole del modelo User
            return true;
        }
        return null; // Si devuelve null, Laravel continuará con los demás métodos de la política.
    }

    /**
     * Determine whether the user can view any models (i.e., list all details).
     */
    public function viewAny(User $user): bool
    {
        // Solo administradores, ventas o inventario pueden ver la lista de detalles de venta.
        return $user->hasAnyRole(['administrador', 'ventas', 'inventario']);
    }

    /**
     * Determine whether the user can view the model (i.e., see a specific detail).
     */
    public function view(User $user, DetalleVentaDespacho $detalleVentaDespacho): bool
    {
        // Solo administradores, ventas o inventario pueden ver un detalle de venta específico.
        return $user->hasAnyRole(['administrador', 'ventas', 'inventario']);
    }

    /**
     * Determine whether the user can create models.
     * (Los detalles se crean a través de VentaDespachoController, no directamente)
     */
    public function create(User $user): bool
    {
        return false; // No se permite la creación directa de detalles de venta
    }

    /**
     * Determine whether the user can update the model.
     * (Las actualizaciones se gestionan a través de VentaDespachoController)
     */
    public function update(User $user, DetalleVentaDespacho $detalleVentaDespacho): bool
    {
        return false; // No se permite la actualización directa de detalles de venta
    }

    /**
     * Determine whether the user can delete the model.
     * (Las eliminaciones se gestionan a través de VentaDespachoController)
     */
    public function delete(User $user, DetalleVentaDespacho $detalleVentaDespacho): bool
    {
        return false; // No se permite la eliminación directa de detalles de venta
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DetalleVentaDespacho $detalleVentaDespacho): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DetalleVentaDespacho $detalleVentaDespacho): bool
    {
        return false;
    }
}
