<?php

namespace App\Policies;

use App\Models\ReporteProduccion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReporteProduccionPolicy
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
        // Administradores, producción e inventario pueden ver reportes de producción.
        return $user->hasAnyRole(['administrador', 'produccion', 'inventario']);
    }

    public function view(User $user, ReporteProduccion $reporteProduccion): bool
    {
        return $user->hasAnyRole(['administrador', 'produccion', 'inventario']);
    }

    public function create(User $user): bool
    {
        // Solo administradores o personal de producción pueden generar reportes.
        return $user->hasAnyRole(['administrador', 'produccion']);
    }

    // No update, delete, restore, forceDelete methods as per business logic (reports are historical)
    public function update(User $user, ReporteProduccion $reporteProduccion): bool
    {
        return false;
    }
    public function delete(User $user, ReporteProduccion $reporteProduccion): bool
    {
        return false;
    }
    public function restore(User $user, ReporteProduccion $reporteProduccion): bool
    {
        return false;
    }
    public function forceDelete(User $user, ReporteProduccion $reporteProduccion): bool
    {
        return false;
    }
}
