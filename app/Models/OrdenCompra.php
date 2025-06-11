<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'orden_compra'; // Especifica el nombre de la tabla

    protected $fillable = [
        'proveedor_id',
        'creada_por_user_id',
        'fecha_orden',
        'fecha_entrega_estimada',
        'estado',
        'total_monto',
        'observaciones',
    ];

    protected $casts = [
        'fecha_orden' => 'date',
        'fecha_entrega_estimada' => 'date',
        'total_monto' => 'decimal:2',
    ];

    /**
     * Get the supplier for the purchase order.
     */
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * Get the user who created the purchase order.
     */
    public function creadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por_user_id');
    }

    /**
     * Get the details for the purchase order.
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleOrdenCompra::class);
    }

    /**
     * Get the raw material receptions associated with this purchase order.
     */
    public function recepcionesMateriaPrima(): HasMany
    {
        return $this->hasMany(RecepcionMateriaPrima::class);
    }
}
