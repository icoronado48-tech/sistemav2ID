<?php

namespace App\Http\Controllers;

// ... el resto de tus declaraciones 'use' y el código de la clase

use App\Models\OrdenCompra;
use App\Models\Proveedor;
use App\Models\MateriaPrima;
use App\Models\User;
use App\Models\DetalleOrdenCompra; // Necesario si vas a gestionar sus detalles
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Import for logging
use App\Http\Requests\OrdenCompra\StoreOrdenCompraRequest;
use App\Http\Requests\OrdenCompra\UpdateOrdenCompraRequest;
use App\Http\Requests\OrdenCompra\AddDetalleOrdenCompraRequest;

class OrdenCompraController extends Controller
{
    /**
     * Constructor to apply policies to resource methods and custom actions.
     */
    public function __construct()
    {
        $this->authorizeResource(OrdenCompra::class, 'orden_compra');
        // Si quieres autorizar addDetalle, updateDetalle, removeDetalle con la política:
        // $this->middleware('can:addDetalle,orden_compra')->only('addDetalle');
        // $this->middleware('can:updateDetalle,orden_compra')->only('updateDetalle');
        // $this->middleware('can:removeDetalle,orden_compra')->only('removeDetalle');
    }

    public function index()
    {
        $ordenes = OrdenCompra::with('proveedor', 'creadaPor')->paginate(10);
        return view('ordenes_compra.index', compact('ordenes'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        $users = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['administrador', 'compras']);
        })->get(); // Filter users by role
        return view('ordenes_compra.create', compact('proveedores', 'users'));
    }

    public function store(StoreOrdenCompraRequest $request)
    {
        try {
            DB::beginTransaction();
            $orden = OrdenCompra::create($request->validated());

            // Handle details for the new order
            // Assuming 'detalles' is an array of objects/arrays of materia_prima_id, cantidad, precio_unitario
            if ($request->has('detalles') && is_array($request->detalles)) {
                foreach ($request->detalles as $detalleData) {
                    if (!isset($detalleData['materia_prima_id']) || !isset($detalleData['cantidad']) || !isset($detalleData['precio_unitario'])) {
                        DB::rollBack();
                        return back()->withInput()->with('error', 'Formato de detalle de orden de compra inválido.');
                    }
                    // Validate materia_prima_id exists
                    if (!MateriaPrima::find($detalleData['materia_prima_id'])) {
                        DB::rollBack();
                        return back()->withInput()->with('error', "Materia prima con ID {$detalleData['materia_prima_id']} no existe en los detalles.");
                    }

                    $subtotal = $detalleData['cantidad'] * $detalleData['precio_unitario'];
                    $orden->detalles()->create(array_merge($detalleData, ['subtotal' => $subtotal]));
                }
            }
            // Update total_monto after all details are added
            $orden->update(['total_monto' => $orden->detalles()->sum('subtotal')]);

            DB::commit();
            return redirect()->route('ordenes-compra.index')->with('success', 'Orden de compra creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al crear la orden de compra: " . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Error al crear la orden de compra: ' . $e->getMessage());
        }
    }

    public function show(OrdenCompra $ordenCompra)
    {
        $ordenCompra->load('proveedor', 'creadaPor', 'detalles.materiaPrima');
        return view('ordenes_compra.show', compact('ordenCompra'));
    }

    public function edit(OrdenCompra $ordenCompra)
    {
        $proveedores = Proveedor::all();
        $users = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['administrador', 'compras']);
        })->get();
        $materiasPrimas = MateriaPrima::all(); // For adding/editing details
        $ordenCompra->load('detalles.materiaPrima'); // Load existing details
        return view('ordenes_compra.edit', compact('ordenCompra', 'proveedores', 'users', 'materiasPrimas'));
    }

    public function update(UpdateOrdenCompraRequest $request, OrdenCompra $ordenCompra)
    {
        try {
            DB::beginTransaction();
            $ordenCompra->update($request->validated());

            // Synchronize details
            $currentDetailIds = [];
            if ($request->has('detalles') && is_array($request->detalles)) {
                foreach ($request->detalles as $detalleData) {
                    if (!isset($detalleData['materia_prima_id']) || !isset($detalleData['cantidad']) || !isset($detalleData['precio_unitario'])) {
                        DB::rollBack();
                        return back()->withInput()->with('error', 'Formato de detalle de orden de compra inválido.');
                    }
                    if (!MateriaPrima::find($detalleData['materia_prima_id'])) {
                        DB::rollBack();
                        return back()->withInput()->with('error', "Materia prima con ID {$detalleData['materia_prima_id']} no existe en los detalles.");
                    }

                    $subtotal = $detalleData['cantidad'] * $detalleData['precio_unitario'];
                    $detalle = $ordenCompra->detalles()->updateOrCreate(
                        ['materia_prima_id' => $detalleData['materia_prima_id']], // Find by materia_prima_id for this order
                        ['cantidad' => $detalleData['cantidad'], 'precio_unitario' => $detalleData['precio_unitario'], 'subtotal' => $subtotal]
                    );
                    $currentDetailIds[] = $detalle->id;
                }
            }
            // Delete details not present in the request
            $ordenCompra->detalles()->whereNotIn('id', $currentDetailIds)->delete();
            // Update total_monto after synchronization
            $ordenCompra->update(['total_monto' => $ordenCompra->detalles()->sum('subtotal')]);

            DB::commit();
            return redirect()->route('ordenes-compra.index')->with('success', 'Orden de compra actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar la orden de compra: " . $e->getMessage(), ['exception' => $e, 'order_id' => $ordenCompra->id, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Error al actualizar la orden de compra: ' . $e->getMessage());
        }
    }

    public function destroy(OrdenCompra $ordenCompra)
    {
        try {
            // Check for dependencies (recepciones)
            if ($ordenCompra->recepciones()->exists()) {
                return back()->with('error', 'No se puede eliminar la orden de compra porque ya tiene recepciones asociadas.');
            }
            DB::beginTransaction();
            // Delete all associated details first (if not cascading in DB)
            $ordenCompra->detalles()->delete();
            $ordenCompra->delete();
            DB::commit();
            return redirect()->route('ordenes-compra.index')->with('success', 'Orden de compra eliminada exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Error al eliminar orden de compra por FK: " . $e->getMessage(), ['exception' => $e, 'order_id' => $ordenCompra->id]);
            return back()->with('error', 'No se puede eliminar la orden de compra. Verifique si aún tiene registros dependientes inesperados.');
        } catch (\Exception $e) {
            Log::error("Error general al eliminar orden de compra: " . $e->getMessage(), ['exception' => $e, 'order_id' => $ordenCompra->id]);
            return back()->with('error', 'Hubo un error inesperado al eliminar la orden de compra. Por favor, inténtelo de nuevo.');
        }
    }

    // Los métodos addDetalle, updateDetalle, removeDetalle se integran en store/update
    // y no se necesitan como rutas separadas si el frontend envía el array completo de detalles.
    // Si el frontend gestiona detalles individualmente vía AJAX, entonces sí se usarían.
    // Para mantener la simplicidad y el modelo de gestión "parent-child" en una sola operación,
    // los eliminamos de la interfaz pública y los fusionamos en store/update.
    // Si los necesitas, deberías volver a añadirlos y sus Form Requests, con sus propias políticas.
    // Por ahora, para esta revisión, los eliminamos.
}
