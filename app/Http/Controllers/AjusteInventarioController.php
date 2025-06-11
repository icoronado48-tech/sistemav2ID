<?php

namespace App\Http\Controllers;

use App\Models\AjusteInventario;
use App\Models\MateriaPrima;
use App\Models\ProductoTerminado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AjusteInventario\StoreAjusteInventarioRequest;

class AjusteInventarioController extends Controller
{
    public function index()
    {
        $ajustes = AjusteInventario::with('materiaPrima', 'productoTerminado', 'realizadoPor')->paginate(10);
        return view('ajustes_inventario.index', compact('ajustes'));
    }

    public function create()
    {
        $materiasPrimas = MateriaPrima::all();
        $productosTerminados = ProductoTerminado::all();
        $users = User::all(); // O usuarios con rol de logística/inventario
        return view('ajustes_inventario.create', compact('materiasPrimas', 'productosTerminados', 'users'));
    }

    public function store(StoreAjusteInventarioRequest $request)
    {
        DB::beginTransaction();
        try {
            // Seeder: AjusteInventario::create([
            //     'tipo_entidad' => 'MateriaPrima',
            //     'entidad_id' => $harina->id,
            //     'cantidad_ajuste' => -5.00, // 5 kg de pérdida
            //     'tipo_ajuste' => 'merma',
            //     'motivo' => 'Paquete roto durante manipulación.',
            //     'fecha_ajuste' => Carbon::now()->subDays(2),
            //     'ajustado_por_user_id' => $supervisorLogistica->id,
            // ]);

            $ajuste = AjusteInventario::create($request->validated());

            // Actualizar stock de la materia prima o producto terminado
            if ($ajuste->materia_prima_id) {
                $materiaPrima = MateriaPrima::find($ajuste->materia_prima_id);
                $materiaPrima->increment('stock_actual', $ajuste->cantidad_ajustada);
            } elseif ($ajuste->producto_terminado_id) {
                $productoTerminado = ProductoTerminado::find($ajuste->producto_terminado_id);
                $productoTerminado->increment('stock_actual', $ajuste->cantidad_ajustada);
            } else {
                DB::rollBack();
                return back()->withInput()->with('error', 'El ajuste debe especificar una materia prima o un producto terminado.');
            }

            DB::commit();
            return redirect()->route('ajustes-inventario.index')->with('success', 'Ajuste de inventario registrado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al registrar el ajuste de inventario: ' . $e->getMessage());
        }
    }

    public function show(AjusteInventario $ajusteInventario)
    {
        $ajusteInventario->load('materiaPrima', 'productoTerminado', 'realizadoPor');
        return view('ajustes_inventario.show', compact('ajusteInventario'));
    }

    // No se suelen permitir actualizaciones o eliminaciones de ajustes de inventario para mantener la auditoría
    // Si se requiere corrección, se crea un nuevo ajuste inverso.
}
