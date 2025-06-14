<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request; // Still needed for assignRole method parameter type-hint (if any custom methods were there)
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use Illuminate\Support\Facades\DB; // Import for database transactions
use Illuminate\Support\Facades\Log; // Import for error logging

class RoleController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        // This will automatically authorize 'viewAny', 'view', 'create', 'update', 'delete', 'restore', 'forceDelete'
        // based on the RolePolicy.
        $this->authorizeResource(Role::class, 'role');
    }

    /**
     * Display a listing of the roles.
     */
    public function index()
    {
        // Authorization (viewAny) is handled by authorizeResource in the constructor.
        $roles = Role::paginate(10);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        // Authorization (create) is handled by authorizeResource.
        return view('roles.create');
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        // Validation and Authorization are handled by StoreRoleRequest and authorizeResource.
        try {
            DB::beginTransaction(); // Start database transaction
            Role::create($request->validated());
            DB::commit(); // Commit transaction
            return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            Log::error("Error creating role: " . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Hubo un error al crear el rol. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        // Authorization (view) is handled by authorizeResource.
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        // Authorization (update - implicit from resource route) is handled by authorizeResource.
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        // Validation and Authorization are handled by UpdateRoleRequest and authorizeResource.
        try {
            DB::beginTransaction(); // Start database transaction
            $role->update($request->validated());
            DB::commit(); // Commit transaction
            return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            Log::error("Error updating role: " . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Hubo un error al actualizar el rol. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        // Authorization (delete) is handled by authorizeResource.
        try {
            // Custom check for associated users before attempting deletion (as per DER and previous discussion).
            // This is handled both in the policy and here for an immediate user-friendly message.
            if ($role->users()->count() > 0) {
                return back()->with('error', 'No se puede eliminar el rol porque tiene usuarios asociados.');
            }

            DB::beginTransaction(); // Start transaction if no users are associated
            $role->delete();
            DB::commit(); // Commit transaction
            return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch specific exception for foreign key constraints if the policy check is somehow bypassed
            Log::error("Error deleting role due to FK constraint: " . $e->getMessage(), ['exception' => $e, 'role_id' => $role->id]);
            return back()->with('error', 'No se puede eliminar el rol. Verifique si aún tiene usuarios asociados o registros dependientes.');
        } catch (\Exception $e) {
            Log::error("General error deleting role: " . $e->getMessage(), ['exception' => $e, 'role_id' => $role->id]);
            return back()->with('error', 'Hubo un error inesperado al eliminar el rol. Por favor, inténtelo de nuevo.');
        }
    }
}
