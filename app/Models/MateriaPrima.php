<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MateriaPrima extends Model
{
    use HasFactory;

    protected $table = 'materias_primas'; // Especifica el nombre de la tabla

    protected $fillable = [
        'nombre',
        'unidad_medida',
        'stock_actual',
        'stock_minimo',
        'proveedor_id',
    ];

    protected $casts = [
        'stock_actual' => 'decimal:2',
        'stock_minimo' => 'decimal:2',
    ];

    /**
     * Get the supplier that owns the raw material.
     */
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * Get the recipe ingredients that use this raw material.
     */
    public function recetaIngredientes(): HasMany
    {
        return $this->hasMany(RecetaIngrediente::class);
    }

    /**
     * Get the traceability records for this raw material.
     */
    public function trazabilidadIngredientes(): HasMany
    {
        return $this->hasMany(TrazabilidadIngrediente::class);
    }

    /**
     * Get the stock alerts for this raw material.
     */
    public function stockAlertas(): HasMany
    {
        return $this->hasMany(StockAlerta::class);
    }

    /**
     * Get the details of purchase orders for this raw material.
     */
    public function detalleOrdenesCompra(): HasMany
    {
        return $this->hasMany(DetalleOrdenCompra::class);
    }

    /**
     * Get the raw material receptions for this raw material.
     */
    public function recepcionesMateriaPrima(): HasMany
    {
        return $this->hasMany(RecepcionMateriaPrima::class);
    }

    /**
     * Get the inventory adjustments for this raw material.
     */
    public function ajustesInventario(): HasMany
    {
        return $this->hasMany(AjusteInventario::class);
    }
}
