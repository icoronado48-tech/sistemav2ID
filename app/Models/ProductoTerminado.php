<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductoTerminado extends Model
{
    use HasFactory;

    protected $table = 'producto_terminados'; // Especifica el nombre de la tabla

    protected $fillable = [
        'nombre_producto',
        'descripcion',
        'unidad_medida_salida',
        'stock_actual',
    ];

    protected $casts = [
        'stock_actual' => 'decimal:2',
    ];

    /**
     * Get the recipes for the finished product.
     */
    public function recetas(): HasMany
    {
        return $this->hasMany(Receta::class);
    }

    /**
     * Get the production orders for the finished product.
     */
    public function ordenesProduccion(): HasMany
    {
        return $this->hasMany(OrdenProduccion::class);
    }

    /**
     * Get the lots for the finished product.
     */
    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class);
    }

    /**
     * Get the stock alerts for this finished product.
     */
    public function stockAlertas(): HasMany
    {
        return $this->hasMany(StockAlerta::class);
    }

    /**
     * Get the inventory adjustments for this finished product.
     */
    public function ajustesInventario(): HasMany
    {
        return $this->hasMany(AjusteInventario::class);
    }
}
