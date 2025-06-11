<?php

namespace App\Http\Controllers;

use App\Models\ControlCalidad;
use App\Models\Lote;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ControlCalidad\StoreControlCalidadRequest;
use App\Http\Requests\ControlCalidad\UpdateControlCalidadRequest;

class ControlCalidadController extends Controller
{
    public function index()
    {
        $controles = ControlCalidad::with('lote.productoTerminado', 'supervisadoPor')->paginate(10);
        return view('controles_calidad.index', compact('controles'));
    }

    public function create()
    {
        $lotesPendientes = Lote::where('estado_calidad', 'Pendiente')->get();
        $supervisores = User::whereHas('role', function ($query) {
            $query->where('nombre_rol', 'Supervisor de Calidad'); // Asumiendo que tienes un rol 'Supervisor de Calidad'
        })->get();

        return view('controles_calidad.create', compact('lotesPendientes', 'supervisores'));
    }

    public function store(StoreControlCalidadRequest $request)
    {
        // Seeder: ControlCalidad::create([
        //     'lote_id' => $loteRechazado->id,
        //     'fecha_control' => Carbon::now()->subDay(),
        //     'observaciones' => 'Defectos en el sellado y masa cruda. Lote rechazado.',
        //     'resultado' => 'rechazado',
        //     'supervisado_por_user_id' => $supervisorCalidadUser->id,
        // ]);

        $control = ControlCalidad::create($request->validated());

        // Actualizar el estado de calidad del lote después de registrar el control
        $lote = Lote::find($request->lote_id);
        if ($lote) {
            $lote->update([
                'estado_calidad' => $request->resultado,
                'observaciones_calidad' => $request->observaciones,
            ]);
        }

        return redirect()->route('controles-calidad.index')->with('success', 'Control de calidad registrado exitosamente.');
    }

    public function show(ControlCalidad $controlCalidad)
    {
        $controlCalidad->load('lote.productoTerminado', 'supervisadoPor');
        return view('controles_calidad.show', compact('controlCalidad'));
    }

    public function edit(ControlCalidad $controlCalidad)
    {
        $lotes = Lote::all(); // Puedes filtrar si solo quieres lotes relevantes
        $supervisores = User::whereHas('role', function ($query) {
            $query->where('nombre_rol', 'Supervisor de Calidad');
        })->get();
        return view('controles_calidad.edit', compact('controlCalidad', 'lotes', 'supervisores'));
    }

    public function update(UpdateControlCalidadRequest $request, ControlCalidad $controlCalidad)
    {
        $controlCalidad->update($request->validated());

        // Opcional: Actualizar el estado del lote si se modificó el resultado del control
        $lote = $controlCalidad->lote;
        if ($lote && $lote->estado_calidad !== $request->resultado) {
            $lote->update([
                'estado_calidad' => $request->resultado,
                'observaciones_calidad' => $request->observaciones,
            ]);
        }

        return redirect()->route('controles-calidad.index')->with('success', 'Control de calidad actualizado exitosamente.');
    }

    public function destroy(ControlCalidad $controlCalidad)
    {
        $controlCalidad->delete();
        return redirect()->route('controles-calidad.index')->with('success', 'Control de calidad eliminado exitosamente.');
    }
}
