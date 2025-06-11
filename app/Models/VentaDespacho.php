<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VentaDespacho extends Model
{
    use HasFactory;

    protected $table = 'ventas_despachos'; // Especifica el nombre de la tabla

    protected $fillable = [
        'cliente_id',
        'fecha_venta_despacho',
        'tipo_documento',
        'numero_documento',
        'total_monto',
        'estado_despacho',
        'registrado_por_user_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha_venta_despacho' => 'date',
        'total_monto' => 'decimal:2',
    ];

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
