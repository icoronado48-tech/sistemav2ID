<?php

namespace App\Policies;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientePolicy
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
        // Administradores y personal de ventas pueden ver clientes.
        return $user->hasAnyRole(['administrador', 'ventas']);
    }

    public function view(User $user, Cliente $cliente): bool
    {
        return $user->hasAnyRole(['administrador', 'ventas']);
    }

    public function create(User $user): bool
    {
        // Solo administradores o personal de ventas pueden crear clientes.
        return $user->hasAnyRole(['administrador', 'ventas']);
    }

    public function update(User $user, Cliente $cliente): bool
    {
        // Solo administradores o personal de ventas pueden actualizar clientes.
        return $user->hasAnyRole(['administrador', 'ventas']);
    }

    public function delete(User $user, Cliente $cliente): bool
    {
        // Solo administradores pueden eliminar clientes, si no tienen ventas/despachos asociados.
        return $user->hasRole('administrador') && !$cliente->ventasDespachos()->exists();
    }

    public function restore(User $user, Cliente $cliente): bool
    {
        return $user->hasRole('administrador');
    }

    public function forceDelete(User $user, Cliente $cliente): bool
    {
        return $user->hasRole('administrador');
    }
}
