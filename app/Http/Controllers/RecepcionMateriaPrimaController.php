<?php

namespace App\Http\Controllers;

use App\Models\RecepcionMateriaPrima;
use App\Models\OrdenCompra;
use App\Models\MateriaPrima;
use App\Models\User;
use App\Models\AjusteInventario; // Para registrar el aumento de stock como ajuste
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RecepcionMateriaPrima\StoreRecepcionMateriaPrimaRequest;

class RecepcionMateriaPrimaController extends Controller
{
    public function index()
    {
        $recepciones = RecepcionMateriaPrima::with('ordenCompra', 'materiaPrima', 'recibidoPor')->paginate(10);
        return view('recepciones_materia_prima.index', compact('recepciones'));
    }

    public function create()
    {
        $ordenesCompra = OrdenCompra::whereIn('estado', ['Pendiente', 'Aprobada'])->get(); // Solo órdenes que pueden ser recibidas
        $materiasPrimas = MateriaPrima::all();
        $users = User::all(); // O usuarios de logística
        return view('recepciones_materia_prima.create', compact('ordenesCompra', 'materiasPrimas', 'users'));
    }

    public function store(StoreRecepcionMateriaPrimaRequest $request)
    {
        DB::beginTransaction();
        try {
            // Seeder: RecepcionMateriaPrima::create([
            //     'orden_compra_id' => $ordenCompraCompletada->id,
            //     'materia_prima_id' => $carne->id,
            //     'cantidad_recibida' => 100.00,
            //     'fecha_recepcion' => Carbon::now()->subDays(5),
            //     'lote_proveedor' => 'LTCARNE20250601',
            //     'estado_recepcion' => 'completa',
            //     'recibido_por_user_id' => $supervisorLogistica->id,
            //     'observaciones' => 'Entrega conforme a lo solicitado.',
            // ]);

            $recepcion = RecepcionMateriaPrima::create($request->validated());

            // Actualizar stock de la materia prima
            $materiaPrima = MateriaPrima::find($request->materia_prima_id);
            $materiaPrima->increment('stock_actual', $request->cantidad_recibida);

            // Opcional: Crear un registro de AjusteInventario para esta entrada
            AjusteInventario::create([
                'materia_prima_id' => $materiaPrima->id,
                'cantidad_ajustada' => $request->cantidad_recibida,
                'tipo_ajuste' => 'Entrada',
                'motivo' => 'Recepción de orden de compra #' . $request->orden_compra_id,
                'fecha_ajuste' => $request->fecha_recepcion,
                'realizado_por_user_id' => Auth::id(), // O el usuario que realiza la recepción
            ]);

            // Lógica para actualizar el estado de la OrdenCompra si se recibe completamente
            $ordenCompra = OrdenCompra::find($request->orden_compra_id);
            // Esto es más complejo: habría que sumar las cantidades recibidas de todos los items de la OC
            // y comparar con las cantidades pedidas. Por simplicidad, un ejemplo básico:
            // if ($ordenCompra->todosLosItemsRecibidos()) {
            //     $ordenCompra->update(['estado' => 'Completada']);
            // }

            DB::commit();
            return redirect()->route('recepciones-materia-prima.index')->with('success', 'Recepción de materia prima registrada y stock actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar la recepción: ' . $e->getMessage());
        }
    }

    public function show(RecepcionMateriaPrima $recepcionMateriaPrima)
    {
        $recepcionMateriaPrima->load('ordenCompra', 'materiaPrima', 'recibidoPor');
        return view('recepciones_materia_prima.show', compact('recepcionMateriaPrima'));
    }

    // Las recepciones generalmente no se editan ni eliminan para mantener la integridad del inventario.
    // Si hay un error, se registra un ajuste de inventario correctivo.
}
