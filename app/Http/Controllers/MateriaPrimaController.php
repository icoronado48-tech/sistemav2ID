<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrima;
use App\Models\Proveedor; // Importa el modelo Proveedor
use Illuminate\Http\Request;
use App\Http\Requests\MateriaPrima\StoreMateriaPrimaRequest;
use App\Http\Requests\MateriaPrima\UpdateMateriaPrimaRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MateriaPrimaController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        $this->authorizeResource(MateriaPrima::class, 'materia_prima');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Carga ansiosamente la relación 'proveedor' para evitar N+1 queries.
        $materiasPrimas = MateriaPrima::with('proveedor')->paginate(10);
        return view('materias_primas.index', compact('materiasPrimas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Carga todos los proveedores para poder seleccionarlos en el formulario.
        $proveedores = Proveedor::all();
        return view('materias_primas.create', compact('proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMateriaPrimaRequest $request)
    {
        try {
            DB::beginTransaction();

            MateriaPrima::create($request->validated());

            DB::commit();
            return redirect()->route('materias-primas.index')->with('success', 'Materia prima creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al crear materia prima: " . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Hubo un error al crear la materia prima. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MateriaPrima $materiaPrima)
    {
        // Carga ansiosamente la relación 'proveedor' para la vista de detalle.
        $materiaPrima->load('proveedor');
        return view('materias_primas.show', compact('materiaPrima'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MateriaPrima $materiaPrima)
    {
        // Carga todos los proveedores para poder seleccionarlos en el formulario de edición.
        $proveedores = Proveedor::all();
        return view('materias_primas.edit', compact('materiaPrima', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMateriaPrimaRequest $request, MateriaPrima $materiaPrima)
    {
        try {
            DB::beginTransaction();

            $materiaPrima->update($request->validated());

            DB::commit();
            return redirect()->route('materias-primas.index')->with('success', 'Materia prima actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar materia prima: " . $e->getMessage(), ['exception' => $e, 'materia_prima_id' => $materiaPrima->id, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Hubo un error al actualizar la materia prima. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MateriaPrima $materiaPrima)
    {
        try {
            // Realiza verificaciones detalladas de dependencias debido a ON DELETE RESTRICT en el DER.
            // Esto proporciona mensajes amigables al usuario antes de que la DB lance un error.
            $dependencies = [];
            if ($materiaPrima->recetaIngredientes()->exists()) {
                $dependencies[] = 'ingredientes de recetas';
            }
            if ($materiaPrima->trazabilidadIngredientes()->exists()) {
                $dependencies[] = 'registros de trazabilidad';
            }
            if ($materiaPrima->stockAlertas()->exists()) {
                $dependencies[] = 'alertas de stock';
            }
            if ($materiaPrima->detalleOrdenesCompra()->exists()) {
                $dependencies[] = 'detalles de órdenes de compra';
            }
            if ($materiaPrima->recepcionesMateriaPrima()->exists()) {
                $dependencies[] = 'recepciones de materia prima';
            }
            if ($materiaPrima->ajustesInventario()->exists()) {
                $dependencies[] = 'ajustes de inventario';
            }

            if (!empty($dependencies)) {
                $message = 'No se puede eliminar la materia prima porque tiene dependencias en: ' . implode(', ', $dependencies) . '. Por favor, elimine los registros relacionados primero.';
                return back()->with('error', $message);
            }

            DB::beginTransaction(); // Iniciar una transacción
            $materiaPrima->delete();
            DB::commit(); // Confirmar la transacción
            return redirect()->route('materias-primas.index')->with('success', 'Materia prima eliminada exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Captura específica para errores de clave foránea si las verificaciones previas fallan.
            Log::error("Error al eliminar materia prima por restricción de clave foránea: " . $e->getMessage(), ['exception' => $e, 'materia_prima_id' => $materiaPrima->id]);
            return back()->with('error', 'No se puede eliminar la materia prima. Verifique si aún tiene registros dependientes inesperados.');
        } catch (\Exception $e) {
            // Captura cualquier otra excepción general.
            Log::error("Error general al eliminar materia prima: " . $e->getMessage(), ['exception' => $e, 'materia_prima_id' => $materiaPrima->id]);
            return back()->with('error', 'Hubo un error inesperado al eliminar la materia prima. Por favor, inténtelo de nuevo.');
        }
    }
}
