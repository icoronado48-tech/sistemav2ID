<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleOrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'detalle_orden_compra'; // Especifica el nombre de la tabla

    protected $fillable = [
        'orden_compra_id',
        'materia_prima_id',
        'cantidad',
        'precio_unitario',
        // 'subtotal', // ¡ELIMINADO! Ya no es una columna de la base de datos.
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        // 'subtotal' => 'decimal:2', // ¡ELIMINADO! Ya no es una columna de la base de datos.
    ];

    /**
     * Get the subtotal for this purchase order detail.
     * This is an Accessor that calculates the subtotal dynamically.
     *
     * @return float
     */
    public function getSubtotalAttribute(): float
    {
        return $this->cantidad * $this->precio_unitario;
    }

    /**
     * Get the purchase order that owns the detail.
     */
    public function ordenCompra(): BelongsTo
    {
        return $this->belongsTo(OrdenCompra::class);
    }

    /**
     * Get the raw material for this detail.
     */
    public function materiaPrima(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class);
    }
}
