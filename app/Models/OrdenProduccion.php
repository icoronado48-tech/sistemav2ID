<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrdenProduccion extends Model
{
    use HasFactory;

    protected $table = 'orden_produccion'; // Especifica el nombre de la tabla

    protected $fillable = [
        'producto_terminado_id',
        'cantidad_a_producir',
        'fecha_planificada_inicio',
        'fecha_planificada_fin',
        'fecha_real_inicio',
        'fecha_real_fin',
        'estado',
        'creada_por_user_id',
    ];

    protected $casts = [
        'cantidad_a_producir' => 'decimal:2',
        'fecha_planificada_inicio' => 'date',
        'fecha_planificada_fin' => 'date',
        'fecha_real_inicio' => 'date',
        'fecha_real_fin' => 'date',
    ];

    /**
     * Get the finished product associated with the production order.
     */
    public function productoTerminado(): BelongsTo
    {
        return $this->belongsTo(ProductoTerminado::class);
    }

    /**
     * Get the user who created the production order.
     */
    public function creadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por_user_id');
    }

    /**
     * Get the lots produced under this production order.
     */
    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class);
    }
}
