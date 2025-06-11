<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores'; // Especifica el nombre de la tabla si no sigue la convención
    
    protected $fillable = [
        'nombre_proveedor',
        'contacto',
        'telefono',
        'email',
        'direccion',
    ];

    /**
     * Get the raw materials for the supplier.
     */
    public function materiasPrimas(): HasMany
    {
        return $this->hasMany(MateriaPrima::class);
    }

    /**
     * Get the purchase orders for the supplier.
     */
    public function ordenesCompra(): HasMany
    {
        return $this->hasMany(OrdenCompra::class);
    }
}