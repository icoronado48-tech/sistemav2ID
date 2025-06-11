<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReporteProduccion;
use App\Models\User;
use App\Models\OrdenProduccion;
use Carbon\Carbon;

class ReporteProduccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jefeProduccionUser = User::where('email', 'juan.produccion@example.com')->first();
        $operarioUser = User::where('email', 'maria.operaria@example.com')->first();
        // Asegúrate de que los estados coincidan con la migración de orden_produccion (minúsculas, guiones bajos)
        $ordenProduccionCompletada = OrdenProduccion::where('estado', 'completada')->first(); // Corregido: 'completado' a 'completada'

        // Validaciones
        if (!$jefeProduccionUser) {
            $this->command->error('Error: Usuario "juan.produccion@example.com" no encontrado para ReporteProduccionSeeder.');
            return;
        }
        if (!$operarioUser) {
            $this->command->error('Error: Usuario "maria.operaria@example.com" no encontrado para ReporteProduccionSeeder.');
            return;
        }
        if (!$ordenProduccionCompletada) {
            $this->command->error('Error: Orden de Producción con estado "completada" no encontrada para ReporteProduccionSeeder.');
            return;
        }


        // Reporte de una orden de producción completada
        if ($ordenProduccionCompletada && $jefeProduccionUser) {
            ReporteProduccion::create([
                'fecha_reporte' => Carbon::now(), // CORREGIDO: 'fecha_generacion' a 'fecha_reporte'
                'tipo_reporte' => 'Orden de Producción',
                // Guardar los parámetros como parte del contenido_reporte si es necesario
                'contenido_reporte' => json_encode([ // CORREGIDO: 'ruta_archivo_generado' a 'contenido_reporte'
                    'orden_id' => $ordenProduccionCompletada->id,
                    'observaciones' => 'Reporte detallado de la Orden de Producción #' . $ordenProduccionCompletada->id,
                    // Si la ruta del archivo se genera y se guarda aquí, considera cómo se maneja
                    'ruta_archivo_generado' => 'reports/produccion/orden_' . $ordenProduccionCompletada->id . '_' . Carbon::now()->format('Ymd_His') . '.pdf',
                ]),
                'generado_por_user_id' => $jefeProduccionUser->id,
                // 'estado' => 'generado', // ELIMINADO: No existe en la migración
                // 'observaciones' => 'Reporte detallado de la Orden de Producción #' . $ordenProduccionCompletada->id, // ELIMINADO, contenido en 'contenido_reporte'
            ]);
        }

        // Otro reporte, quizás de resumen diario
        if ($operarioUser) {
            ReporteProduccion::create([
                'fecha_reporte' => Carbon::now()->subDay(), // CORREGIDO
                'tipo_reporte' => 'Resumen Diario',
                'contenido_reporte' => json_encode([ // CORREGIDO
                    'fecha' => Carbon::now()->subDay()->toDateString(),
                    'resumen' => 'Resumen de producción diario.',
                    'ruta_archivo_generado' => 'reports/resumen_diario_' . Carbon::now()->subDay()->format('Ymd') . '.xlsx',
                ]),
                'generado_por_user_id' => $operarioUser->id,
                // 'estado' => 'generado', // ELIMINADO
                // 'observaciones' => 'Resumen de producción diario.', // ELIMINADO
            ]);
        }
    }
}
