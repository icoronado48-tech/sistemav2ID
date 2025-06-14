<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\Cliente\StoreClienteRequest;
use App\Http\Requests\Cliente\UpdateClienteRequest;
use Illuminate\Support\Facades\DB;   // Import for database transactions
use Illuminate\Support\Facades\Log;  // Import for logging

class ClienteController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        $this->authorizeResource(Cliente::class, 'cliente');
    }

    public function index()
    {
        $clientes = Cliente::paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(StoreClienteRequest $request)
    {
        try {
            DB::beginTransaction();
            Cliente::create($request->validated());
            DB::commit();
            return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al crear cliente: " . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Hubo un error al crear el cliente. Por favor, inténtelo de nuevo.');
        }
    }

    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        try {
            DB::beginTransaction();
            $cliente->update($request->validated());
            DB::commit();
            return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar cliente: " . $e->getMessage(), ['exception' => $e, 'cliente_id' => $cliente->id, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Hubo un error al actualizar el cliente. Por favor, inténtelo de nuevo.');
        }
    }

    public function destroy(Cliente $cliente)
    {
        try {
            if ($cliente->ventasDespachos()->exists()) {
                return back()->with('error', 'No se puede eliminar el cliente porque tiene ventas/despachos asociados.');
            }
            DB::beginTransaction();
            $cliente->delete();
            DB::commit();
            return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Error al eliminar cliente por FK: " . $e->getMessage(), ['exception' => $e, 'cliente_id' => $cliente->id]);
            return back()->with('error', 'No se puede eliminar el cliente. Verifique si aún tiene registros dependientes inesperados.');
        } catch (\Exception $e) {
            Log::error("Error general al eliminar cliente: " . $e->getMessage(), ['exception' => $e, 'cliente_id' => $cliente->id]);
            return back()->with('error', 'Hubo un error inesperado al eliminar el cliente. Por favor, inténtelo de nuevo.');
        }
    }
}
