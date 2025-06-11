<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleVentaDespacho extends Model
{
    use HasFactory;

    protected $table = 'detalle_venta_despacho'; // Especifica el nombre de la tabla

    protected $fillable = [
        'venta_despacho_id',
        'lote_id',
        'cantidad_vendida_despachada',
        'precio_unitario',
    ];

    protected $casts = [
        'cantidad_vendida_despachada' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
    ];

    /**
     * Get the sale/dispatch that owns the detail.
     */
    public function ventaDespacho(): BelongsTo
    {
        return $this->belongsTo(VentaDespacho::class);
    }

    /**
     * Get the specific lot sold/dispatched in this detail.
     */
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class);
    }
}
