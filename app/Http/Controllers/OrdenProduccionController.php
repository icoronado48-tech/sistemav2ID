<?php

namespace App\Http\Controllers;

use App\Models\OrdenProduccion;
use App\Models\ProductoTerminado;
use App\Models\MateriaPrima;
use App\Models\Lote;
use App\Models\User;
use App\Models\Receta; // Make sure this is imported
use App\Models\TrazabilidadIngrediente; // Make sure this is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // For Auth::id()
use App\Http\Requests\OrdenProduccion\StoreOrdenProduccionRequest;
use App\Http\Requests\OrdenProduccion\UpdateOrdenProduccionRequest;
use App\Http\Requests\OrdenProduccion\UpdateOrdenProduccionStatusRequest;

class OrdenProduccionController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        // Authorize all standard resource methods
        $this->authorizeResource(OrdenProduccion::class, 'orden_produccion');

        // Also authorize the custom 'updateStatus' method.
        // The 'can' middleware checks the policy method.
        $this->middleware('can:updateStatus,orden_produccion')->only('updateStatus');
    }

    public function index()
    {
        $ordenes = OrdenProduccion::with('productoTerminado', 'creadaPor')->paginate(10);
        return view('ordenes_produccion.index', compact('ordenes'));
    }

    public function create()
    {
        $productosTerminados = ProductoTerminado::all();
        // Filter users to only those with 'produccion' or 'administrador' roles
        $users = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['administrador', 'produccion']);
        })->get();
        return view('ordenes_produccion.create', compact('productosTerminados', 'users'));
    }

    public function store(StoreOrdenProduccionRequest $request)
    {
        try {
            DB::beginTransaction();

            $productoTerminado = ProductoTerminado::findOrFail($request->producto_terminado_id);

            // Logic to verify raw material availability
            $receta = $productoTerminado->recetas()->first(); // Assuming 1 recipe per PT
            if (!$receta) {
                DB::rollBack();
                Log::warning("No recipe defined for finished product {$productoTerminado->id} during order creation.", ['product_id' => $productoTerminado->id]);
                return back()->withInput()->with('error', 'El producto terminado no tiene una receta definida. No se puede crear la orden de producción.');
            }

            foreach ($receta->ingredientes as $recetaIngrediente) {
                $materiaPrima = $recetaIngrediente->materiaPrima;
                $cantidadNecesaria = $recetaIngrediente->cantidad_necesaria * $request->cantidad_a_producir;

                if (!$materiaPrima) {
                    DB::rollBack();
                    Log::error("Materia prima con ID {$recetaIngrediente->materia_prima_id} no encontrada en la receta {$receta->id}. Posible inconsistencia de datos.");
                    return back()->withInput()->with('error', 'Materia prima en la receta no encontrada. Contacte a soporte.');
                }

                if ($materiaPrima->stock_actual < $cantidadNecesaria) {
                    DB::rollBack();
                    return back()->withInput()->with('error', "Stock insuficiente de {$materiaPrima->nombre}. Necesitas {$cantidadNecesaria} {$materiaPrima->unidad_medida}. Solo tienes {$materiaPrima->stock_actual}.");
                }
            }

            $orden = OrdenProduccion::create($request->validated());

            DB::commit();
            return redirect()->route('ordenes-produccion.index')->with('success', 'Orden de producción creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al crear la orden de producción: " . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Error al crear la orden de producción: ' . $e->getMessage());
        }
    }

    public function show(OrdenProduccion $ordenProduccion)
    {
        $ordenProduccion->load('productoTerminado', 'creadaPor', 'lotes');
        return view('ordenes_produccion.show', compact('ordenProduccion'));
    }

    public function edit(OrdenProduccion $ordenProduccion)
    {
        $productosTerminados = ProductoTerminado::all();
        $users = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['administrador', 'produccion']);
        })->get();
        return view('ordenes_produccion.edit', compact('ordenProduccion', 'productosTerminados', 'users'));
    }

    public function update(UpdateOrdenProduccionRequest $request, OrdenProduccion $ordenProduccion)
    {
        try {
            DB::beginTransaction();
            $ordenProduccion->update($request->validated());
            DB::commit();
            return redirect()->route('ordenes-produccion.index')->with('success', 'Orden de producción actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar la orden de producción: " . $e->getMessage(), ['exception' => $e, 'order_id' => $ordenProduccion->id, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Error al actualizar la orden de producción: ' . $e->getMessage());
        }
    }

    public function destroy(OrdenProduccion $ordenProduccion)
    {
        try {
            if ($ordenProduccion->lotes()->exists()) {
                return back()->with('error', 'No se puede eliminar una orden de producción que ya tiene lotes asociados.');
            }
            DB::beginTransaction();
            $ordenProduccion->delete();
            DB::commit();
            return redirect()->route('ordenes-produccion.index')->with('success', 'Orden de producción eliminada exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Error al eliminar orden de producción por FK: " . $e->getMessage(), ['exception' => $e, 'order_id' => $ordenProduccion->id]);
            return back()->with('error', 'No se puede eliminar la orden de producción. Verifique si aún tiene registros dependientes inesperados.');
        } catch (\Exception $e) {
            Log::error("Error general al eliminar orden de producción: " . $e->getMessage(), ['exception' => $e, 'order_id' => $ordenProduccion->id]);
            return back()->with('error', 'Hubo un error inesperado al eliminar la orden de producción. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Method to update the production order status.
     */
    public function updateStatus(UpdateOrdenProduccionStatusRequest $request, OrdenProduccion $ordenProduccion)
    {
        // Authorization handled by middleware 'can:updateStatus,orden_produccion'
        try {
            DB::beginTransaction();
            $oldStatus = $ordenProduccion->estado;
            $newStatus = $request->estado;

            $ordenProduccion->update(['estado' => $newStatus]);

            // Logic to handle important status changes
            if ($newStatus === 'en_proceso' && $oldStatus === 'pendiente') {
                $ordenProduccion->update(['fecha_real_inicio' => Carbon::now()]);
            } elseif ($newStatus === 'completada' && $oldStatus !== 'completada') {
                $ordenProduccion->update(['fecha_real_fin' => Carbon::now()]);

                // Generate lot upon order completion
                $lote = $this->generateLote($ordenProduccion); // Capture the generated lot

                // Deduct consumed raw material stock AND create traceability records
                $this->deductMateriaPrimaStock($ordenProduccion, $lote);
            } elseif ($newStatus === 'cancelada' && $oldStatus !== 'cancelada') {
                // Logic if an order is cancelled (e.g., release raw material if already reserved)
            }

            DB::commit();
            return redirect()->route('ordenes-produccion.index')->with('success', "Estado de la orden de producción actualizado a {$newStatus}.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar el estado de la orden de producción: " . $e->getMessage(), ['exception' => $e, 'order_id' => $ordenProduccion->id, 'new_status' => $request->estado]);
            return back()->with('error', 'Error al actualizar el estado: ' . $e->getMessage());
        }
    }

    /**
     * Logic to generate a lot upon completing a production order.
     * This could be a private method, a Job, or an Event/Listener.
     *
     * @param OrdenProduccion $ordenProduccion The production order.
     * @return Lote The created lot.
     * @throws \Exception If a lot already exists or recipe is not found.
     */
    private function generateLote(OrdenProduccion $ordenProduccion): Lote
    {
        // Validate if the lot already exists or if the order was already completed and lot-generated
        if ($ordenProduccion->lotes()->exists()) {
            throw new \Exception('Esta orden de producción ya tiene un lote asociado. No se puede generar un nuevo lote.');
        }

        $lote = Lote::create([
            'orden_produccion_id' => $ordenProduccion->id,
            'producto_terminado_id' => $ordenProduccion->producto_terminado_id,
            'cantidad_producida' => $ordenProduccion->cantidad_a_producir, // Or the actual produced quantity if captured
            'fecha_produccion' => Carbon::now(),
            'fecha_vencimiento' => Carbon::now()->addDays(30), // Calculated expiration date
            'estado_calidad' => 'Pendiente', // By default, quality control is required
            'observaciones_calidad' => null,
            'registrado_por_user_id' => Auth::id(), // The logged-in user who completes the order
        ]);

        // Increment the stock of the Finished Product
        $ordenProduccion->productoTerminado->increment('stock_actual', $ordenProduccion->cantidad_a_producir);

        return $lote; // Return the created lot
    }

    /**
     * Logic to deduct raw material stock and create traceability records.
     * This could be a private method, a Job, or an Event/Listener.
     *
     * @param OrdenProduccion $ordenProduccion The production order.
     * @param Lote $lote The lot generated from this order.
     * @throws \Exception If no recipe is found for the finished product.
     */
    private function deductMateriaPrimaStock(OrdenProduccion $ordenProduccion, Lote $lote): void
    {
        $productoTerminado = $ordenProduccion->productoTerminado;
        // Eager load the recipe and its ingredients to avoid N+1 problems
        $receta = $productoTerminado->recetas()->with('ingredientes.materiaPrima')->first();

        if (!$receta) {
            throw new \Exception('No se encontró receta para el producto terminado. No se puede deducir el stock.');
        }

        foreach ($receta->ingredientes as $recetaIngrediente) {
            $materiaPrima = $recetaIngrediente->materiaPrima;
            $cantidadConsumida = $recetaIngrediente->cantidad_necesaria * $ordenProduccion->cantidad_a_producir; // Assuming 1 unit of recipe per 1 unit of product

            if (!$materiaPrima) {
                Log::error("Materia prima con ID {$recetaIngrediente->materia_prima_id} en la receta {$receta->id} no encontrada. No se puede deducir stock.");
                continue; // Skip this ingredient but continue with others if possible
            }

            // Deduct stock
            if ($materiaPrima->stock_actual < $cantidadConsumida) {
                // This scenario should ideally be caught BEFORE creating the order,
                // but good to have a fallback.
                Log::error("Stock of {$materiaPrima->nombre} is insufficient for order {$ordenProduccion->id}. Required: {$cantidadConsumida}, Actual: {$materiaPrima->stock_actual}.");
                // You might want to throw an exception here if partial deduction is not allowed.
                throw new \Exception("Stock insuficiente de {$materiaPrima->nombre} al deducir el inventario. Revise los stocks.");
            }
            $materiaPrima->decrement('stock_actual', $cantidadConsumida);

            // Create a traceability record for the consumed raw material
            TrazabilidadIngrediente::create([
                'lote_id' => $lote->id,
                'materia_prima_id' => $materiaPrima->id,
                'cantidad_utilizada' => $cantidadConsumida,
                'fecha_registro' => Carbon::now(),
            ]);

            // Optional: Generate an AjusteInventario of type 'Salida' for production consumption
            // AjusteInventario::create([
            //     'materia_prima_id' => $materiaPrima->id,
            //     'cantidad_ajustada' => -$cantidadConsumida, // Negative for consumption
            //     'tipo_ajuste' => 'Consumo Producción',
            //     'motivo' => "Consumo para OP#{$ordenProduccion->id}, Lote#{$lote->id}",
            //     'fecha_ajuste' => Carbon::now(),
            //     'realizado_por_user_id' => Auth::id(),
            // ]);
        }
    }
}
