<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Para acceder al usuario autenticado
use App\Models\User; // Para contar usuarios (general)
use App\Models\MateriaPrima; // Para inventario de Materias Primas
use App\Models\ProductoTerminado; // Para inventario de Productos Terminados
use App\Models\OrdenProduccion; // Para órdenes de producción
use App\Models\Lote; // Para lotes y control de calidad
use App\Models\Receta; // Para recetas
use App\Models\Proveedor; // Para proveedores
use App\Models\Cliente; // Para clientes
use App\Models\OrdenCompra; // Para órdenes de compra
use App\Models\RecepcionMateriaPrima; // Para recepciones de materia prima
use App\Models\AjusteInventario; // Para ajustes de inventario
use App\Models\StockAlerta; // Para alertas de stock
use App\Models\VentaDespacho; // Para ventas y despachos
use App\Models\DetalleVentaDespacho; // Necesario para sumar subtotales de ventas
use App\Models\ControlCalidad; // Para controles de calidad
use App\Models\ReporteProduccion; // Para reportes de producción
use Carbon\Carbon; // Para trabajar con fechas




class DashboardController extends Controller
{
    /**
     * Muestra el dashboard adaptado al rol del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        // 1. Verificar si hay un usuario autenticado
        if (!Auth::check()) {
            // Si no hay un usuario autenticado, redirigir a la página de login.
            return redirect()->route('login');
        }

        // Obtener el usuario autenticado.
        $user = Auth::user();
        // Obtener el nombre del rol del usuario. Asume que el modelo User tiene una relación 'role'
        // y el modelo Role tiene un campo 'nombre_rol'.
        $userRole = $user->role ? $user->role->nombre_rol : 'sin_rol';

        // Inicializar un array para los datos del dashboard.
        $dashboardData = [];

        // 2. Recopilar datos basados en el rol del usuario.
        switch ($userRole) {
            case 'Gerente General':
                // Datos para Gerente General: Resumen de todo el sistema.
                $dashboardData['usersCount'] = User::count();
                $dashboardData['materiasPrimasCount'] = MateriaPrima::count();
                $dashboardData['productosTerminadosCount'] = ProductoTerminado::count();
                $dashboardData['ordenesProduccionCount'] = OrdenProduccion::whereIn('estado', ['pendiente', 'en_proceso'])->count();
                $dashboardData['lotesPendientesCalidadCount'] = Lote::where('estado_calidad', 'Pendiente')->count();
                $dashboardData['stockAlertasActivasCount'] = StockAlerta::where('resuelta', false)->count();
                $dashboardData['ordenesCompraPendientesCount'] = OrdenCompra::whereIn('estado', ['Pendiente', 'Aprobada'])->count();
                $dashboardData['ventasDespachoPendientesCount'] = VentaDespacho::where('estado_despacho', 'Pendiente')->count();

                // Ejemplos de datos para gráficos (pueden ser más complejos).
                // Producción diaria (ej., últimos 7 días).
                $dashboardData['produccionDiaria'] = OrdenProduccion::selectRaw('DATE(fecha_real_fin) as fecha, COUNT(*) as total_ordenes')
                    ->whereNotNull('fecha_real_fin')
                    ->where('estado', 'completada')
                    ->where('fecha_real_fin', '>=', Carbon::now()->subDays(7))
                    ->groupBy('fecha')
                    ->orderBy('fecha')
                    ->get();

                // Stock actual de productos terminados clave (ej., top 5).
                $dashboardData['topProductosTerminadosStock'] = ProductoTerminado::orderBy('stock_actual', 'asc')
                    ->limit(5)
                    ->get();
                break;

            case 'Jefe de Producción':
            case 'Operario de Producción':
                // Datos para Jefe de Producción/Operario.
                // Órdenes de Producción en curso.
                $dashboardData['ordenesEnCurso'] = OrdenProduccion::whereIn('estado', ['pendiente', 'en_proceso'])
                    ->with('productoTerminado')
                    ->orderBy('fecha_planificada_inicio', 'asc')
                    ->limit(10)
                    ->get()
                    ->map(function ($op) {
                        // Calcular progreso (ej., basado en fechas o lógica de negocio más compleja).
                        $start = Carbon::parse($op->fecha_planificada_inicio);
                        $end = Carbon::parse($op->fecha_planificada_fin);
                        $now = Carbon::now();

                        if ($now->greaterThanOrEqualTo($end)) {
                            $op->progreso = 100; // Si la fecha fin ha pasado, asumir 100%.
                        } elseif ($now->lessThanOrEqualTo($start)) {
                            $op->progreso = 0; // Si aún no ha empezado, 0%.
                        } else {
                            $totalDuration = $end->diffInDays($start);
                            $elapsedDuration = $now->diffInDays($start);
                            $op->progreso = $totalDuration > 0 ? round(($elapsedDuration / $totalDuration) * 100) : 0;
                        }
                        return $op;
                    });

                // Lotes Pendientes de Control de Calidad.
                $dashboardData['lotesPendientesCalidad'] = Lote::where('estado_calidad', 'Pendiente')
                    ->with('productoTerminado')
                    ->orderBy('fecha_produccion', 'asc')
                    ->limit(5)
                    ->get();

                // Producción Completada Hoy.
                $dashboardData['produccionCompletadaHoy'] = OrdenProduccion::where('estado', 'completada')
                    ->whereDate('fecha_real_fin', Carbon::today())
                    ->count();

                // Recetas más utilizadas (ej., en el último mes).
                // Esta lógica puede ser más compleja y requerir agregaciones si no hay un contador directo.
                // Por simplicidad, aquí solo se contará el número total de recetas.
                $dashboardData['totalRecetas'] = Receta::count();
                break;

            case 'Supervisor de Logística':
            case 'Usuario de Inventario':
                // Datos para Supervisor de Logística / Usuario de Inventario.
                // Materias Primas con Stock Bajo.
                $dashboardData['materiasPrimasBajoStock'] = MateriaPrima::whereColumn('stock_actual', '<=', 'stock_minimo')
                    ->orderBy('stock_actual', 'asc')
                    ->limit(10)
                    ->get();

                // Alertas de Stock Activas.
                $dashboardData['alertasStockActivas'] = StockAlerta::where('resuelta', false)
                    ->with('materiaPrima', 'productoTerminado') // Cargar relaciones para mostrar el nombre.
                    ->orderBy('fecha_alerta', 'desc')
                    ->limit(5)
                    ->get();

                // Próximas Recepciones de Materia Prima (ej., para los próximos 7 días).
                $dashboardData['proximasRecepciones'] = RecepcionMateriaPrima::where('fecha_recepcion', '>=', Carbon::today())
                    ->where('fecha_recepcion', '<=', Carbon::today()->addDays(7))
                    ->with('ordenCompra.proveedor', 'materiaPrima')
                    ->orderBy('fecha_recepcion', 'asc')
                    ->limit(5)
                    ->get();

                // Últimos Ajustes de Inventario.
                $dashboardData['ultimosAjustesInventario'] = AjusteInventario::with('materiaPrima', 'productoTerminado')
                    ->orderBy('fecha_ajuste', 'desc')
                    ->limit(5)
                    ->get();
                break;

            case 'Gerente de Ventas':
                // Datos para Gerente de Ventas.
                // Órdenes de Venta/Despacho Pendientes o en Proceso.
                $dashboardData['ventasPendientes'] = VentaDespacho::whereIn('estado_despacho', ['Pendiente', 'En Proceso'])
                    ->with('cliente')
                    ->orderBy('fecha_venta_despacho', 'asc')
                    ->limit(10)
                    ->get();

                // Top 5 Productos Terminados en Stock (para venta rápida).
                $dashboardData['topProductosVenta'] = ProductoTerminado::orderBy('stock_actual', 'desc')
                    ->limit(5)
                    ->get();

                // Ventas del mes actual (resumen): Sumar subtotal de los detalles de venta.
                // Utiliza la tabla 'detalle_venta_despacho' y calcula el subtotal.
                $dashboardData['ventasMesActual'] = DetalleVentaDespacho::query()
                    ->join('ventas_despachos', 'detalle_venta_despacho.venta_despacho_id', '=', 'ventas_despachos.id')
                    ->whereBetween('ventas_despachos.fecha_venta_despacho', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                    ->sum(DB::raw('detalle_venta_despacho.cantidad_vendida_despachada * detalle_venta_despacho.precio_unitario'));


                // Top 5 Clientes (por monto de ventas en el último mes): Sumar subtotal de los detalles de venta.
                $dashboardData['topClientes'] = Cliente::selectRaw('clientes.nombre_cliente, SUM(detalle_venta_despacho.cantidad_vendida_despachada * detalle_venta_despacho.precio_unitario) as total_vendido')
                    ->join('ventas_despachos', 'clientes.id', '=', 'ventas_despachos.cliente_id')
                    ->join('detalle_venta_despacho', 'ventas_despachos.id', '=', 'detalle_venta_despacho.venta_despacho_id')
                    ->whereBetween('ventas_despachos.fecha_venta_despacho', [Carbon::now()->subMonth(), Carbon::now()])
                    ->groupBy('clientes.nombre_cliente')
                    ->orderByDesc('total_vendido')
                    ->limit(5)
                    ->get();
                break;

            case 'Supervisor de Calidad':
                // Datos para Supervisor de Calidad.
                // Lotes con Control de Calidad Pendiente.
                $dashboardData['lotesPendientesControl'] = Lote::where('estado_calidad', 'Pendiente')
                    ->with('productoTerminado')
                    ->orderBy('fecha_produccion', 'asc')
                    ->limit(10)
                    ->get();

                // Resumen de Lotes Aprobados vs. Rechazados (últimos 30 días).
                $lotesRecientes = Lote::selectRaw('estado_calidad, COUNT(*) as count')
                    ->where('fecha_produccion', '>=', Carbon::now()->subDays(30))
                    ->groupBy('estado_calidad')
                    ->get()
                    ->keyBy('estado_calidad');

                $dashboardData['lotesAprobados'] = $lotesRecientes->get('Aprobado')->count ?? 0;
                $dashboardData['lotesRechazados'] = $lotesRecientes->get('Rechazado')->count ?? 0;
                $dashboardData['lotesEnRevision'] = $lotesRecientes->get('En Revisión')->count ?? 0;
                $dashboardData['lotesPendientes'] = $lotesRecientes->get('Pendiente')->count ?? 0;

                // Controles de Calidad Recientes.
                $dashboardData['controlesCalidadRecientes'] = ControlCalidad::with('lote.productoTerminado', 'supervisadoPor')
                    ->orderBy('fecha_control', 'desc')
                    ->limit(5)
                    ->get();
                break;

            default:
                // Rol por defecto o sin rol específico, podría mostrar algo básico o redirigir.
                $dashboardData['message'] = 'No tienes un rol específico para el dashboard. Contacta al administrador.';
                // Contadores básicos como fallback.
                $dashboardData['usersCount'] = User::count();
                $dashboardData['materiasPrimasCount'] = MateriaPrima::count();
                $dashboardData['productosTerminadosCount'] = ProductoTerminado::count();
                break;
        }

        // Pasar el rol del usuario y los datos a la vista.
        return view('dashboard', [ // Asegúrate de que esta vista sea 'dashboard'
            'userRole' => $userRole,
            'dashboardData' => $dashboardData,
        ]);
    }
}
