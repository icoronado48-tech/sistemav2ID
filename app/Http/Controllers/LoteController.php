<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\ControlCalidad; // Para crear el registro de control de calidad
use Illuminate\Http\Request;
use App\Http\Requests\Lote\UpdateLoteQualityRequest; // Crear

class LoteController extends Controller
{
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

    // No hay `create`, `store`, `edit`, `update`, `destroy` CRUD estándar para Lote,
    // ya que se generan principalmente a través de OrdenProduccion y su estado.
    // Solo un método para actualizar estado de calidad

    public function updateQualityStatus(UpdateLoteQualityRequest $request, Lote $lote)
    {
        // El seeder de ControlCalidad hace:
        // ControlCalidad::create([
        //     'lote_id' => $lote->id,
        //     'fecha_control' => Carbon::now(),
        //     'observaciones' => 'Textura y sabor conformes. Apariencia ligeramente irregular.',
        //     'resultado' => 'aprobado',
        //     'supervisado_por_user_id' => $supervisorCalidadUser->id,
        // ]);

        // Lógica de actualización
        $lote->update([
            'estado_calidad' => $request->estado_calidad,
            'observaciones_calidad' => $request->observaciones_calidad,
        ]);

        // Opcional: Crear un registro en la tabla `control_calidad`
        ControlCalidad::create([
            'lote_id' => $lote->id,
            'supervisado_por_user_id' => auth()->id(), // El usuario logueado que realiza el control
            'fecha_control' => now(),
            'resultado' => $request->estado_calidad,
            'observaciones' => $request->observaciones_calidad,
        ]);

        return redirect()->route('lotes.show', $lote)->with('success', 'Estado de calidad del lote actualizado.');
    }
}
