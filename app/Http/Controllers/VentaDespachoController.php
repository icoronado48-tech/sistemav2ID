<?php

namespace App\Http\Controllers;

use App\Models\VentaDespacho;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Lote;
use App\Models\ProductoTerminado;
use App\Models\DetalleVentaDespacho; // Required for managing details
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // For Auth::id()
use App\Http\Requests\VentaDespacho\StoreVentaDespachoRequest;
use App\Http\Requests\VentaDespacho\UpdateVentaDespachoRequest;
// AddDetalleVentaDespachoRequest is no longer needed as a separate Form Request for dedicated methods

class VentaDespachoController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        $this->authorizeResource(VentaDespacho::class, 'venta_despacho');
    }

    public function index()
    {
        $ventas = VentaDespacho::with('cliente', 'registradoPor')->paginate(10);
        return view('ventas_despachos.index', compact('ventas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $users = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['administrador', 'ventas']);
        })->get();
        $lotesAprobados = Lote::where('estado_calidad', 'Aprobado')
            ->whereHas('productoTerminado', function ($query) {
                $query->where('stock_actual', '>', 0);
            })
            ->get();
        return view('ventas_despachos.create', compact('clientes', 'users', 'lotesAprobados'));
    }

    public function store(StoreVentaDespachoRequest $request)
    {
        try {
            DB::beginTransaction();
            $venta = VentaDespacho::create(array_merge($request->validated(), [
                'registrado_por_user_id' => Auth::id(), // Assign logged-in user
                'total_monto' => 0, // Initialize to 0, will be summed from details
            ]));

            // Handle details for the new sale
            if ($request->has('detalles') && is_array($request->detalles)) {
                foreach ($request->detalles as $detalleData) {
                    if (!isset($detalleData['lote_id']) || !isset($detalleData['cantidad_vendida_despachada']) || !isset($detalleData['precio_unitario'])) {
                        DB::rollBack();
                        return back()->withInput()->with('error', 'Formato de detalle de venta inválido.');
                    }

                    $lote = Lote::find($detalleData['lote_id']);
                    if (!$lote) {
                        DB::rollBack();
                        return back()->withInput()->with('error', "Lote con ID {$detalleData['lote_id']} no existe.");
                    }
                    if ($lote->estado_calidad !== 'Aprobado') {
                        DB::rollBack();
                        return back()->withInput()->with('error', 'Solo se pueden vender productos de lotes con estado de calidad "Aprobado".');
                    }
                    if ($lote->productoTerminado->stock_actual < $detalleData['cantidad_vendida_despachada']) {
                        DB::rollBack();
                        return back()->withInput()->with('error', "Stock insuficiente del producto del lote {$lote->id}. Solo tienes {$lote->productoTerminado->stock_actual} disponibles.");
                    }

                    $subtotal = $detalleData['cantidad_vendida_despachada'] * $detalleData['precio_unitario'];
                    $venta->detallesVenta()->create(array_merge($detalleData, ['subtotal' => $subtotal]));

                    // Decrement stock of Finished Product
                    $lote->productoTerminado->decrement('stock_actual', $detalleData['cantidad_vendida_despachada']);
                    // Optional: Register an inventory adjustment for sales
                    // AjusteInventario::create([...]);
                }
            }
            // Update total_monto after all details are added
            $venta->update(['total_monto' => $venta->detallesVenta()->sum('subtotal')]);

            DB::commit();
            return redirect()->route('ventas-despachos.index')->with('success', 'Venta/Despacho creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al crear la venta/despacho: " . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Error al crear la venta/despacho: ' . $e->getMessage());
        }
    }

    public function show(VentaDespacho $ventaDespacho)
    {
        $ventaDespacho->load('cliente', 'registradoPor', 'detallesVenta.lote.productoTerminado');
        return view('ventas_despachos.show', compact('ventaDespacho'));
    }

    public function edit(VentaDespacho $ventaDespacho)
    {
        $clientes = Cliente::all();
        $users = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['administrador', 'ventas']);
        })->get();
        $lotesAprobados = Lote::where('estado_calidad', 'Aprobado')
            ->whereHas('productoTerminado', function ($query) {
                $query->where('stock_actual', '>', 0);
            })
            ->get();
        $ventaDespacho->load('detallesVenta.lote.productoTerminado'); // Load existing details
        return view('ventas_despachos.edit', compact('ventaDespacho', 'clientes', 'users', 'lotesAprobados'));
    }

    public function update(UpdateVentaDespachoRequest $request, VentaDespacho $ventaDespacho)
    {
        try {
            DB::beginTransaction();
            $ventaDespacho->update($request->validated());

            // Synchronize details
            $currentDetailIds = [];
            if ($request->has('detalles') && is_array($request->detalles)) {
                foreach ($request->detalles as $detalleData) {
                    if (!isset($detalleData['lote_id']) || !isset($detalleData['cantidad_vendida_despachada']) || !isset($detalleData['precio_unitario'])) {
                        DB::rollBack();
                        return back()->withInput()->with('error', 'Formato de detalle de venta inválido.');
                    }

                    $lote = Lote::find($detalleData['lote_id']);
                    if (!$lote) {
                        DB::rollBack();
                        return back()->withInput()->with('error', "Lote con ID {$detalleData['lote_id']} no existe.");
                    }

                    $subtotal = $detalleData['cantidad_vendida_despachada'] * $detalleData['precio_unitario'];

                    $detalleExistente = $ventaDespacho->detallesVenta()->where('lote_id', $detalleData['lote_id'])->first();

                    $oldCantidad = $detalleExistente ? $detalleExistente->cantidad_vendida_despachada : 0;
                    $newCantidad = $detalleData['cantidad_vendida_despachada'];

                    // Revert stock of old quantity, then deduct new quantity
                    // This part should be transactional and careful.
                    // First, return the old quantity to stock if it was already deducted.
                    if ($oldCantidad > 0 && $lote->productoTerminado) {
                        $lote->productoTerminado->increment('stock_actual', $oldCantidad);
                    }
                    // Then, check if new quantity can be deducted and deduct it.
                    if ($lote->productoTerminado && $lote->productoTerminado->stock_actual < $newCantidad) {
                        DB::rollBack();
                        // If the new quantity is too high, revert the increment as well (to prevent double increment if transaction rolls back later)
                        if ($oldCantidad > 0) {
                            $lote->productoTerminado->decrement('stock_actual', $oldCantidad);
                        }
                        return back()->withInput()->with('error', "Stock insuficiente para la nueva cantidad del lote {$lote->id}. Solo tienes {$lote->productoTerminado->stock_actual} disponibles.");
                    }
                    if ($lote->productoTerminado) { // Check again before decrementing
                        $lote->productoTerminado->decrement('stock_actual', $newCantidad);
                    }


                    $detalle = $ventaDespacho->detallesVenta()->updateOrCreate(
                        ['lote_id' => $detalleData['lote_id']], // Condition to find
                        ['cantidad_vendida_despachada' => $newCantidad, 'precio_unitario' => $detalleData['precio_unitario'], 'subtotal' => $subtotal]
                    );
                    $currentDetailIds[] = $detalle->id;
                }
            }
            // Delete details that are no longer in the request and revert their stock
            $detailsToDelete = $ventaDespacho->detallesVenta()->whereNotIn('id', $currentDetailIds)->get();
            foreach ($detailsToDelete as $detalle) {
                $lote = $detalle->lote;
                if ($lote && $lote->productoTerminado) { // Check if related models exist
                    $lote->productoTerminado->increment('stock_actual', $detalle->cantidad_vendida_despachada);
                }
                $detalle->delete();
            }

            // Update total_monto after synchronization
            $ventaDespacho->update(['total_monto' => $ventaDespacho->detallesVenta()->sum('subtotal')]);

            DB::commit();
            return redirect()->route('ventas-despachos.index')->with('success', 'Venta/Despacho actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar la venta/despacho: " . $e->getMessage(), ['exception' => $e, 'venta_id' => $ventaDespacho->id, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Error al actualizar la venta/despacho: ' . $e->getMessage());
        }
    }

    public function destroy(VentaDespacho $ventaDespacho)
    {
        try {
            DB::beginTransaction();
            // Revert stock for all associated details before deleting the sale
            foreach ($ventaDespacho->detallesVenta as $detalle) {
                $lote = $detalle->lote;
                if ($lote && $lote->productoTerminado) { // Check if related models exist
                    $lote->productoTerminado->increment('stock_actual', $detalle->cantidad_vendida_despachada);
                    // Optional: Register an inventory adjustment for sales return
                    // AjusteInventario::create([...]);
                }
            }
            $ventaDespacho->delete(); // This should cascade delete DetalleVentaDespacho if configured
            DB::commit();
            return redirect()->route('ventas-despachos.index')->with('success', 'Venta/Despacho eliminado y stock revertido.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Error al eliminar venta/despacho por FK: " . $e->getMessage(), ['exception' => $e, 'venta_id' => $ventaDespacho->id]);
            return back()->with('error', 'No se puede eliminar la venta/despacho. Verifique si aún tiene registros dependientes inesperados.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error general al eliminar venta/despacho: " . $e->getMessage(), ['exception' => $e, 'venta_id' => $ventaDespacho->id]);
            return back()->with('error', 'Error al eliminar la venta/despacho: ' . $e->getMessage());
        }
    }
}
