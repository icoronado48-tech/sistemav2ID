<?php

namespace App\Http\Controllers;

use App\Models\ControlCalidad;
use App\Models\Lote;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ControlCalidad\StoreControlCalidadRequest;
use App\Http\Requests\ControlCalidad\UpdateControlCalidadRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ControlCalidadController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        $this->authorizeResource(ControlCalidad::class, 'control_calidad');
    }

    public function index()
    {
        $controles = ControlCalidad::with('lote.productoTerminado', 'supervisadoPor')->paginate(10);
        return view('controles_calidad.index', compact('controles'));
    }

    public function create()
    {
        // Solo lotes en estado 'Pendiente' de calidad para control
        $lotesPendientes = Lote::where('estado_calidad', 'Pendiente')->get();
        $supervisores = User::whereHas('role', function ($query) {
            $query->where('nombre_rol', 'calidad'); // Assuming you have a 'calidad' role
        })->get();

        return view('controles_calidad.create', compact('lotesPendientes', 'supervisores'));
    }

    public function store(StoreControlCalidadRequest $request)
    {
        try {
            DB::beginTransaction();

            $lote = Lote::find($request->lote_id);
            if (!$lote) {
                DB::rollBack();
                Log::warning("Lote con ID {$request->lote_id} no encontrado al registrar control de calidad.", ['request' => $request->all()]);
                return back()->withInput()->with('error', 'El lote especificado no existe. No se pudo registrar el control de calidad.');
            }

            $control = ControlCalidad::create(array_merge($request->validated(), [
                'supervisado_por_user_id' => Auth::id(), // Asigna el usuario logueado
                'fecha_control' => Carbon::now(),
            ]));

            // Actualizar el estado de calidad del lote después de registrar el control
            $lote->update([
                'estado_calidad' => $request->resultado,
                'observaciones_calidad' => $request->observaciones,
            ]);

            DB::commit();
            return redirect()->route('controles-calidad.index')->with('success', 'Control de calidad registrado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al registrar el control de calidad: " . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Error al registrar el control de calidad: ' . $e->getMessage());
        }
    }

    public function show(ControlCalidad $controlCalidad)
    {
        $controlCalidad->load('lote.productoTerminado', 'supervisadoPor');
        return view('controles_calidad.show', compact('controlCalidad'));
    }

    public function edit(ControlCalidad $controlCalidad)
    {
        $lotes = Lote::all(); // You can filter if you only want relevant lots
        $supervisores = User::whereHas('role', function ($query) {
            $query->where('nombre_rol', 'calidad');
        })->get();
        return view('controles_calidad.edit', compact('controlCalidad', 'lotes', 'supervisores'));
    }

    public function update(UpdateControlCalidadRequest $request, ControlCalidad $controlCalidad)
    {
        try {
            DB::beginTransaction();
            $controlCalidad->update($request->validated());

            // Update the lot's quality status if the control result changed
            $lote = $controlCalidad->lote;
            if ($lote) {
                if ($lote->estado_calidad !== $request->resultado) {
                    $lote->update([
                        'estado_calidad' => $request->resultado,
                        'observaciones_calidad' => $request->observaciones, // Update lot's quality observations as well
                    ]);
                }
            } else {
                Log::warning("Lote asociado a control de calidad {$controlCalidad->id} no encontrado durante la actualización.");
            }

            DB::commit();
            return redirect()->route('controles-calidad.index')->with('success', 'Control de calidad actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar control de calidad: " . $e->getMessage(), ['exception' => $e, 'control_id' => $controlCalidad->id, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Error al actualizar control de calidad: ' . $e->getMessage());
        }
    }

    public function destroy(ControlCalidad $controlCalidad)
    {
        // Deleting a quality control record can be delicate as it might impact the lot's perceived state.
        // Consider if this operation should be allowed or if it should revert the lot's quality status.
        // For now, assuming direct delete is allowed, but it's often better to disallow or only soft delete.
        try {
            DB::beginTransaction();
            $controlCalidad->delete();
            DB::commit();
            return redirect()->route('controles-calidad.index')->with('success', 'Control de calidad eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error("Error al eliminar control de calidad por FK: " . $e->getMessage(), ['exception' => $e, 'control_id' => $controlCalidad->id]);
            return back()->with('error', 'No se puede eliminar el control de calidad. Verifique si aún tiene registros dependientes inesperados.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error general al eliminar control de calidad: " . $e->getMessage(), ['exception' => $e, 'control_id' => $controlCalidad->id]);
            return back()->with('error', 'Error al eliminar control de calidad: ' . $e->getMessage());
        }
    }
}
