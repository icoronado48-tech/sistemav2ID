<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVentaDespacho extends Model
{
    use HasFactory;

    // ¡CORREGIDO! Especifica el nombre exacto de la tabla según tu migración
    protected $table = 'detalle_venta_despacho'; // Este es el nombre de la tabla en tu DB

    protected $fillable = [
        'venta_despacho_id',
        'lote_id',
        'cantidad_vendida_despachada',
        'precio_unitario',
        // Si 'subtotal' no es una columna real en la base de datos, no debería estar aquí
        // o si es una columna calculada en el modelo, la definición es diferente.
        // Asumiendo que 'subtotal' es una columna persistente en la tabla 'detalle_venta_despacho'.
        'subtotal',
    ];

    /**
     * Get the VentaDespacho that owns the DetalleVentaDespacho.
     */
    public function ventaDespacho()
    {
        return $this->belongsTo(VentaDespacho::class, 'venta_despacho_id');
    }

    /**
     * Get the Lote that owns the DetalleVentaDespacho.
     */
    public function lote()
    {
        return $this->belongsTo(Lote::class, 'lote_id');
    }
}
