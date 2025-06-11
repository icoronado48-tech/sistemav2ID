<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecepcionMateriaPrima extends Model
{
    use HasFactory;

    protected $table = 'recepcion_materia_prima'; // Especifica el nombre de la tabla

    protected $fillable = [
        'orden_compra_id',
        'materia_prima_id',
        'cantidad_recibida',
        'fecha_recepcion',
        'numero_lote_proveedor',
        'recibido_por_user_id',
    ];

    protected $casts = [
        'cantidad_recibida' => 'decimal:2',
        'fecha_recepcion' => 'date',
    ];

    /**
     * Get the purchase order related to the reception.
     */
    public function ordenCompra(): BelongsTo
    {
        return $this->belongsTo(OrdenCompra::class);
    }

    /**
     * Get the raw material received.
     */
    public function materiaPrima(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class);
    }

    /**
     * Get the user who received the raw material.
     */
    public function recibidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recibido_por_user_id');
    }
}
