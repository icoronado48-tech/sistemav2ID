<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Http\Requests\Proveedor\StoreProveedorRequest;
use App\Http\Requests\Proveedor\UpdateProveedorRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProveedorController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        $this->authorizeResource(Proveedor::class, 'proveedor');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // La autorización (viewAny) ya se maneja automáticamente por authorizeResource.
        $proveedores = Proveedor::paginate(10);
        return view('proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // La autorización (create) ya se maneja automáticamente por authorizeResource.
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProveedorRequest $request)
    {
        // La validación y autorización ya se manejan por StoreProveedorRequest y authorizeResource.
        try {
            DB::beginTransaction(); // Iniciar una transacción de base de datos

            Proveedor::create($request->validated());

            DB::commit(); // Confirmar la transacción si todo es exitoso
            return redirect()->route('proveedores.index')->with('success', 'Proveedor creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de cualquier error
            Log::error("Error al crear proveedor: " . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]); // Registrar el error
            return back()->withInput()->with('error', 'Hubo un error al crear el proveedor. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        // La autorización (view) ya se maneja automáticamente por authorizeResource.
        return view('proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        // La autorización (update - implícita por ser un método de recurso) ya se maneja automáticamente.
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProveedorRequest $request, Proveedor $proveedor)
    {
        // La validación y autorización ya se manejan por UpdateProveedorRequest y authorizeResource.
        try {
            DB::beginTransaction(); // Iniciar una transacción

            $proveedor->update($request->validated());

            DB::commit(); // Confirmar la transacción
            return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción
            Log::error("Error al actualizar proveedor: " . $e->getMessage(), ['exception' => $e, 'proveedor_id' => $proveedor->id, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Hubo un error al actualizar el proveedor. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        // La autorización (delete) ya se maneja automáticamente por authorizeResource.
        try {
            // Realizamos la verificación de dependencias explícitamente para dar un mensaje amigable.
            // La ProveedorPolicy también tiene esta lógica, pero esta es para el feedback al usuario.
            if ($proveedor->ordenesCompra()->exists()) {
                return back()->with('error', 'No se puede eliminar el proveedor porque tiene órdenes de compra asociadas. Elimine primero sus órdenes de compra.');
            }

            DB::beginTransaction(); // Iniciar una transacción
            $proveedor->delete();
            DB::commit(); // Confirmar la transacción
            return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Captura una excepción específica si, por alguna razón, se intenta borrar
            // un proveedor con dependencias que la política no capturó, o si hay otras FKs.
            Log::error("Error al eliminar proveedor por restricción de clave foránea: " . $e->getMessage(), ['exception' => $e, 'proveedor_id' => $proveedor->id]);
            return back()->with('error', 'No se puede eliminar el proveedor. Verifique si aún tiene materias primas asociadas u otros registros dependientes.');
        } catch (\Exception $e) {
            // Captura cualquier otra excepción general
            Log::error("Error general al eliminar proveedor: " . $e->getMessage(), ['exception' => $e, 'proveedor_id' => $proveedor->id]);
            return back()->with('error', 'Hubo un error inesperado al eliminar el proveedor. Por favor, inténtelo de nuevo.');
        }
    }
}
