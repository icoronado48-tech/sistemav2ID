<?php

namespace App\Http\Controllers;

use App\Models\RecepcionMateriaPrima;
use App\Models\OrdenCompra;
use App\Models\MateriaPrima;
use App\Models\User;
use App\Models\AjusteInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RecepcionMateriaPrima\StoreRecepcionMateriaPrimaRequest;

class RecepcionMateriaPrimaController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        $this->authorizeResource(RecepcionMateriaPrima::class, 'recepcion_materia_prima', [
            'except' => ['edit', 'update', 'destroy']
        ]);
    }

    public function index()
    {
        $recepciones = RecepcionMateriaPrima::with('ordenCompra', 'materiaPrima', 'recibidoPor')->paginate(10);
        return view('recepciones_materia_prima.index', compact('recepciones'));
    }

    public function create()
    {
        // Solo órdenes con estados que permiten recepción
        $ordenesCompra = OrdenCompra::whereIn('estado', ['Pendiente', 'Aprobada'])->get();
        $materiasPrimas = MateriaPrima::all();
        // Filtra usuarios que tienen roles permitidos para recibir materia prima
        $users = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['administrador', 'inventario', 'logistica']); // Ajusta según tus roles
        })->get();

        return view('recepciones_materia_prima.create', compact('ordenesCompra', 'materiasPrimas', 'users'));
    }

    public function store(StoreRecepcionMateriaPrimaRequest $request)
    {
        // La validación y autorización ya se manejan por StoreRecepcionMateriaPrimaRequest y authorizeResource.
        try {
            DB::beginTransaction(); // Iniciar una transacción de base de datos

            // Validar si la orden de compra y materia prima existen antes de proceder
            $ordenCompra = OrdenCompra::find($request->orden_compra_id);
            $materiaPrima = MateriaPrima::find($request->materia_prima_id);

            if (!$ordenCompra) {
                DB::rollBack();
                Log::warning("Intento de registrar recepción para OC inexistente.", ['orden_compra_id' => $request->orden_compra_id, 'request' => $request->all()]);
                return back()->withInput()->with('error', 'La Orden de Compra especificada no existe.');
            }

            if (!$materiaPrima) {
                DB::rollBack();
                Log::warning("Intento de registrar recepción para Materia Prima inexistente.", ['materia_prima_id' => $request->materia_prima_id, 'request' => $request->all()]);
                return back()->withInput()->with('error', 'La Materia Prima especificada no existe.');
            }

            $recepcion = RecepcionMateriaPrima::create($request->validated());

            // Actualizar stock de la materia prima
            $materiaPrima->increment('stock_actual', $request->cantidad_recibida);

            // Crear un registro de AjusteInventario para esta entrada
            AjusteInventario::create([
                'materia_prima_id' => $materiaPrima->id,
                'cantidad_ajustada' => $request->cantidad_recibida,
                'tipo_ajuste' => 'Entrada', // Asegúrate de que 'Entrada' sea un tipo válido en tu DB
                'motivo' => 'Recepción de orden de compra #' . $ordenCompra->id, // Usa $ordenCompra->id
                'fecha_ajuste' => $request->fecha_recepcion,
                'realizado_por_user_id' => Auth::id(),
            ]);

            // Lógica para actualizar el estado de la OrdenCompra si se recibe completamente
            // (Esto es más complejo y requeriría comparar cantidades pedidas vs. recibidas en todos los detalles)
            // if ($ordenCompra->todosLosItemsRecibidos()) {
            //     $ordenCompra->update(['estado' => 'Completada']);
            // }

            DB::commit();
            return redirect()->route('recepciones-materia-prima.index')->with('success', 'Recepción de materia prima registrada y stock actualizado.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de cualquier error
            Log::error("Error al registrar la recepción de materia prima: " . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Error al registrar la recepción. Por favor, inténtelo de nuevo.');
        }
    }

    public function show(RecepcionMateriaPrima $recepcionMateriaPrima)
    {
        // La autorización (view) ya se maneja automáticamente por authorizeResource.
        $recepcionMateriaPrima->load('ordenCompra', 'materiaPrima', 'recibidoPor');
        return view('recepciones_materia_prima.show', compact('recepcionMateriaPrima'));
    }

    // Los métodos `edit`, `update`, `destroy` están excluidos en el constructor.
}
