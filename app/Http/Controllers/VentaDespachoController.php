<?php

namespace App\Http\Controllers;

use App\Models\VentaDespacho;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Lote;
use App\Models\ProductoTerminado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VentaDespacho\StoreVentaDespachoRequest;
use App\Http\Requests\VentaDespacho\UpdateVentaDespachoRequest;
use App\Http\Requests\VentaDespacho\AddDetalleVentaDespachoRequest; // Crear

class VentaDespachoController extends Controller
{
    public function index()
    {
        $ventas = VentaDespacho::with('cliente', 'registradoPor')->paginate(10);
        return view('ventas_despachos.index', compact('ventas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $users = User::all(); // O usuarios de ventas
        $lotesAprobados = Lote::where('estado_calidad', 'Aprobado')
            ->whereHas('productoTerminado', function ($query) {
                $query->where('stock_actual', '>', 0);
            })
            ->get();
        return view('ventas_despachos.create', compact('clientes', 'users', 'lotesAprobados'));
    }

    public function store(StoreVentaDespachoRequest $request)
    {
        // Seeder: VentaDespacho::create([
        //     'cliente_id' => $clienteSupermercado->id,
        //     'fecha_venta_despacho' => Carbon::now(),
        //     'tipo_documento' => 'Factura',
        //     'numero_documento' => 'FAC-20250609-001',
        //     'total_monto' => 1500.00,
        //     'estado_despacho' => 'Pendiente',
        //     'registrado_por_user_id' => $userVentas->id,
        //     'observaciones' => 'Factura de venta a supermercado.',
        // ]);

        DB::beginTransaction();
        try {
            $venta = VentaDespacho::create($request->validated());

            DB::commit();
            return redirect()->route('ventas-despachos.index')->with('success', 'Venta/Despacho creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
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
        $users = User::all();
        $lotesAprobados = Lote::where('estado_calidad', 'Aprobado')->get();
        $ventaDespacho->load('detallesVenta.lote');
        return view('ventas_despachos.edit', compact('ventaDespacho', 'clientes', 'users', 'lotesAprobados'));
    }

    public function update(UpdateVentaDespachoRequest $request, VentaDespacho $ventaDespacho)
    {
        $ventaDespacho->update($request->validated());
        return redirect()->route('ventas-despachos.index')->with('success', 'Venta/Despacho actualizado exitosamente.');
    }

    public function destroy(VentaDespacho $ventaDespacho)
    {
        // Si se elimina una venta, el stock de PT de los lotes debe ser revertido
        DB::beginTransaction();
        try {
            foreach ($ventaDespacho->detallesVenta as $detalle) {
                $lote = $detalle->lote;
                if ($lote) {
                    $lote->productoTerminado->increment('stock_actual', $detalle->cantidad_vendida_despachada);
                    // Opcional: Registrar un ajuste de inventario de entrada
                }
            }
            $ventaDespacho->delete();
            DB::commit();
            return redirect()->route('ventas-despachos.index')->with('success', 'Venta/Despacho eliminado y stock revertido.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la venta/despacho: ' . $e->getMessage());
        }
    }

    // Método para añadir detalles de venta/despacho (productos y lotes)
    public function addDetalle(AddDetalleVentaDespachoRequest $request, VentaDespacho $ventaDespacho)
    {
        DB::beginTransaction();
        try {
            $lote = Lote::findOrFail($request->lote_id);

            if ($lote->estado_calidad !== 'Aprobado') {
                DB::rollBack();
                return back()->withInput()->with('error', 'Solo se pueden vender productos de lotes con estado de calidad "Aprobado".');
            }
            if ($lote->productoTerminado->stock_actual < $request->cantidad_vendida_despachada) {
                DB::rollBack();
                return back()->withInput()->with('error', "Stock insuficiente del producto del lote {$lote->id}. Solo tienes {$lote->productoTerminado->stock_actual}.");
            }

            $subtotal = $request->cantidad_vendida_despachada * $request->precio_unitario_venta;

            $ventaDespacho->detallesVenta()->create(array_merge($request->validated(), ['subtotal' => $subtotal]));

            // Decrementar stock de Producto Terminado
            $lote->productoTerminado->decrement('stock_actual', $request->cantidad_vendida_despachada);
            // Opcional: Registrar un ajuste de inventario de salida

            // Actualizar el total_monto de la venta
            $ventaDespacho->update(['total_monto' => $ventaDespacho->detallesVenta->sum('subtotal')]);

            DB::commit();
            return back()->with('success', 'Detalle de venta/despacho añadido y stock actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al añadir detalle de venta: ' . $e->getMessage());
        }
    }

    public function updateDetalle(Request $request, VentaDespacho $ventaDespacho, $detalleId)
    {
        DB::beginTransaction();
        try {
            $detalle = $ventaDespacho->detallesVenta()->findOrFail($detalleId);
            $lote = $detalle->lote;
            $oldCantidad = $detalle->cantidad_vendida_despachada;

            $request->validate([
                'cantidad_vendida_despachada' => 'required|numeric|min:0.01',
                'precio_unitario_venta' => 'required|numeric|min:0.01',
            ]);

            $newCantidad = $request->cantidad_vendida_despachada;

            // Revertir stock antiguo y aplicar nuevo
            $lote->productoTerminado->increment('stock_actual', $oldCantidad);
            if ($lote->productoTerminado->stock_actual < $newCantidad) {
                DB::rollBack();
                $lote->productoTerminado->decrement('stock_actual', $oldCantidad); // Revertir incremento
                return back()->withInput()->with('error', "Stock insuficiente para la nueva cantidad. Solo tienes {$lote->productoTerminado->stock_actual} disponibles.");
            }
            $lote->productoTerminado->decrement('stock_actual', $newCantidad);

            $subtotal = $newCantidad * $request->precio_unitario_venta;
            $detalle->update([
                'cantidad_vendida_despachada' => $newCantidad,
                'precio_unitario_venta' => $request->precio_unitario_venta,
                'subtotal' => $subtotal,
            ]);

            $ventaDespacho->update(['total_monto' => $ventaDespacho->detallesVenta->sum('subtotal')]);

            DB::commit();
            return back()->with('success', 'Detalle de venta/despacho actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar detalle de venta: ' . $e->getMessage());
        }
    }

    public function removeDetalle(VentaDespacho $ventaDespacho, $detalleId)
    {
        DB::beginTransaction();
        try {
            $detalle = $ventaDespacho->detallesVenta()->findOrFail($detalleId);
            $lote = $detalle->lote;
            $cantidadRevertida = $detalle->cantidad_vendida_despachada;

            $detalle->delete();

            // Revertir el stock del producto terminado
            $lote->productoTerminado->increment('stock_actual', $cantidadRevertida);

            // Actualizar el total_monto de la venta
            $ventaDespacho->update(['total_monto' => $ventaDespacho->detallesVenta->sum('subtotal')]);

            DB::commit();
            return back()->with('success', 'Detalle de venta/despacho eliminado y stock revertido.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar detalle de venta: ' . $e->getMessage());
        }
    }
}
