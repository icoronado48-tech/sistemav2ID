<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecetaIngrediente extends Model
{
    use HasFactory;

    protected $table = 'receta_ingredientes'; // Especifica el nombre de la tabla

    protected $fillable = [
        'receta_id',
        'materia_prima_id',
        'cantidad_necesaria',
    ];

    protected $casts = [
        'cantidad_necesaria' => 'decimal:2',
    ];

    /**
     * Get the recipe that owns the ingredient.
     */
    public function receta(): BelongsTo
    {
        return $this->belongsTo(Receta::class);
    }

    /**
     * Get the raw material that is part of the ingredient.
     */
    public function materiaPrima(): BelongsTo
    {
        return $this->belongsTo(MateriaPrima::class);
    }
}
