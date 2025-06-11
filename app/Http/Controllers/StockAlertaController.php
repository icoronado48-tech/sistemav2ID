<?php

namespace App\Http\Controllers;

use App\Models\StockAlerta;
use App\Models\MateriaPrima;
use App\Models\ProductoTerminado;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StockAlerta\UpdateStockAlertaRequest;

class StockAlertaController extends Controller
{
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
        return view('stock_alertas.edit', compact('stockAlerta'));
    }

    public function update(UpdateStockAlertaRequest $request, StockAlerta $stockAlerta)
    {
        // Seeder:
        // StockAlerta::create([
        //     'tipo_entidad' => 'MateriaPrima',
        //     'entidad_id' => $harina->id,
        //     'nivel_actual' => 90.00,
        //     'nivel_minimo' => $harina->stock_minimo,
        //     'tipo_alerta' => 'stock_bajo',
        //     'mensaje' => 'El stock de Harina de Trigo está bajo. Se requiere reabastecimiento urgente.',
        //     'fecha_alerta' => Carbon::now()->subHours(5),
        //     'resuelta' => false,
        //     'generado_por_user_id' => $supervisorLogistica->id,
        // ]);

        $stockAlerta->update($request->validated());
        return redirect()->route('stock-alertas.index')->with('success', 'Alerta de stock actualizada exitosamente.');
    }

    // Marcar alerta como resuelta
    public function markAsResolved(StockAlerta $stockAlerta)
    {
        $stockAlerta->update(['resuelta' => true]);
        return back()->with('success', 'Alerta marcada como resuelta.');
    }

    // Las alertas generalmente se generan automáticamente (ej. a través de Observers o Jobs programados)
    // No hay un método `store` o `create` directo para que los usuarios creen alertas manualmente,
    // ya que su propósito es ser disparadas por el sistema.
    // Si se desea crear una alerta manualmente para pruebas, se podría hacer una ruta dedicada.
}
