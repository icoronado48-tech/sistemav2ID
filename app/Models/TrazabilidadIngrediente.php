<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrazabilidadIngrediente extends Model
{
    use HasFactory;

    protected $table = 'trazabilidad_ingredientes'; // Especifica el nombre de la tabla

    protected $fillable = [
        'lote_id',
        'materia_prima_id',
        'cantidad_utilizada',
        'fecha_registro',
    ];

    protected $casts = [
        'cantidad_utilizada' => 'decimal:2',
        'fecha_registro' => 'date',
    ];

    /**
     * Get the lot that used this ingredient.
     */
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class);
    }

    /**
     * Get the raw material used in this traceability record.
     */
    public function materiaPrima(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class);
    }
}
