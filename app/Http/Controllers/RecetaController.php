<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\ProductoTerminado;
use App\Models\MateriaPrima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Receta\StoreRecetaRequest;
use App\Http\Requests\Receta\UpdateRecetaRequest;
use App\Http\Requests\Receta\AddRecetaIngredienteRequest; // Crear

class RecetaController extends Controller
{
    public function index()
    {
        $recetas = Receta::with('productoTerminado')->paginate(10);
        return view('recetas.index', compact('recetas'));
    }

    public function create()
    {
        $productosTerminados = ProductoTerminado::all();
        return view('recetas.create', compact('productosTerminados'));
    }

    public function store(StoreRecetaRequest $request)
    {
        Receta::create($request->validated());
        return redirect()->route('recetas.index')->with('success', 'Receta creada exitosamente.');
    }

    public function show(Receta $receta)
    {
        $receta->load('productoTerminado', 'ingredientes.materiaPrima');
        return view('recetas.show', compact('receta'));
    }

    public function edit(Receta $receta)
    {
        $productosTerminados = ProductoTerminado::all();
        $materiasPrimas = MateriaPrima::all();
        $receta->load('ingredientes.materiaPrima'); // Cargar ingredientes para edición
        return view('recetas.edit', compact('receta', 'productosTerminados', 'materiasPrimas'));
    }

    public function update(UpdateRecetaRequest $request, Receta $receta)
    {
        $receta->update($request->validated());
        return redirect()->route('recetas.index')->with('success', 'Receta actualizada exitosamente.');
    }

    public function destroy(Receta $receta)
    {
        $receta->delete();
        return redirect()->route('recetas.index')->with('success', 'Receta eliminada exitosamente.');
    }

    // Métodos para gestionar RecetaIngrediente
    public function addIngrediente(AddRecetaIngredienteRequest $request, Receta $receta)
    {
        $receta->ingredientes()->create($request->validated());
        return back()->with('success', 'Ingrediente añadido a la receta.');
    }

    public function updateIngrediente(Request $request, Receta $receta, $recetaIngredienteId)
    {
        $recetaIngrediente = $receta->ingredientes()->findOrFail($recetaIngredienteId);
        // Validar request aquí o crear un Form Request específico
        $request->validate([
            'cantidad_necesaria' => 'required|numeric|min:0.01',
        ]);
        $recetaIngrediente->update(['cantidad_necesaria' => $request->cantidad_necesaria]);
        return back()->with('success', 'Cantidad de ingrediente actualizada.');
    }

    public function removeIngrediente(Receta $receta, $recetaIngredienteId)
    {
        $receta->ingredientes()->findOrFail($recetaIngredienteId)->delete();
        return back()->with('success', 'Ingrediente eliminado de la receta.');
    }
}
