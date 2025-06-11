<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes'; // Especifica el nombre de la tabla

    protected $fillable = [
        'nombre_cliente',
        'cedula_rif',
        'telefono',
        'email',
        'direccion',
    ];

    /**
     * Get the sales/dispatches for the client.
     */
    public function ventasDespachos(): HasMany
    {
        return $this->hasMany(VentaDespacho::class);
    }
}
