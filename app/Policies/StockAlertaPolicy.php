<?php

namespace App\Policies;

use App\Models\StockAlerta;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StockAlertaPolicy
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
        // Solo administradores o personal de inventario/logística pueden ver alertas.
        return $user->hasAnyRole(['administrador', 'inventario', 'produccion']);
    }

    public function view(User $user, StockAlerta $stockAlerta): bool
    {
        return $user->hasAnyRole(['administrador', 'inventario', 'produccion']);
    }

    public function update(User $user, StockAlerta $stockAlerta): bool
    {
        // Solo administradores o personal de inventario pueden actualizar alertas.
        // Podrías añadir lógica para que solo se actualicen alertas no resueltas.
        return $user->hasAnyRole(['administrador', 'inventario']);
    }

    /**
     * Determine whether the user can mark an alert as resolved.
     */
    public function markAsResolved(User $user, StockAlerta $stockAlerta): bool
    {
        // Solo personal de inventario/administradores puede marcar alertas como resueltas
        // Y solo si la alerta aún no está resuelta.
        return $user->hasAnyRole(['administrador', 'inventario']) && !$stockAlerta->resuelta;
    }
}
