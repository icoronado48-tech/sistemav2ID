<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VentaDespacho extends Model
{
    use HasFactory;

    protected $table = 'ventas_despachos'; // Specify the table name if it doesn't follow convention

    protected $fillable = [
        'cliente_id',
        'fecha_venta_despacho',
        'tipo_documento',
        'numero_documento',
        // 'total_monto', // REMOVED! It's no longer a database column.
        'estado_despacho',
        'registrado_por_user_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha_venta_despacho' => 'date',
        // 'total_monto' => 'decimal:2', // REMOVED! It's no longer a database column.
    ];

    /**
     * Get the total amount of the sale/dispatch.
     * This is an Accessor that calculates the total dynamically.
     *
     * @return float
     */
    public function getTotalMontoAttribute(): float
    {
        // Ensure that the sales details are eager loaded
        // (e.g., VentaDespacho::with('detallesVenta')->find($id);)
        // to avoid N+1 query issues when accessing this attribute.
        if ($this->relationLoaded('detallesVenta')) {
            return $this->detallesVenta->sum(function ($detalle) {
                // Access the calculated subtotal from DetalleVentaDespacho model's accessor
                return $detalle->cantidad_vendida_despachada * $detalle->precio_unitario;
            });
        }
        return 0.00; // Return 0 if details are not loaded.
    }

    /**
     * Get the client associated with the sale/dispatch.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Get the user who registered the sale/dispatch.
     */
    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por_user_id');
    }

    /**
     * Get the details of the sale/dispatch.
     */
    public function detallesVenta(): HasMany
    {
        return $this->hasMany(DetalleVentaDespacho::class);
    }
}
