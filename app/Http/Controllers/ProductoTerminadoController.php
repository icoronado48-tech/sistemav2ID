<?php

namespace App\Http\Controllers;

use App\Models\ProductoTerminado;
use Illuminate\Http\Request;
use App\Http\Requests\ProductoTerminado\StoreProductoTerminadoRequest;
use App\Http\Requests\ProductoTerminado\UpdateProductoTerminadoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductoTerminadoController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        $this->authorizeResource(ProductoTerminado::class, 'producto_terminado');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // La autorización (viewAny) ya se maneja automáticamente por authorizeResource.
        $productosTerminados = ProductoTerminado::paginate(10);
        return view('productos_terminados.index', compact('productosTerminados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // La autorización (create) ya se maneja automáticamente por authorizeResource.
        return view('productos_terminados.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoTerminadoRequest $request)
    {
        // La validación y autorización ya se manejan por StoreProductoTerminadoRequest y authorizeResource.
        try {
            DB::beginTransaction(); // Iniciar una transacción de base de datos

            ProductoTerminado::create($request->validated());

            DB::commit(); // Confirmar la transacción si todo es exitoso
            return redirect()->route('productos-terminados.index')->with('success', 'Producto terminado creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de cualquier error
            Log::error("Error al crear producto terminado: " . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]); // Registrar el error
            return back()->withInput()->with('error', 'Hubo un error al crear el producto terminado. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductoTerminado $productoTerminado)
    {
        // La autorización (view) ya se maneja automáticamente por authorizeResource.
        return view('productos_terminados.show', compact('productoTerminado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductoTerminado $productoTerminado)
    {
        // La autorización (update - implícita por ser un método de recurso) ya se maneja automáticamente.
        return view('productos_terminados.edit', compact('productoTerminado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoTerminadoRequest $request, ProductoTerminado $productoTerminado)
    {
        // La validación y autorización ya se manejan por UpdateProductoTerminadoRequest y authorizeResource.
        try {
            DB::beginTransaction(); // Iniciar una transacción

            $productoTerminado->update($request->validated());

            DB::commit(); // Confirmar la transacción
            return redirect()->route('productos-terminados.index')->with('success', 'Producto terminado actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción
            Log::error("Error al actualizar producto terminado: " . $e->getMessage(), ['exception' => $e, 'producto_terminado_id' => $productoTerminado->id, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Hubo un error al actualizar el producto terminado. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductoTerminado $productoTerminado)
    {
        // La autorización (delete) ya se maneja automáticamente por authorizeResource.
        try {
            // Realizamos verificaciones detalladas de dependencias debido a ON DELETE RESTRICT en el DER.
            // Esto proporciona mensajes amigables al usuario antes de que la base de datos lance un error.
            $dependencies = [];
            if ($productoTerminado->recetas()->exists()) $dependencies[] = 'recetas';
            if ($productoTerminado->ordenesProduccion()->exists()) $dependencies[] = 'órdenes de producción';
            if ($productoTerminado->lotes()->exists()) $dependencies[] = 'lotes';
            if ($productoTerminado->stockAlertas()->exists()) $dependencies[] = 'alertas de stock';
            if ($productoTerminado->ajustesInventario()->exists()) $dependencies[] = 'ajustes de inventario';

            if (!empty($dependencies)) {
                $message = 'No se puede eliminar el producto terminado porque tiene dependencias en: ' . implode(', ', $dependencies) . '. Por favor, elimine los registros relacionados primero.';
                return back()->with('error', $message);
            }

            DB::beginTransaction(); // Iniciar una transacción
            $productoTerminado->delete();
            DB::commit(); // Confirmar la transacción
            return redirect()->route('productos-terminados.index')->with('success', 'Producto terminado eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Captura una excepción específica si, por alguna razón, se intenta borrar
            // un producto terminado con dependencias que la política no capturó, o si hay otras FKs.
            Log::error("Error al eliminar producto terminado por restricción de clave foránea: " . $e->getMessage(), ['exception' => $e, 'producto_terminado_id' => $productoTerminado->id]);
            return back()->with('error', 'No se puede eliminar el producto terminado. Verifique si aún tiene registros dependientes inesperados.');
        } catch (\Exception $e) {
            // Captura cualquier otra excepción general
            Log::error("Error general al eliminar producto terminado: " . $e->getMessage(), ['exception' => $e, 'producto_terminado_id' => $productoTerminado->id]);
            return back()->with('error', 'Hubo un error inesperado al eliminar el producto terminado. Por favor, inténtelo de nuevo.');
        }
    }
}
