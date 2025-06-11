<?php

namespace App\Http\Controllers;

use App\Models\ReporteProduccion;
use App\Models\User;
use App\Models\OrdenProduccion;
use App\Models\Lote;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\ReporteProduccion\StoreReporteProduccionRequest;

class ReporteProduccionController extends Controller
{
    public function index()
    {
        $reportes = ReporteProduccion::with('generadoPor')->paginate(10);
        return view('reportes_produccion.index', compact('reportes'));
    }

    public function create()
    {
        $users = User::all();
        $ordenesProduccion = OrdenProduccion::all(); // Para reportes por orden
        return view('reportes_produccion.create', compact('users', 'ordenesProduccion'));
    }

    public function store(StoreReporteProduccionRequest $request)
    {
        // Seeder: ReporteProduccion::create([
        //     'fecha_generacion' => Carbon::now(),
        //     'tipo_reporte' => 'Orden de Producción',
        //     'parametros_generacion' => json_encode(['orden_id' => $ordenProduccionCompletada->id]),
        //     'ruta_archivo_generado' => 'reports/produccion/orden_' . $ordenProduccionCompletada->id . '_' . Carbon::now()->format('Ymd_His') . '.pdf',
        //     'generado_por_user_id' => $jefeProduccionUser->id,
        //     'estado' => 'generado',
        //     'observaciones' => 'Reporte detallado de la Orden de Producción #' . $ordenProduccionCompletada->id,
        // ]);

        // Lógica para generar el contenido real del reporte
        $contenido = $this->generateReportContent($request->tipo_reporte, $request->parametros_generacion);
        $rutaArchivo = $this->saveReportToFile($request->tipo_reporte, $contenido);

        ReporteProduccion::create([
            'fecha_reporte' => Carbon::now(), // O la fecha seleccionada para el reporte
            'tipo_reporte' => $request->tipo_reporte,
            'contenido_reporte' => json_encode($contenido), // Guarda el contenido como JSON
            'generado_por_user_id' => Auth::id(),
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('reportes-produccion.index')->with('success', 'Reporte generado y guardado exitosamente.');
    }

    public function show(ReporteProduccion $reporteProduccion)
    {
        $reporteProduccion->load('generadoPor');
        return view('reportes_produccion.show', compact('reporteProduccion'));
    }

    // Métodos auxiliares para la generación de reportes
    private function generateReportContent(string $tipoReporte, array $parametros)
    {
        switch ($tipoReporte) {
            case 'diario':
                // Lógica para generar reporte diario
                // Ej: total de producción, lotes aprobados/rechazados del día
                $fecha = Carbon::parse($parametros['fecha'] ?? Carbon::now()->toDateString());
                $lotesDelDia = Lote::whereDate('fecha_produccion', $fecha)->get();
                return ['fecha' => $fecha->toDateString(), 'lotes' => $lotesDelDia->toArray()];
            case 'lote':
                // Lógica para reporte por lote específico
                $lote = Lote::with('ordenProduccion.productoTerminado', 'controlesCalidad')->find($parametros['lote_id']);
                return $lote ? $lote->toArray() : [];
                // ... otros tipos de reportes
            default:
                return ['mensaje' => 'Tipo de reporte no reconocido o no implementado.'];
        }
    }

    // Podrías tener un método para guardar el reporte como PDF/Excel y retornar la ruta
    private function saveReportToFile(string $tipoReporte, array $contenido)
    {
        // Implementar lógica para generar un archivo PDF/Excel y guardarlo
        // Ej: usar paquetes como Dompdf, Laravel Excel (Maatwebsite/Laravel-Excel)
        // Por ahora, solo un placeholder
        $filename = "report_{$tipoReporte}_" . Carbon::now()->format('Ymd_His') . ".json"; // O .pdf/.xlsx
        // file_put_contents(storage_path('app/reports/' . $filename), json_encode($contenido, JSON_PRETTY_PRINT));
        return 'storage/reports/' . $filename; // Ruta pública si es accesible
    }
}
