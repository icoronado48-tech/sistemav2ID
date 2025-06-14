<?php

namespace App\Http\Controllers;

use App\Models\DetalleVentaDespacho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Asegúrate de que esta línea esté aquí

// Este controlador solo tendrá métodos de lectura para los detalles de venta/despacho.
// Los métodos de creación, actualización y eliminación se gestionan
// dentro del VentaDespachoController para mantener la consistencia transaccional.

class DetalleVentaDespachoController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        $this->authorizeResource(DetalleVentaDespacho::class, 'detalle_venta_despacho', [
            'except' => ['create', 'store', 'edit', 'update', 'destroy']
        ]);
    }

    /**
     * Display a listing of the resource.
     * Permite ver una lista paginada de todos los detalles de venta/despacho.
     */
    public function index()
    {
        try {
            // La autorización (viewAny) ya se maneja automáticamente por authorizeResource.
            // Carga las relaciones necesarias para evitar el problema N+1.
            $detalles = DetalleVentaDespacho::with('ventaDespacho', 'lote.productoTerminado')->paginate(10);
            return view('detalle_ventas_despachos.index', compact('detalles'));
        } catch (\Exception $e) {
            Log::error("Error al listar detalles de venta/despacho: " . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'Hubo un error al cargar los detalles de venta. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     * Permite ver los detalles de un DetalleVentaDespacho específico.
     */
    public function show(DetalleVentaDespacho $detalleVentaDespacho)
    {
        try {
            // La autorización (view) ya se maneja automáticamente por authorizeResource.
            // Carga las relaciones necesarias para la vista de detalle.
            $detalleVentaDespacho->load('ventaDespacho', 'lote.productoTerminado');
            return view('detalle_ventas_despachos.show', compact('detalleVentaDespacho'));
        } catch (\Exception $e) {
            Log::error("Error al mostrar detalle de venta/despacho {$detalleVentaDespacho->id}: " . $e->getMessage(), ['exception' => $e, 'detalle_id' => $detalleVentaDespacho->id]);
            return back()->with('error', 'Hubo un error al cargar el detalle de venta. Por favor, inténtelo de nuevo.');
        }
    }

    // Los métodos `store`, `update`, `destroy` están excluidos en el constructor
    // ya que su lógica se gestiona en el VentaDespachoController.
}
