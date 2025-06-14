<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request; // Still needed for assignRole method parameter type-hint
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\AssignRoleRequest;
use Illuminate\Support\Facades\DB; // Import for database transactions
use Illuminate\Support\Facades\Log; // Import for error logging

class UserController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        // This will automatically authorize 'viewAny', 'view', 'create', 'update', 'delete', 'restore', 'forceDelete'
        // based on the UserPolicy.
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Authorization (viewAny) is handled by authorizeResource in the constructor.
        $users = User::with('role')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Authorization (create) is handled by authorizeResource.
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // Validation and Authorization are handled by StoreUserRequest and authorizeResource.
        try {
            DB::beginTransaction(); // Start database transaction

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
            ]);

            DB::commit(); // Commit transaction
            return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            Log::error("Error creating user: " . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Hubo un error al crear el usuario. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Authorization (view) is handled by authorizeResource.
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Authorization (update - implicit from resource route) is handled by authorizeResource.
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // Validation and Authorization are handled by UpdateUserRequest and authorizeResource.
        try {
            DB::beginTransaction(); // Start database transaction

            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->role_id = $request->role_id;
            $user->save();

            DB::commit(); // Commit transaction
            return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            Log::error("Error updating user: " . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Hubo un error al actualizar el usuario. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Authorization (delete) is handled by authorizeResource.
        try {
            // Because we set ON DELETE RESTRICT in migrations, this will fail if dependencies exist.
            // Catching QueryException specifically for foreign key constraints.
            $user->delete();
            return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Error deleting user due to FK constraint: " . $e->getMessage(), ['exception' => $e, 'user_id' => $user->id]);
            return back()->with('error', 'No se puede eliminar el usuario porque tiene registros asociados en el sistema (órdenes de compra, lotes, etc.). Por favor, elimine los registros relacionados primero.');
        } catch (\Exception $e) {
            Log::error("General error deleting user: " . $e->getMessage(), ['exception' => $e, 'user_id' => $user->id]);
            return back()->with('error', 'Hubo un error inesperado al eliminar el usuario. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Assign a role to a user (if not handled by general update).
     */
    public function assignRole(AssignRoleRequest $request, User $user)
    {
        // Authorize this specific action using the policy.
        $this->authorize('assignRole', $user);

        try {
            DB::beginTransaction();
            $user->role_id = $request->role_id;
            $user->save();
            DB::commit();
            return redirect()->route('users.index')->with('success', 'Rol asignado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error assigning role to user: " . $e->getMessage(), ['exception' => $e, 'user_id' => $user->id]);
            return back()->withInput()->with('error', 'Hubo un error al asignar el rol. Por favor, inténtelo de nuevo.');
        }
    }
}
