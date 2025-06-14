<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\ControlCalidad;
use Illuminate\Http\Request;
use App\Http\Requests\Lote\UpdateLoteQualityRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoteController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        $this->authorizeResource(Lote::class, 'lote', [
            'except' => ['create', 'store', 'edit', 'update', 'destroy']
        ]);
        $this->middleware('can:updateQualityStatus,lote')->only('updateQualityStatus');
    }

    public function index()
    {
        $lotes = Lote::with('ordenProduccion', 'productoTerminado', 'registradoPor')->paginate(10);
        return view('lotes.index', compact('lotes'));
    }

    public function show(Lote $lote)
    {
        $lote->load('ordenProduccion', 'productoTerminado', 'registradoPor', 'controlesCalidad');
        return view('lotes.show', compact('lote'));
    }

    /**
     * Update the quality status of the specified lot.
     */
    public function updateQualityStatus(UpdateLoteQualityRequest $request, Lote $lote)
    {
        // La autorización es manejada por el middleware 'can:updateQualityStatus,lote'.
        try {
            DB::beginTransaction(); // Iniciar una transacción de base de datos

            // Lógica de actualización del lote
            $lote->update([
                'estado_calidad' => $request->estado_calidad,
                'observaciones_calidad' => $request->observaciones_calidad,
            ]);

            // Crear un registro en la tabla `control_calidad` para la auditoría de calidad.
            ControlCalidad::create([
                'lote_id' => $lote->id,
                'supervisado_por_user_id' => Auth::id(), // El usuario logueado que realiza el control
                'fecha_control' => Carbon::now(),
                'resultado' => $request->estado_calidad,
                'observaciones' => $request->observaciones_calidad,
            ]);

            DB::commit(); // Confirmar la transacción si todo es exitoso
            return redirect()->route('lotes.show', $lote)->with('success', 'Estado de calidad del lote actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de cualquier error
            Log::error("Error al actualizar estado de calidad del lote: " . $e->getMessage(), ['exception' => $e, 'lote_id' => $lote->id, 'request' => $request->all()]); // Registrar el error
            return back()->withInput()->with('error', 'Hubo un error al actualizar el estado de calidad. Por favor, inténtelo de nuevo.');
        }
    }
}
