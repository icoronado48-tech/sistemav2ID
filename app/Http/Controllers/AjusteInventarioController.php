<?php

namespace App\Http\Controllers;

use App\Models\AjusteInventario;
use App\Models\MateriaPrima;
use App\Models\ProductoTerminado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Asegúrate de que esté importado
use App\Http\Requests\AjusteInventario\StoreAjusteInventarioRequest;

class AjusteInventarioController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        $this->authorizeResource(AjusteInventario::class, 'ajuste_inventario', [
            'except' => ['edit', 'update', 'destroy']
        ]);
    }

    public function index()
    {
        $ajustes = AjusteInventario::with('materiaPrima', 'productoTerminado', 'realizadoPor')->paginate(10);
        return view('ajustes_inventario.index', compact('ajustes'));
    }

    public function create()
    {
        $materiasPrimas = MateriaPrima::all();
        $productosTerminados = ProductoTerminado::all();
        // Filtra usuarios que tienen roles permitidos para realizar ajustes
        $users = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['administrador', 'inventario']); // Ajusta según tus roles
        })->get();

        return view('ajustes_inventario.create', compact('materiasPrimas', 'productosTerminados', 'users'));
    }

    public function store(StoreAjusteInventarioRequest $request)
    {
        // La validación y autorización ya se manejan por StoreAjusteInventarioRequest y authorizeResource.
        try {
            DB::beginTransaction(); // Iniciar una transacción de base de datos

            $data = $request->validated();

            // *** Validación de Polimorfismo Implícito a nivel de controlador ***
            // Esta lógica DEBE estar FUERTEMENTE respaldada por la validación del Form Request.
            // Asegura que SOLO UN ID de entidad (materia_prima_id O producto_terminado_id) sea proporcionado.
            if (empty($data['materia_prima_id']) && empty($data['producto_terminado_id'])) {
                DB::rollBack();
                Log::warning('Intento de crear ajuste sin materia prima ni producto terminado.', ['request' => $request->all()]);
                return back()->withInput()->with('error', 'El ajuste debe especificar una materia prima o un producto terminado.');
            }
            if (!empty($data['materia_prima_id']) && !empty($data['producto_terminado_id'])) {
                DB::rollBack();
                Log::warning('Intento de crear ajuste especificando tanto materia prima como producto terminado.', ['request' => $request->all()]);
                return back()->withInput()->with('error', 'El ajuste no puede especificar tanto una materia prima como un producto terminado.');
            }
            // *** Fin Validación de Polimorfismo Implícito ***

            // Asegura que el usuario que realiza el ajuste es el autenticado si no viene en la request
            // (o si siempre debe ser el logueado independientemente de la request)
            $data['realizado_por_user_id'] = Auth::id();

            $ajuste = AjusteInventario::create($data);

            // Actualizar stock de la materia prima o producto terminado
            if ($ajuste->materia_prima_id) {
                $materiaPrima = MateriaPrima::find($ajuste->materia_prima_id);
                if ($materiaPrima) {
                    $materiaPrima->increment('stock_actual', $ajuste->cantidad_ajustada);
                } else {
                    DB::rollBack();
                    Log::error("Materia prima con ID {$ajuste->materia_prima_id} no encontrada al intentar actualizar stock para ajuste {$ajuste->id}.");
                    return back()->withInput()->with('error', 'Materia prima no encontrada para el ajuste. Posible inconsistencia de datos.');
                }
            } elseif ($ajuste->producto_terminado_id) {
                $productoTerminado = ProductoTerminado::find($ajuste->producto_terminado_id);
                if ($productoTerminado) {
                    $productoTerminado->increment('stock_actual', $ajuste->cantidad_ajustada);
                } else {
                    DB::rollBack();
                    Log::error("Producto terminado con ID {$ajuste->producto_terminado_id} no encontrado al intentar actualizar stock para ajuste {$ajuste->id}.");
                    return back()->withInput()->with('error', 'Producto terminado no encontrado para el ajuste. Posible inconsistencia de datos.');
                }
            }

            DB::commit();
            return redirect()->route('ajustes-inventario.index')->with('success', 'Ajuste de inventario registrado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de cualquier error
            Log::error("Error general al registrar el ajuste de inventario: " . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Error al registrar el ajuste de inventario. Por favor, inténtelo de nuevo.');
        }
    }

    public function show(AjusteInventario $ajusteInventario)
    {
        // La autorización (view) ya se maneja automáticamente por authorizeResource.
        $ajusteInventario->load('materiaPrima', 'productoTerminado', 'realizadoPor');
        return view('ajustes_inventario.show', compact('ajusteInventario'));
    }

    // No se suelen permitir actualizaciones o eliminaciones de ajustes de inventario para mantener la auditoría
    // (Estos métodos están excluidos en el constructor con `except`).
}
