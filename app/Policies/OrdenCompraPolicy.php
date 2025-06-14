<?php

namespace App\Policies;

use App\Models\OrdenCompra;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrdenCompraPolicy
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
        // Administradores y personal de compras/inventario pueden ver órdenes de compra.
        return $user->hasAnyRole(['administrador', 'compras', 'inventario']);
    }

    public function view(User $user, OrdenCompra $ordenCompra): bool
    {
        return $user->hasAnyRole(['administrador', 'compras', 'inventario']);
    }

    public function create(User $user): bool
    {
        // Solo administradores o personal de compras pueden crear órdenes.
        return $user->hasAnyRole(['administrador', 'compras']);
    }

    public function update(User $user, OrdenCompra $ordenCompra): bool
    {
        // Administradores y personal de compras pueden actualizar órdenes.
        // Considera si se puede actualizar una orden en estado "completada" o "recibida".
        return $user->hasAnyRole(['administrador', 'compras']) &&
            $ordenCompra->estado !== 'completada'; // No se puede editar si está completada
    }

    public function delete(User $user, OrdenCompra $ordenCompra): bool
    {
        // Solo administradores pueden eliminar órdenes, si no tienen recepciones asociadas.
        return $user->hasRole('administrador') && !$ordenCompra->recepciones()->exists();
    }

    public function restore(User $user, OrdenCompra $ordenCompra): bool
    {
        return $user->hasRole('administrador');
    }

    public function forceDelete(User $user, OrdenCompra $ordenCompra): bool
    {
        return $user->hasRole('administrador');
    }

    // Podrías añadir habilidades para addDetalle, updateDetalle, removeDetalle si lo necesitas.
    // public function addDetalle(User $user, OrdenCompra $ordenCompra): bool { ... }
}
