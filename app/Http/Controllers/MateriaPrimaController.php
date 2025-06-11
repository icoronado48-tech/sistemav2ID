<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrima;
use Illuminate\Http\Request;
use App\Http\Requests\MateriaPrima\StoreMateriaPrimaRequest;
use App\Http\Requests\MateriaPrima\UpdateMateriaPrimaRequest;

class MateriaPrimaController extends Controller
{
    public function index()
    {
        $materiasPrimas = MateriaPrima::paginate(10);
        return view('materias_primas.index', compact('materiasPrimas'));
    }

    public function create()
    {
        return view('materias_primas.create');
    }

    public function store(StoreMateriaPrimaRequest $request)
    {
        MateriaPrima::create($request->validated());
        return redirect()->route('materias-primas.index')->with('success', 'Materia prima creada exitosamente.');
    }

    public function show(MateriaPrima $materiaPrima)
    {
        return view('materias_primas.show', compact('materiaPrima'));
    }

    public function edit(MateriaPrima $materiaPrima)
    {
        return view('materias_primas.edit', compact('materiaPrima'));
    }

    public function update(UpdateMateriaPrimaRequest $request, MateriaPrima $materiaPrima)
    {
        $materiaPrima->update($request->validated());
        return redirect()->route('materias-primas.index')->with('success', 'Materia prima actualizada exitosamente.');
    }

    public function destroy(MateriaPrima $materiaPrima)
    {
        // Considerar si hay dependencias (recetas, recepciones, etc.)
        $materiaPrima->delete();
        return redirect()->route('materias-primas.index')->with('success', 'Materia prima eliminada exitosamente.');
    }
}
