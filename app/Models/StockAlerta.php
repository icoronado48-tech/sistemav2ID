<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAlerta extends Model
{
    use HasFactory;

    protected $table = 'stock_alertas'; // Especifica el nombre de la tabla

    protected $fillable = [
        'materia_prima_id',
        'producto_terminado_id',
        'nivel_actual',
        'nivel_minimo',
        'tipo_alerta',
        'mensaje',
        'resuelta',
        'fecha_alerta',
        'generado_por_user_id',
    ];

    protected $casts = [
        'nivel_actual' => 'decimal:2',
        'nivel_minimo' => 'decimal:2',
        'resuelta' => 'boolean',
        'fecha_alerta' => 'datetime',
    ];

    /**
     * Get the raw material associated with the alert.
     */
    public function materiaPrima(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class);
    }

    /**
     * Get the finished product associated with the alert.
     */
    public function productoTerminado(): BelongsTo
    {
        return $this->belongsTo(ProductoTerminado::class);
    }

    /**
     * Get the user who generated the alert.
     */
    public function generadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generado_por_user_id');
    }
}
