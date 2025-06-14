<?php

namespace App\Policies;

use App\Models\RecepcionMateriaPrima;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecepcionMateriaPrimaPolicy
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
        // Solo administradores o personal de inventario/logística pueden ver recepciones.
        return $user->hasAnyRole(['administrador', 'inventario', 'logistica']); // Asumiendo un rol 'logistica'
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RecepcionMateriaPrima $recepcionMateriaPrima): bool
    {
        // Solo administradores o personal de inventario/logística pueden ver una recepción específica.
        return $user->hasAnyRole(['administrador', 'inventario', 'logistica']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Solo administradores o personal de inventario/logística pueden crear recepciones.
        return $user->hasAnyRole(['administrador', 'inventario', 'logistica']);
    }

    /**
     * Determine whether the user can update the model.
     * By default, returns false as per business rule (no updates for receptions).
     */
    public function update(User $user, RecepcionMateriaPrima $recepcionMateriaPrima): bool
    {
        // Según la nota en el controlador, las recepciones no se editan.
        // Si esto cambia, ajusta la lógica aquí.
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * By default, returns false as per business rule (no deletions for receptions).
     */
    public function delete(User $user, RecepcionMateriaPrima $recepcionMateriaPrima): bool
    {
        // Según la nota en el controlador, las recepciones no se eliminan.
        // Si esto cambia, ajusta la lógica aquí.
        return false;
    }

    // `restore` y `forceDelete` también pueden retornar false si no se usan soft deletes.
}
