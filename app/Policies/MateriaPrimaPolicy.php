<?php

namespace App\Policies;

use App\Models\MateriaPrima;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MateriaPrimaPolicy
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
        // Solo administradores, inventario o producción pueden ver materias primas.
        return $user->hasAnyRole(['administrador', 'inventario', 'produccion']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MateriaPrima $materiaPrima): bool
    {
        // Solo administradores, inventario o producción pueden ver una materia prima específica.
        return $user->hasAnyRole(['administrador', 'inventario', 'produccion']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Solo administradores o personal de inventario/compras pueden crear materias primas.
        return $user->hasAnyRole(['administrador', 'inventario', 'compras']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MateriaPrima $materiaPrima): bool
    {
        // Solo administradores o personal de inventario/compras pueden actualizar materias primas.
        return $user->hasAnyRole(['administrador', 'inventario', 'compras']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MateriaPrima $materiaPrima): bool
    {
        // Solo administradores pueden eliminar materias primas, y solo si no tienen dependencias.
        // Esta comprobación se puede hacer también en el controlador para un mensaje más directo.
        // Verifica todas las relaciones HasMany que tienen ON DELETE RESTRICT
        return $user->hasRole('administrador') &&
            !$materiaPrima->recetaIngredientes()->exists() &&
            !$materiaPrima->trazabilidadIngredientes()->exists() &&
            !$materiaPrima->stockAlertas()->exists() &&
            !$materiaPrima->detalleOrdenesCompra()->exists() &&
            !$materiaPrima->recepcionesMateriaPrima()->exists() &&
            !$materiaPrima->ajustesInventario()->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MateriaPrima $materiaPrima): bool
    {
        return $user->hasRole('administrador');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MateriaPrima $materiaPrima): bool
    {
        return $user->hasRole('administrador');
    }
}
