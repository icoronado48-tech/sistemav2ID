<?php

namespace App\Http\Controllers;

use App\Models\OrdenCompra;
use App\Models\Proveedor;
use App\Models\MateriaPrima;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrdenCompra\StoreOrdenCompraRequest;
use App\Http\Requests\OrdenCompra\UpdateOrdenCompraRequest;
use App\Http\Requests\OrdenCompra\AddDetalleOrdenCompraRequest; // Crear

class OrdenCompraController extends Controller
{
    public function index()
    {
        $ordenes = OrdenCompra::with('proveedor', 'creadaPor')->paginate(10);
        return view('ordenes_compra.index', compact('ordenes'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        $users = User::all(); // O usuarios con rol de compras
        return view('ordenes_compra.create', compact('proveedores', 'users'));
    }

    public function store(StoreOrdenCompraRequest $request)
    {
        OrdenCompra::create($request->validated());
        return redirect()->route('ordenes-compra.index')->with('success', 'Orden de compra creada exitosamente.');
    }

    public function show(OrdenCompra $ordenCompra)
    {
        $ordenCompra->load('proveedor', 'creadaPor', 'detalles.materiaPrima');
        return view('ordenes_compra.show', compact('ordenCompra'));
    }

    public function edit(OrdenCompra $ordenCompra)
    {
        $proveedores = Proveedor::all();
        $users = User::all();
        $materiasPrimas = MateriaPrima::all(); // Para añadir detalles
        $ordenCompra->load('detalles.materiaPrima');
        return view('ordenes_compra.edit', compact('ordenCompra', 'proveedores', 'users', 'materiasPrimas'));
    }

    public function update(UpdateOrdenCompraRequest $request, OrdenCompra $ordenCompra)
    {
        $ordenCompra->update($request->validated());
        return redirect()->route('ordenes-compra.index')->with('success', 'Orden de compra actualizada exitosamente.');
    }

    public function destroy(OrdenCompra $ordenCompra)
    {
        if ($ordenCompra->recepciones()->exists()) {
            return back()->with('error', 'No se puede eliminar la orden de compra porque ya tiene recepciones asociadas.');
        }
        $ordenCompra->delete();
        return redirect()->route('ordenes-compra.index')->with('success', 'Orden de compra eliminada exitosamente.');
    }

    // Métodos para gestionar DetalleOrdenCompra
    public function addDetalle(AddDetalleOrdenCompraRequest $request, OrdenCompra $ordenCompra)
    {
        $subtotal = $request->cantidad * $request->precio_unitario;
        $ordenCompra->detalles()->create(array_merge($request->validated(), ['subtotal' => $subtotal]));

        // Actualizar el total_monto de la orden de compra
        $ordenCompra->update(['total_monto' => $ordenCompra->detalles->sum('subtotal')]);

        return back()->with('success', 'Detalle de orden de compra añadido.');
    }

    public function updateDetalle(Request $request, OrdenCompra $ordenCompra, $detalleId)
    {
        $detalle = $ordenCompra->detalles()->findOrFail($detalleId);
        $request->validate([
            'cantidad' => 'required|numeric|min:0.01',
            'precio_unitario' => 'required|numeric|min:0.01',
        ]);
        $subtotal = $request->cantidad * $request->precio_unitario;
        $detalle->update([
            'cantidad' => $request->cantidad,
            'precio_unitario' => $request->precio_unitario,
            'subtotal' => $subtotal,
        ]);

        $ordenCompra->update(['total_monto' => $ordenCompra->detalles->sum('subtotal')]);
        return back()->with('success', 'Detalle de orden de compra actualizado.');
    }

    public function removeDetalle(OrdenCompra $ordenCompra, $detalleId)
    {
        $detalle = $ordenCompra->detalles()->findOrFail($detalleId);
        $detalle->delete();

        // Actualizar el total_monto de la orden de compra
        $ordenCompra->update(['total_monto' => $ordenCompra->detalles->sum('subtotal')]);

        return back()->with('success', 'Detalle de orden de compra eliminado.');
    }
}
