<?php

namespace App\Http\Controllers;

use App\Models\OrdenProduccion;
use App\Models\ProductoTerminado;
use App\Models\MateriaPrima;
use App\Models\Lote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Requests\OrdenProduccion\StoreOrdenProduccionRequest;
use App\Http\Requests\OrdenProduccion\UpdateOrdenProduccionRequest;
use App\Http\Requests\OrdenProduccion\UpdateOrdenProduccionStatusRequest; // Crear

class OrdenProduccionController extends Controller
{
    public function index()
    {
        $ordenes = OrdenProduccion::with('productoTerminado', 'creadaPor')->paginate(10);
        return view('ordenes_produccion.index', compact('ordenes'));
    }

    public function create()
    {
        $productosTerminados = ProductoTerminado::all();
        $users = User::all(); // O solo usuarios con rol de producción
        return view('ordenes_produccion.create', compact('productosTerminados', 'users'));
    }

    public function store(StoreOrdenProduccionRequest $request)
    {
        DB::beginTransaction();
        try {
            $productoTerminado = ProductoTerminado::findOrFail($request->producto_terminado_id);

            // Lógica para verificar disponibilidad de materia prima
            $receta = $productoTerminado->recetas()->first(); // Asumiendo 1 receta por PT
            if (!$receta) {
                return back()->withInput()->with('error', 'El producto terminado no tiene una receta definida.');
            }

            foreach ($receta->ingredientes as $recetaIngrediente) {
                $materiaPrima = $recetaIngrediente->materiaPrima;
                $cantidadNecesaria = $recetaIngrediente->cantidad_necesaria * $request->cantidad_a_producir;

                if ($materiaPrima->stock_actual < $cantidadNecesaria) {
                    DB::rollBack();
                    return back()->withInput()->with('error', "Stock insuficiente de {$materiaPrima->nombre}. Necesitas {$cantidadNecesaria} {$materiaPrima->unidad_medida_entrada}. Solo tienes {$materiaPrima->stock_actual}.");
                }
            }

            $orden = OrdenProduccion::create($request->validated());

            DB::commit();
            return redirect()->route('ordenes-produccion.index')->with('success', 'Orden de producción creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
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
        $users = User::all();
        return view('ordenes_produccion.edit', compact('ordenProduccion', 'productosTerminados', 'users'));
    }

    public function update(UpdateOrdenProduccionRequest $request, OrdenProduccion $ordenProduccion)
    {
        $ordenProduccion->update($request->validated());
        return redirect()->route('ordenes-produccion.index')->with('success', 'Orden de producción actualizada exitosamente.');
    }

    public function destroy(OrdenProduccion $ordenProduccion)
    {
        if ($ordenProduccion->lotes()->exists()) {
            return back()->with('error', 'No se puede eliminar una orden de producción que ya tiene lotes asociados.');
        }
        $ordenProduccion->delete();
        return redirect()->route('ordenes-produccion.index')->with('success', 'Orden de producción eliminada exitosamente.');
    }

    // Método para actualizar el estado de la orden de producción
    public function updateStatus(UpdateOrdenProduccionStatusRequest $request, OrdenProduccion $ordenProduccion)
    {
        DB::beginTransaction();
        try {
            $oldStatus = $ordenProduccion->estado;
            $newStatus = $request->estado;

            $ordenProduccion->update(['estado' => $newStatus]);

            // Lógica para manejar cambios de estado importantes
            if ($newStatus === 'en_proceso' && $oldStatus === 'pendiente') {
                $ordenProduccion->update(['fecha_real_inicio' => Carbon::now()]);
            } elseif ($newStatus === 'completada' && $oldStatus !== 'completada') {
                $ordenProduccion->update(['fecha_real_fin' => Carbon::now()]);

                // Generar lote al completar la orden
                $this->generateLote($ordenProduccion);

                // Disminuir stock de materias primas consumidas
                $this->deductMateriaPrimaStock($ordenProduccion);
            } elseif ($newStatus === 'cancelada' && $oldStatus !== 'cancelada') {
                // Lógica si se cancela una orden (ej. liberar materia prima si ya se había apartado)
            }

            DB::commit();
            return redirect()->route('ordenes-produccion.index')->with('success', "Estado de la orden de producción actualizado a {$newStatus}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el estado: ' . $e->getMessage());
        }
    }

    /**
     * Lógica para generar un lote al completar una orden de producción.
     * Esto podría ser un método privado, un Job o un Evento/Listener.
     */
    private function generateLote(OrdenProduccion $ordenProduccion)
    {
        // Validar si el lote ya existe o si la orden ya fue completada y loteada
        if ($ordenProduccion->lotes()->exists()) {
            throw new \Exception('Esta orden de producción ya tiene un lote asociado.');
        }

        // Seeder: Lote::create([
        //     'orden_produccion_id' => $ordenProduccion->id,
        //     'producto_terminado_id' => $ordenProduccion->producto_terminado_id,
        //     'cantidad_producida' => 500.00, // Aquí iría la cantidad real producida
        //     'fecha_produccion' => Carbon::parse($ordenProduccion->fecha_real_inicio)->addHours(2),
        //     'fecha_vencimiento' => Carbon::parse($ordenProduccion->fecha_real_inicio)->addDays(30),
        //     'estado_calidad' => 'pendiente',
        //     'registrado_por_user_id' => $jefeProduccionUser->id,
        // ]);

        // Aquí se crearía el lote real
        Lote::create([
            'orden_produccion_id' => $ordenProduccion->id,
            'producto_terminado_id' => $ordenProduccion->producto_terminado_id,
            'cantidad_producida' => $ordenProduccion->cantidad_a_producir, // O la cantidad real producida si se captura
            'fecha_produccion' => Carbon::now(),
            'fecha_vencimiento' => Carbon::now()->addDays(30), // Fecha de vencimiento calculada
            'estado_calidad' => 'Pendiente', // Por defecto, se requiere control de calidad
            'observaciones_calidad' => null,
            'registrado_por_user_id' => Auth::id(), // El usuario que completa la orden
        ]);
        // Considerar aquí también la lógica para incrementar el stock de ProductoTerminado
        $ordenProduccion->productoTerminado->increment('stock_actual', $ordenProduccion->cantidad_a_producir);
    }

    /**
     * Lógica para deducir el stock de materias primas.
     * Esto podría ser un método privado, un Job o un Evento/Listener.
     */
    private function deductMateriaPrimaStock(OrdenProduccion $ordenProduccion)
    {
        $productoTerminado = $ordenProduccion->productoTerminado;
        $receta = $productoTerminado->recetas()->first();

        if (!$receta) {
            throw new \Exception('No se encontró receta para el producto terminado.');
        }

        foreach ($receta->ingredientes as $recetaIngrediente) {
            $materiaPrima = $recetaIngrediente->materiaPrima;
            $cantidadConsumida = $recetaIngrediente->cantidad_necesaria * $ordenProduccion->cantidad_a_producir;

            $materiaPrima->decrement('stock_actual', $cantidadConsumida);
            // Opcional: Generar un AjusteInventario de tipo 'Salida' por consumo de producción
        }
    }
}
