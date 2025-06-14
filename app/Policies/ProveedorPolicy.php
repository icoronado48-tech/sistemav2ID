<?php

namespace App\Policies;

use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProveedorPolicy
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
        // Solo administradores o personal de compras/inventario pueden ver proveedores.
        return $user->hasAnyRole(['administrador', 'inventario', 'compras']); // Asumiendo un rol 'compras'
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Proveedor $proveedor): bool
    {
        // Solo administradores o personal de compras/inventario pueden ver un proveedor específico.
        return $user->hasAnyRole(['administrador', 'inventario', 'compras']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Solo administradores o personal de compras pueden crear proveedores.
        return $user->hasAnyRole(['administrador', 'compras']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Proveedor $proveedor): bool
    {
        // Solo administradores o personal de compras pueden actualizar proveedores.
        return $user->hasAnyRole(['administrador', 'compras']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Proveedor $proveedor): bool
    {
        // Solo administradores pueden eliminar proveedores, y solo si no tienen órdenes de compra.
        // Esta comprobación se puede hacer también en el controlador para un mensaje más directo.
        return $user->hasRole('administrador') && !$proveedor->ordenesCompra()->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Proveedor $proveedor): bool
    {
        return $user->hasRole('administrador');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Proveedor $proveedor): bool
    {
        return $user->hasRole('administrador');
    }
}
