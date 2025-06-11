<?php

namespace App\Http\Controllers;

use App\Models\ProductoTerminado;
use Illuminate\Http\Request;
use App\Http\Requests\ProductoTerminado\StoreProductoTerminadoRequest;
use App\Http\Requests\ProductoTerminado\UpdateProductoTerminadoRequest;

class ProductoTerminadoController extends Controller
{
    public function index()
    {
        $productosTerminados = ProductoTerminado::paginate(10);
        return view('productos_terminados.index', compact('productosTerminados'));
    }

    public function create()
    {
        return view('productos_terminados.create');
    }

    public function store(StoreProductoTerminadoRequest $request)
    {
        ProductoTerminado::create($request->validated());
        return redirect()->route('productos-terminados.index')->with('success', 'Producto terminado creado exitosamente.');
    }

    public function show(ProductoTerminado $productoTerminado)
    {
        return view('productos_terminados.show', compact('productoTerminado'));
    }

    public function edit(ProductoTerminado $productoTerminado)
    {
        return view('productos_terminados.edit', compact('productoTerminado'));
    }

    public function update(UpdateProductoTerminadoRequest $request, ProductoTerminado $productoTerminado)
    {
        $productoTerminado->update($request->validated());
        return redirect()->route('productos-terminados.index')->with('success', 'Producto terminado actualizado exitosamente.');
    }

    public function destroy(ProductoTerminado $productoTerminado)
    {
        // Considerar si hay dependencias (recetas, lotes, ventas, etc.)
        $productoTerminado->delete();
        return redirect()->route('productos-terminados.index')->with('success', 'Producto terminado eliminado exitosamente.');
    }
}
