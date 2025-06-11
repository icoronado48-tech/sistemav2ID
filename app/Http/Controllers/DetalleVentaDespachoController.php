<?php

namespace App\Http\Controllers;

use App\Models\DetalleVentaDespacho;
use Illuminate\Http\Request;

// Este controlador solo tendría sentido si quieres una API para los detalles
// o si no quieres anidarlos en VentaDespachoController.
// Generalmente, los métodos de creación, actualización y eliminación
// de DetalleVentaDespacho se manejan dentro de VentaDespachoController
// para asegurar la consistencia transaccional con la VentaDespacho padre.

class DetalleVentaDespachoController extends Controller
{
    // Métodos CRUD básicos si fueran rutas independientes.
    // Pero es fuertemente recomendado manejarlos anidados.
    public function index()
    {
        $detalles = DetalleVentaDespacho::with('ventaDespacho', 'lote.productoTerminado')->paginate(10);
        return view('detalle_ventas_despachos.index', compact('detalles'));
    }

    public function show(DetalleVentaDespacho $detalleVentaDespacho)
    {
        $detalleVentaDespacho->load('ventaDespacho', 'lote.productoTerminado');
        return view('detalle_ventas_despachos.show', compact('detalleVentaDespacho'));
    }

    // store, update, delete usualmente se harían vía VentaDespachoController
}
