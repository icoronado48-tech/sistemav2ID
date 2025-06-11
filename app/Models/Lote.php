<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lotes'; // Especifica el nombre de la tabla

    protected $fillable = [
        'orden_produccion_id',
        'producto_terminado_id',
        'cantidad_producida',
        'fecha_produccion',
        'fecha_vencimiento',
        'estado_calidad',
        'observaciones_calidad',
        'registrado_por_user_id',
    ];

    protected $casts = [
        'cantidad_producida' => 'decimal:2',
        'fecha_produccion' => 'date',
        'fecha_vencimiento' => 'date',
    ];

    /**
     * Get the production order that this lot belongs to.
     */
    public function ordenProduccion(): BelongsTo
    {
        return $this->belongsTo(OrdenProduccion::class);
    }

    /**
     * Get the finished product this lot is comprised of.
     */
    public function productoTerminado(): BelongsTo
    {
        return $this->belongsTo(ProductoTerminado::class);
    }

    /**
     * Get the user who registered this lot.
     */
    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por_user_id');
    }

    /**
     * Get the traceability records for this lot.
     */
    public function trazabilidadIngredientes(): HasMany
    {
        return $this->hasMany(TrazabilidadIngrediente::class);
    }

    /**
     * Get the quality controls for this lot.
     */
    public function controlesCalidad(): HasMany
    {
        return $this->hasMany(ControlCalidad::class);
    }

    /**
     * Get the sales/dispatch details for this lot.
     */
    public function detallesVentaDespacho(): HasMany
    {
        return $this->hasMany(DetalleVentaDespacho::class);
    }
}
