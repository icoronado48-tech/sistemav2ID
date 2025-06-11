<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receta extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_terminado_id',
        'nombre_receta',
        'descripcion',
    ];

    /**
     * Get the finished product that owns the recipe.
     */
    public function productoTerminado(): BelongsTo
    {
        return $this->belongsTo(ProductoTerminado::class);
    }

    /**
     * Get the ingredients for the recipe.
     */
    public function ingredientes(): HasMany
    {
        return $this->hasMany(RecetaIngrediente::class);
    }
}
