<?php

namespace App\Policies;

use App\Models\VentaDespacho;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class VentaDespachoPolicy
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
        // Administradores y personal de ventas pueden ver ventas/despachos.
        return $user->hasAnyRole(['administrador', 'ventas', 'inventario']); // Inventario para visibilidad
    }

    public function view(User $user, VentaDespacho $ventaDespacho): bool
    {
        return $user->hasAnyRole(['administrador', 'ventas', 'inventario']);
    }

    public function create(User $user): bool
    {
        // Solo administradores o personal de ventas pueden crear ventas/despachos.
        return $user->hasAnyRole(['administrador', 'ventas']);
    }

    public function update(User $user, VentaDespacho $ventaDespacho): bool
    {
        // Solo administradores o personal de ventas pueden actualizar ventas/despachos
        // Y solo si no están ya en estado "despachado" o "completado"
        return $user->hasAnyRole(['administrador', 'ventas']) &&
            $ventaDespacho->estado_despacho !== 'despachado' && // O el estado final
            $ventaDespacho->estado_despacho !== 'completado';
    }

    public function delete(User $user, VentaDespacho $ventaDespacho): bool
    {
        // Solo administradores pueden eliminar ventas/despachos.
        // Esta acción revierte stock, lo cual es delicado.
        return $user->hasRole('administrador');
    }

    public function restore(User $user, VentaDespacho $ventaDespacho): bool
    {
        return $user->hasRole('administrador');
    }

    public function forceDelete(User $user, VentaDespacho $ventaDespacho): bool
    {
        return $user->hasRole('administrador');
    }
}
