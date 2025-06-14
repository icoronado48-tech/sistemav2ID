<?php

namespace App\Http\Controllers;

use App\Models\StockAlerta;
use App\Models\MateriaPrima;
use App\Models\ProductoTerminado;
use App\Models\User; // Corregí la importación si estaba mal en tu original
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StockAlerta\UpdateStockAlertaRequest;

class StockAlertaController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        $this->authorizeResource(StockAlerta::class, 'stock_alerta', [
            'except' => ['create', 'store', 'destroy']
        ]);
    }

    public function index()
    {
        $alertas = StockAlerta::with('materiaPrima', 'productoTerminado', 'generadoPor')->paginate(10);
        return view('stock_alertas.index', compact('alertas'));
    }

    public function show(StockAlerta $stockAlerta)
    {
        $stockAlerta->load('materiaPrima', 'productoTerminado', 'generadoPor');
        return view('stock_alertas.show', compact('stockAlerta'));
    }

    public function edit(StockAlerta $stockAlerta)
    {
        // Opcional: Carga materias primas y productos terminados si la edición de la entidad es relevante
        // Ten en cuenta la validación de polimorfismo implícito en el Form Request.
        $materiasPrimas = MateriaPrima::all();
        $productosTerminados = ProductoTerminado::all();
        return view('stock_alertas.edit', compact('stockAlerta', 'materiasPrimas', 'productosTerminados'));
    }

    public function update(UpdateStockAlertaRequest $request, StockAlerta $stockAlerta)
    {
        // La validación y autorización ya se manejan por UpdateStockAlertaRequest y authorizeResource.
        try {
            DB::beginTransaction(); // Iniciar una transacción

            $stockAlerta->update($request->validated());

            DB::commit(); // Confirmar la transacción
            return redirect()->route('stock-alertas.index')->with('success', 'Alerta de stock actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción
            Log::error("Error al actualizar alerta de stock: " . $e->getMessage(), ['exception' => $e, 'alerta_id' => $stockAlerta->id, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Hubo un error al actualizar la alerta de stock. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Mark an alert as resolved.
     */
    public function markAsResolved(StockAlerta $stockAlerta)
    {
        // Autoriza esta acción personalizada usando la política.
        // Asegúrate de que tienes un método `markAsResolved` en tu `StockAlertaPolicy`.
        $this->authorize('markAsResolved', $stockAlerta);

        try {
            DB::beginTransaction(); // Iniciar una transacción
            $stockAlerta->update(['resuelta' => true]);
            DB::commit(); // Confirmar la transacción
            return back()->with('success', 'Alerta marcada como resuelta.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción
            Log::error("Error al marcar alerta como resuelta: " . $e->getMessage(), ['exception' => $e, 'alerta_id' => $stockAlerta->id]);
            return back()->with('error', 'Hubo un error al marcar la alerta como resuelta. Por favor, inténtelo de nuevo.');
        }
    }
}
