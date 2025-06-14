<?php

namespace App\Http\Controllers;

use App\Models\ReporteProduccion;
use App\Models\User;
use App\Models\OrdenProduccion;
use App\Models\Lote;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\ReporteProduccion\StoreReporteProduccionRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ReporteProduccionController extends Controller
{
    /**
     * Constructor to apply policies to resource methods.
     */
    public function __construct()
    {
        // Reports are usually read-only or generated, not fully CRUD.
        // We'll allow index, create, store, show.
        $this->authorizeResource(ReporteProduccion::class, 'reporte_produccion', [
            'except' => ['edit', 'update', 'destroy'] // No edit, update, delete for reports
        ]);
    }

    public function index()
    {
        $reportes = ReporteProduccion::with('generadoPor')->paginate(10);
        return view('reportes_produccion.index', compact('reportes'));
    }

    public function create()
    {
        $users = User::whereHas('role', function ($query) {
            $query->whereIn('nombre_rol', ['administrador', 'produccion']);
        })->get();
        $ordenesProduccion = OrdenProduccion::all(); // For reports by order
        return view('reportes_produccion.create', compact('users', 'ordenesProduccion'));
    }

    public function store(StoreReporteProduccionRequest $request)
    {
        try {
            DB::beginTransaction();

            // Logic to generate the actual report content
            // Pass only validated parameters to the private method
            $contenido = $this->generateReportContent($request->tipo_reporte, $request->parametros_generacion ?? []);
            // Nota: saveReportToFile es un placeholder. Si realmente generas archivos,
            // asegúrate de que la lógica sea robusta y guarde en un lugar seguro.
            // Y que la ruta sea accesible públicamente si vas a vincularla.
            $rutaArchivo = $this->saveReportToFile($request->tipo_reporte, $contenido);

            ReporteProduccion::create([
                'fecha_reporte' => Carbon::now(), // O la fecha seleccionada para el reporte
                'tipo_reporte' => $request->tipo_reporte,
                'contenido_reporte' => json_encode($contenido), // Store content as JSON
                'generado_por_user_id' => Auth::id(), // Assign logged-in user
                'observaciones' => $request->observaciones,
                // 'ruta_archivo_generado' => $rutaArchivo, // Uncomment if you implement actual file saving
            ]);

            DB::commit();
            return redirect()->route('reportes-produccion.index')->with('success', 'Reporte generado y guardado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al generar y guardar reporte de producción: " . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            return back()->withInput()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    public function show(ReporteProduccion $reporteProduccion)
    {
        $reporteProduccion->load('generadoPor');
        return view('reportes_produccion.show', compact('reporteProduccion'));
    }

    // Auxiliary methods for report generation
    private function generateReportContent(string $tipoReporte, array $parametros)
    {
        switch ($tipoReporte) {
            case 'diario':
                $fecha = Carbon::parse($parametros['fecha'] ?? Carbon::now()->toDateString());
                $lotesDelDia = Lote::whereDate('fecha_produccion', $fecha)->get();
                return ['fecha' => $fecha->toDateString(), 'lotes' => $lotesDelDia->toArray()];
            case 'lote':
                // Ensure lote_id is present and valid
                if (!isset($parametros['lote_id'])) {
                    return ['mensaje' => 'Parámetro lote_id es requerido para reporte por lote.'];
                }
                $lote = Lote::with('ordenProduccion.productoTerminado', 'controlesCalidad')->find($parametros['lote_id']);
                return $lote ? $lote->toArray() : ['mensaje' => 'Lote no encontrado.'];
                // ... other report types
            default:
                return ['mensaje' => 'Tipo de reporte no reconocido o no implementado.'];
        }
    }

    // You could have a method to save the report as PDF/Excel and return the path
    private function saveReportToFile(string $tipoReporte, array $contenido)
    {
        // Implement logic to generate a PDF/Excel file and save it
        // E.g., use packages like Dompdf, Laravel Excel (Maatwebsite/Laravel-Excel)
        // For now, just a placeholder path (not actually saving a file).
        // If you implement this, ensure the 'storage/app/reports' directory is writable
        // and consider using Laravel's Storage facade.
        $filename = "report_{$tipoReporte}_" . Carbon::now()->format('Ymd_His') . ".json"; // Or .pdf/.xlsx
        // file_put_contents(storage_path('app/reports/' . $filename), json_encode($contenido, JSON_PRETTY_PRINT));
        return 'storage/reports/' . $filename; // Public path if accessible
    }

    // No edit, update, delete methods as reports are usually historical records generated by the system.
}
