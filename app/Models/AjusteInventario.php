<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AjusteInventario extends Model
{
    use HasFactory;

    protected $table = 'ajuste_inventario'; // Especifica el nombre de la tabla

    protected $fillable = [
        'materia_prima_id',
        'producto_terminado_id',
        'cantidad_ajustada',
        'tipo_ajuste',
        'motivo',
        'fecha_ajuste',
        'realizado_por_user_id',
    ];

    protected $casts = [
        'cantidad_ajustada' => 'decimal:2',
        'fecha_ajuste' => 'date',
    ];

    /**
     * Get the raw material associated with the adjustment (if applicable).
     */
    public function materiaPrima(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class);
    }

    /**
     * Get the finished product associated with the adjustment (if applicable).
     */
    public function productoTerminado(): BelongsTo
    {
        return $this->belongsTo(ProductoTerminado::class);
    }

    /**
     * Get the user who made the inventory adjustment.
     */
    public function realizadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'realizado_por_user_id');
    }
}
