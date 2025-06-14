<?php

namespace App\Policies;

use App\Models\ControlCalidad;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ControlCalidadPolicy
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
        // Administradores y personal de calidad/producción/inventario pueden ver controles de calidad.
        return $user->hasAnyRole(['administrador', 'calidad', 'produccion', 'inventario']);
    }

    public function view(User $user, ControlCalidad $controlCalidad): bool
    {
        return $user->hasAnyRole(['administrador', 'calidad', 'produccion', 'inventario']);
    }

    public function create(User $user): bool
    {
        // Solo administradores o personal de calidad pueden crear controles.
        return $user->hasAnyRole(['administrador', 'calidad']);
    }

    public function update(User $user, ControlCalidad $controlCalidad): bool
    {
        // Solo administradores o personal de calidad pueden actualizar controles.
        // Considera si se puede actualizar un control ya "finalizado" (aprobado/rechazado).
        return $user->hasAnyRole(['administrador', 'calidad']);
    }

    public function delete(User $user, ControlCalidad $controlCalidad): bool
    {
        // La eliminación de un control de calidad es delicada, ya que suprime un historial.
        // Se recomienda no permitir la eliminación o solo soft delete.
        // Si se permite, solo administradores.
        return $user->hasRole('administrador');
    }

    public function restore(User $user, ControlCalidad $controlCalidad): bool
    {
        return $user->hasRole('administrador');
    }

    public function forceDelete(User $user, ControlCalidad $controlCalidad): bool
    {
        return $user->hasRole('administrador');
    }
}
