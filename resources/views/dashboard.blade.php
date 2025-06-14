 @extends('layouts.admin') {{-- Asegúrate de que esto extienda tu layout principal --}}

 @section('content')
     <div class="content-header">
         <div class="container-fluid">
             <div class="row mb-2">
                 <div class="col-sm-12">
                     <h1 class="m-0 text-dark">Dashboard de {{ $userRole ?? 'Usuario' }}</h1>
                 </div><!-- /.col -->
             </div><!-- /.row -->
         </div><!-- /.container-fluid -->
     </div>
     <!-- /.content-header -->

     <!-- Main content -->
     <section class="content">
         <div class="container-fluid">

             {{-- Mensajes de éxito/error --}}
             @if (session('success'))
                 <div class="alert alert-success alert-dismissible fade show" role="alert">
                     {{ session('success') }}
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
             @endif

             @if (session('error'))
                 <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     {{ session('error') }}
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
             @endif

             <div class="row">
                 @if (($userRole ?? 'sin_rol') == 'Gerente General')
                     {{-- Tarjetas de información para Gerente General --}}
                     <div class="col-lg-3 col-6">
                         <div class="small-box bg-primary"> {{-- Azul para Usuarios --}}
                             <div class="inner">
                                 <h3>{{ $dashboardData['usersCount'] ?? 0 }}</h3>
                                 <p>Usuarios Registrados</p>
                             </div>
                             <div class="icon">
                                 <i class="fas fa-users"></i>
                             </div>
                             <a href="{{ route('users.index') }}" class="small-box-footer">Más información <i
                                     class="fas fa-arrow-circle-right"></i></a>
                         </div>
                     </div>
                     <div class="col-lg-3 col-6">
                         <div class="small-box bg-success"> {{-- Verde para Materias Primas --}}
                             <div class="inner">
                                 <h3>{{ $dashboardData['materiasPrimasCount'] ?? 0 }}</h3>
                                 <p>Materias Primas</p>
                             </div>
                             <div class="icon">
                                 <i class="fas fa-boxes"></i>
                             </div>
                             <a href="{{ route('materias-primas.index') }}" class="small-box-footer">Más información <i
                                     class="fas fa-arrow-circle-right"></i></a>
                         </div>
                     </div>
                     <div class="col-lg-3 col-6">
                         <div class="small-box bg-info"> {{-- Celeste para Productos Terminados --}}
                             <div class="inner">
                                 <h3>{{ $dashboardData['productosTerminadosCount'] ?? 0 }}</h3>
                                 <p>Productos Terminados</p>
                             </div>
                             <div class="icon">
                                 <i class="fas fa-cube"></i>
                             </div>
                             <a href="{{ route('productos-terminados.index') }}" class="small-box-footer">Más información <i
                                     class="fas fa-arrow-circle-right"></i></a>
                         </div>
                     </div>
                     <div class="col-lg-3 col-6">
                         <div class="small-box bg-warning"> {{-- Amarillo para Órdenes de Producción Activas --}}
                             <div class="inner">
                                 <h3>{{ $dashboardData['ordenesProduccionCount'] ?? 0 }}</h3>
                                 <p>Orden Prod. Activas</p>
                             </div>
                             <div class="icon">
                                 <i class="fas fa-industry"></i>
                             </div>
                             <a href="{{ route('ordenes-produccion.index') }}" class="small-box-footer">Más información <i
                                     class="fas fa-arrow-circle-right"></i></a>
                         </div>
                     </div>
                     <div class="col-lg-3 col-6">
                         <div class="small-box bg-danger"> {{-- Rojo para Lotes Pendientes de Calidad --}}
                             <div class="inner">
                                 <h3>{{ $dashboardData['lotesPendientesCalidadCount'] ?? 0 }}</h3>
                                 <p>Lotes Pendientes de Calidad</p>
                             </div>
                             <div class="icon">
                                 <i class="fas fa-clipboard-check"></i>
                             </div>
                             <a href="{{ route('lotes.index') }}" class="small-box-footer">Más información <i
                                     class="fas fa-arrow-circle-right"></i></a>
                         </div>
                     </div>
                     <div class="col-lg-3 col-6">
                         <div class="small-box" style= "background-color: #9f20c9; color: white;"> {{-- Púrpura para Alertas de Stock --}}
                             <div class="inner">
                                 <h3>{{ $dashboardData['stockAlertasActivasCount'] ?? 0 }}</h3>
                                 <p>Alertas de Stock Activas</p>
                             </div>
                             <div class="icon">
                                 <i class="fas fa-exclamation-triangle"></i>
                             </div>
                             <a href="{{ route('stock-alertas.index') }}" class="small-box-footer"
                                 style="color: rgba(255, 255, 255, 0.8);">Más
                                 información <i class="fas fa-arrow-circle-right"></i></a>
                         </div>
                     </div>
                     <div class="col-lg-3 col-6">
                         <div class="small-box" style="background-color: #20c997; color: white;"> {{-- Turquesa para Órdenes de Compra --}}
                             <div class="inner">
                                 <h3>{{ $dashboardData['ordenesCompraPendientesCount'] ?? 0 }}</h3>
                                 <p>O/C Pendientes</p>
                             </div>
                             <div class="icon">
                                 <i class="fas fa-shopping-cart"></i>
                             </div>
                             <a href="{{ route('ordenes-compra.index') }}" class="small-box-footer"
                                 style="color: rgba(255, 255, 255, 0.8);">Más
                                 información <i class="fas fa-arrow-circle-right"></i></a>
                         </div>
                     </div>
                     <div class="col-lg-3 col-6">
                         <div class="small-box" style="background-color: #fd7e14; color: white;"> {{-- Naranja para Ventas/Despachos --}}
                             <div class="inner">
                                 <h3>{{ $dashboardData['ventasDespachoPendientesCount'] ?? 0 }}</h3>
                                 <p>Ventas/Despachos Pendientes</p>
                             </div>
                             <div class="icon">
                                 <i class="fas fa-truck-loading"></i>
                             </div>
                             <a href="{{ route('ventas-despachos.index') }}" class="small-box-footer"
                                 style="color: rgba(255, 255, 255, 0.8);">Más
                                 información <i class="fas fa-arrow-circle-right"></i></a>
                         </div>
                     </div>

                     {{-- Gráfico de Producción Diaria --}}
                     <div class="col-lg-6">
                         <div class="card card-info">
                             <div class="card-header">
                                 <h3 class="card-title">Producción Diaria (Últimos 7 Días)</h3>
                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                 </div>
                             </div>
                             <div class="card-body">
                                 <div class="chart">
                                     <canvas id="produccionDiariaChart"
                                         style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                 </div>
                             </div>
                         </div>
                     </div>

                     {{-- Tabla de Top Productos Terminados en Stock --}}
                     <div class="col-lg-6">
                         <div class="card card-success">
                             <div class="card-header">
                                 <h3 class="card-title">Top Productos Terminados con Bajo Stock</h3>
                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                 </div>
                             </div>
                             <div class="card-body p-0">
                                 <table class="table table-striped">
                                     <thead>
                                         <tr>
                                             <th>Producto</th>
                                             <th>Stock Actual</th>
                                             <th>Unidad</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @forelse ($dashboardData['topProductosTerminadosStock'] ?? [] as $producto)
                                             <tr>
                                                 <td>{{ $producto->nombre_producto }}</td>
                                                 <td>{{ $producto->stock_actual }}</td>
                                                 <td>{{ $producto->unidad_medida_salida }}</td>
                                             </tr>
                                         @empty
                                             <tr>
                                                 <td colspan="3">No hay productos terminados con stock bajo.</td>
                                             </tr>
                                         @endforelse
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>
                 @endif

                 @if (($userRole ?? 'sin_rol') == 'Jefe de Producción' || ($userRole ?? 'sin_rol') == 'Operario de Producción')
                     {{-- Órdenes de Producción en Curso --}}
                     <div class="col-lg-6">
                         <div class="card card-primary"> {{-- Azul para Órdenes de Producción --}}
                             <div class="card-header">
                                 <h3 class="card-title">Órdenes de Producción en Curso</h3>
                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                 </div>
                             </div>
                             <div class="card-body p-0">
                                 <table class="table table-striped">
                                     <thead>
                                         <tr>
                                             <th>Producto</th>
                                             <th>Cantidad</th>
                                             <th>Estado</th>
                                             <th>Progreso</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @forelse ($dashboardData['ordenesEnCurso'] ?? [] as $orden)
                                             <tr>
                                                 <td>{{ $orden->productoTerminado->nombre_producto ?? 'N/A' }}</td>
                                                 <td>{{ $orden->cantidad_a_producir }}</td>
                                                 <td><span
                                                         class="badge badge-{{ $orden->estado == 'en_proceso' ? 'info' : 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $orden->estado)) }}</span>
                                                 </td>
                                                 <td>
                                                     <div class="progress progress-xs">
                                                         <div class="progress-bar bg-primary"
                                                             style="width: {{ $orden->progreso }}%"></div>
                                                     </div>
                                                     <small class="text-muted">{{ $orden->progreso }}% Completo</small>
                                                 </td>
                                             </tr>
                                         @empty
                                             <tr>
                                                 <td colspan="4">No hay órdenes de producción en curso.</td>
                                             </tr>
                                         @endforelse
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>

                     {{-- Lotes Pendientes de Control de Calidad --}}
                     <div class="col-lg-6">
                         <div class="card card-danger"> {{-- Rojo para Lotes Pendientes de Calidad --}}
                             <div class="card-header">
                                 <h3 class="card-title">Lotes Pendientes de Control de Calidad</h3>
                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                 </div>
                             </div>
                             <div class="card-body p-0">
                                 <table class="table table-striped">
                                     <thead>
                                         <tr>
                                             <th>Lote ID</th>
                                             <th>Producto</th>
                                             <th>Fecha Producción</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @forelse ($dashboardData['lotesPendientesCalidad'] ?? [] as $lote)
                                             <tr>
                                                 <td>{{ $lote->id }}</td>
                                                 <td>{{ $lote->productoTerminado->nombre_producto ?? 'N/A' }}</td>
                                                 <td>{{ $lote->fecha_produccion->format('d/m/Y') }}</td>
                                             </tr>
                                         @empty
                                             <tr>
                                                 <td colspan="3">No hay lotes pendientes de control de calidad.</td>
                                             </tr>
                                         @endforelse
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>

                     {{-- Producción Completada Hoy --}}
                     <div class="col-lg-3 col-6">
                         <div class="small-box bg-success"> {{-- Verde para Producción Completada --}}
                             <div class="inner">
                                 <h3>{{ $dashboardData['produccionCompletadaHoy'] ?? 0 }}</h3>
                                 <p>Producción Completada Hoy</p>
                             </div>
                             <div class="icon">
                                 <i class="fas fa-check-circle"></i>
                             </div>
                             <a href="{{ route('ordenes-produccion.index', ['estado' => 'completada', 'fecha' => 'hoy']) }}"
                                 class="small-box-footer">Ver Detalles <i class="fas fa-arrow-circle-right"></i></a>
                         </div>
                     </div>

                     {{-- Total de Recetas --}}
                     <div class="col-lg-3 col-6">
                         <div class="small-box bg-info"> {{-- Celeste para Recetas --}}
                             <div class="inner">
                                 <h3>{{ $dashboardData['totalRecetas'] ?? 0 }}</h3>
                                 <p>Recetas Registradas</p>
                             </div>
                             <div class="icon">
                                 <i class="fas fa-book"></i>
                             </div>
                             <a href="{{ route('recetas.index') }}" class="small-box-footer">Ver Recetas <i
                                     class="fas fa-arrow-circle-right"></i></a>
                         </div>
                     </div>
                 @endif

                 @if (($userRole ?? 'sin_rol') == 'Supervisor de Logística' || ($userRole ?? 'sin_rol') == 'Usuario de Inventario')
                     {{-- Materias Primas con Stock Bajo --}}
                     <div class="col-lg-6">
                         <div class="card card-danger"> {{-- Rojo para Materias Primas con Stock Bajo --}}
                             <div class="card-header">
                                 <h3 class="card-title">Materias Primas con Stock Bajo</h3>
                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                 </div>
                             </div>
                             <div class="card-body p-0">
                                 <table class="table table-striped">
                                     <thead>
                                         <tr>
                                             <th>Materia Prima</th>
                                             <th>Stock Actual</th>
                                             <th>Stock Mínimo</th>
                                             <th>Unidad</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @forelse ($dashboardData['materiasPrimasBajoStock'] ?? [] as $materia)
                                             <tr>
                                                 <td>{{ $materia->nombre }}</td>
                                                 <td>{{ $materia->stock_actual }}</td>
                                                 <td>{{ $materia->stock_minimo }}</td>
                                                 <td>{{ $materia->unidad_medida }}</td>
                                             </tr>
                                         @empty
                                             <tr>
                                                 <td colspan="4">Todas las materias primas están en buen nivel de stock.
                                                 </td>
                                             </tr>
                                         @endforelse
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>

                     {{-- Alertas de Stock Activas --}}
                     <div class="col-lg-6">
                         <div class="card card-warning"> {{-- Amarillo para Alertas de Stock --}}
                             <div class="card-header">
                                 <h3 class="card-title">Alertas de Stock Activas</h3>
                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                 </div>
                             </div>
                             <div class="card-body p-0">
                                 <table class="table table-striped">
                                     <thead>
                                         <tr>
                                             <th>Tipo</th>
                                             <th>Elemento</th>
                                             <th>Nivel Actual</th>
                                             <th>Mensaje</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @forelse ($dashboardData['alertasStockActivas'] ?? [] as $alerta)
                                             <tr>
                                                 <td>{{ $alerta->tipo_alerta }}</td>
                                                 <td>
                                                     @if ($alerta->materiaPrima)
                                                         {{ $alerta->materiaPrima->nombre }} (MP)
                                                     @elseif ($alerta->productoTerminado)
                                                         {{ $alerta->productoTerminado->nombre_producto }} (PT)
                                                     @else
                                                         N/A
                                                     @endif
                                                 </td>
                                                 <td>{{ $alerta->nivel_actual }}</td>
                                                 <td>{{ $alerta->mensaje }}</td>
                                             </tr>
                                         @empty
                                             <tr>
                                                 <td colspan="4">No hay alertas de stock activas.</td>
                                             </tr>
                                         @endforelse
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>

                     {{-- Próximas Recepciones de Materia Prima --}}
                     <div class="col-lg-6">
                         <div class="card card-info"> {{-- Celeste para Próximas Recepciones --}}
                             <div class="card-header">
                                 <h3 class="card-title">Próximas Recepciones de Materia Prima</h3>
                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                 </div>
                             </div>
                             <div class="card-body p-0">
                                 <table class="table table-striped">
                                     <thead>
                                         <tr>
                                             <th>Orden Compra</th>
                                             <th>Materia Prima</th>
                                             <th>Cantidad</th>
                                             <th>Fecha Recepción</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @forelse ($dashboardData['proximasRecepciones'] ?? [] as $recepcion)
                                             <tr>
                                                 <td>{{ $recepcion->ordenCompra->numero_documento ?? 'N/A' }}</td>
                                                 <td>{{ $recepcion->materiaPrima->nombre ?? 'N/A' }}</td>
                                                 <td>{{ $recepcion->cantidad_recibida }}</td>
                                                 <td>{{ $recepcion->fecha_recepcion->format('d/m/Y') }}</td>
                                             </tr>
                                         @empty
                                             <tr>
                                                 <td colspan="4">No hay próximas recepciones de materia prima.</td>
                                             </tr>
                                         @endforelse
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>

                     {{-- Últimos Ajustes de Inventario --}}
                     <div class="col-lg-6">
                         <div class="card card-secondary"> {{-- Gris oscuro para Ajustes de Inventario --}}
                             <div class="card-header">
                                 <h3 class="card-title">Últimos Ajustes de Inventario</h3>
                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                 </div>
                             </div>
                             <div class="card-body p-0">
                                 <table class="table table-striped">
                                     <thead>
                                         <tr>
                                             <th>Tipo Ajuste</th>
                                             <th>Elemento</th>
                                             <th>Cantidad</th>
                                             <th>Fecha</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @forelse ($dashboardData['ultimosAjustesInventario'] ?? [] as $ajuste)
                                             <tr>
                                                 <td>{{ $ajuste->tipo_ajuste }}</td>
                                                 <td>
                                                     @if ($ajuste->materiaPrima)
                                                         {{ $ajuste->materiaPrima->nombre }} (MP)
                                                     @elseif ($ajuste->productoTerminado)
                                                         {{ $ajuste->productoTerminado->nombre_producto }} (PT)
                                                     @else
                                                         N/A
                                                     @endif
                                                 </td>
                                                 <td>{{ $ajuste->cantidad_ajustada }}</td>
                                                 <td>{{ $ajuste->fecha_ajuste->format('d/m/Y') }}</td>
                                             </tr>
                                         @empty
                                             <tr>
                                                 <td colspan="4">No hay ajustes de inventario recientes.</td>
                                             </tr>
                                         @endforelse
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>
                 @endif

                 @if (($userRole ?? 'sin_rol') == 'Gerente de Ventas')
                     {{-- Ventas del Mes Actual --}}
                     <div class="col-lg-3 col-6">
                         <div class="small-box bg-success"> {{-- Verde para Ventas Netas --}}
                             <div class="inner">
                                 <h3>${{ number_format($dashboardData['ventasMesActual'] ?? 0, 2) }}</h3>
                                 <p>Ventas Netas Este Mes</p>
                             </div>
                             <div class="icon">
                                 <i class="fas fa-dollar-sign"></i>
                             </div>
                             <a href="{{ route('ventas-despachos.index') }}" class="small-box-footer">Ver Reporte <i
                                     class="fas fa-arrow-circle-right"></i></a>
                         </div>
                     </div>

                     {{-- Órdenes de Venta/Despacho Pendientes --}}
                     <div class="col-lg-6">
                         <div class="card card-info"> {{-- Celeste para Ventas/Despachos Pendientes --}}
                             <div class="card-header">
                                 <h3 class="card-title">Ventas/Despachos Pendientes</h3>
                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                 </div>
                             </div>
                             <div class="card-body p-0">
                                 <table class="table table-striped">
                                     <thead>
                                         <tr>
                                             <th>Cliente</th>
                                             <th>Documento</th>
                                             <th>Fecha</th>
                                             <th>Estado</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @forelse ($dashboardData['ventasPendientes'] ?? [] as $venta)
                                             <tr>
                                                 <td>{{ $venta->cliente->nombre_cliente ?? 'N/A' }}</td>
                                                 <td>{{ $venta->tipo_documento }} - {{ $venta->numero_documento }}</td>
                                                 <td>{{ $venta->fecha_venta_despacho->format('d/m/Y') }}</td>
                                                 <td><span
                                                         class="badge badge-warning">{{ $venta->estado_despacho }}</span>
                                                 </td>
                                             </tr>
                                         @empty
                                             <tr>
                                                 <td colspan="4">No hay ventas/despachos pendientes.</td>
                                             </tr>
                                         @endforelse
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>

                     {{-- Top 5 Clientes --}}
                     <div class="col-lg-6">
                         <div class="card card-primary"> {{-- Azul para Top Clientes --}}
                             <div class="card-header">
                                 <h3 class="card-title">Top 5 Clientes (Último Mes)</h3>
                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                 </div>
                             </div>
                             <div class="card-body p-0">
                                 <table class="table table-striped">
                                     <thead>
                                         <tr>
                                             <th>Cliente</th>
                                             <th>Monto Vendido</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @forelse ($dashboardData['topClientes'] ?? [] as $cliente)
                                             <tr>
                                                 <td>{{ $cliente->nombre_cliente }}</td>
                                                 <td>${{ number_format($cliente->total_vendido, 2) }}</td>
                                             </tr>
                                         @empty
                                             <tr>
                                                 <td colspan="2">No hay datos de top clientes.</td>
                                             </tr>
                                         @endforelse
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>
                 @endif

                 @if (($userRole ?? 'sin_rol') == 'Supervisor de Calidad')
                     {{-- Lotes con Control de Calidad Pendiente --}}
                     <div class="col-lg-6">
                         <div class="card card-warning"> {{-- Amarillo para Lotes Pendientes de Control --}}
                             <div class="card-header">
                                 <h3 class="card-title">Lotes con Control de Calidad Pendiente</h3>
                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                 </div>
                             </div>
                             <div class="card-body p-0">
                                 <table class="table table-striped">
                                     <thead>
                                         <tr>
                                             <th>Lote ID</th>
                                             <th>Producto</th>
                                             <th>Fecha Producción</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @forelse ($dashboardData['lotesPendientesControl'] ?? [] as $lote)
                                             <tr>
                                                 <td>{{ $lote->id }}</td>
                                                 <td>{{ $lote->productoTerminado->nombre_producto ?? 'N/A' }}</td>
                                                 <td>{{ $lote->fecha_produccion->format('d/m/Y') }}</td>
                                             </tr>
                                         @empty
                                             <tr>
                                                 <td colspan="3">No hay lotes pendientes de control de calidad.</td>
                                             </tr>
                                         @endforelse
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>

                     {{-- Resumen de Lotes por Estado de Calidad --}}
                     <div class="col-lg-6">
                         <div class="card card-success"> {{-- Verde para Resumen de Lotes por Calidad --}}
                             <div class="card-header">
                                 <h3 class="card-title">Resumen de Lotes por Calidad (Últimos 30 Días)</h3>
                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                 </div>
                             </div>
                             <div class="card-body">
                                 <canvas id="lotesCalidadPieChart"
                                     style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                             </div>
                         </div>
                     </div>

                     {{-- Controles de Calidad Recientes --}}
                     <div class="col-lg-12">
                         <div class="card card-info"> {{-- Celeste para Controles de Calidad Recientes --}}
                             <div class="card-header">
                                 <h3 class="card-title">Controles de Calidad Recientes</h3>
                                 <div class="card-tools">
                                     <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                         <i class="fas fa-minus"></i>
                                     </button>
                                 </div>
                             </div>
                             <div class="card-body p-0">
                                 <table class="table table-striped">
                                     <thead>
                                         <tr>
                                             <th>Lote</th>
                                             <th>Producto</th>
                                             <th>Resultado</th>
                                             <th>Supervisado Por</th>
                                             <th>Fecha Control</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @forelse ($dashboardData['controlesCalidadRecientes'] ?? [] as $control)
                                             <tr>
                                                 <td>{{ $control->lote->id ?? 'N/A' }}</td>
                                                 <td>{{ $control->lote->productoTerminado->nombre_producto ?? 'N/A' }}</td>
                                                 <td><span
                                                         class="badge badge-{{ $control->resultado == 'Aprobado' ? 'success' : ($control->resultado == 'Rechazado' ? 'danger' : 'warning') }}">{{ $control->resultado }}</span>
                                                 </td>
                                                 <td>{{ $control->supervisadoPor->name ?? 'N/A' }}</td>
                                                 <td>{{ $control->fecha_control->format('d/m/Y') }}</td>
                                             </tr>
                                         @empty
                                             <tr>
                                                 <td colspan="5">No hay controles de calidad recientes.</td>
                                             </tr>
                                         @endforelse
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>
                 @endif

                 @if (($userRole ?? 'sin_rol') == 'sin_rol')
                     <div class="col-12">
                         <div class="alert alert-info" role="alert">
                             {{ $dashboardData['message'] ?? 'Bienvenido. No se ha asignado un rol específico para el dashboard.' }}
                         </div>
                         {{-- Contadores básicos para usuarios sin rol específico --}}
                         <div class="row">
                             <div class="col-lg-3 col-6">
                                 <div class="small-box bg-info">
                                     <div class="inner">
                                         <h3>{{ $dashboardData['usersCount'] ?? 0 }}</h3>
                                         <p>Usuarios Registrados</p>
                                     </div>
                                     <div class="icon">
                                         <i class="fas fa-users"></i>
                                     </div>
                                     <a href="{{ route('users.index') }}" class="small-box-footer">Más información <i
                                             class="fas fa-arrow-circle-right"></i></a>
                                 </div>
                             </div>
                             <div class="col-lg-3 col-6">
                                 <div class="small-box bg-success">
                                     <div class="inner">
                                         <h3>{{ $dashboardData['materiasPrimasCount'] ?? 0 }}</h3>
                                         <p>Materias Primas</p>
                                     </div>
                                     <div class="icon">
                                         <i class="fas fa-boxes"></i>
                                     </div>
                                     <a href="{{ route('materias-primas.index') }}" class="small-box-footer">Más
                                         información <i class="fas fa-arrow-circle-right"></i></a>
                                 </div>
                             </div>
                             <div class="col-lg-3 col-6">
                                 <div class="small-box bg-warning">
                                     <div class="inner">
                                         <h3>{{ $dashboardData['productosTerminadosCount'] ?? 0 }}</h3>
                                         <p>Productos Terminados</p>
                                     </div>
                                     <div class="icon">
                                         <i class="fas fa-cube"></i>
                                     </div>
                                     <a href="{{ route('productos-terminados.index') }}" class="small-box-footer">Más
                                         información <i class="fas fa-arrow-circle-right"></i></a>
                                 </div>
                             </div>
                         </div>
                     </div>
                 @endif
             </div>
             <!-- /.row -->
         </div><!-- /.container-fluid -->
     </section>
     <!-- /.content -->
 @endsection

 @section('scripts')
     <!-- ChartJS -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
     <script>
         $(function() {
             // Script para gráficos (Solo para roles que lo necesiten)

             // Gráfico de Producción Diaria para Gerente General
             @if (($userRole ?? 'sin_rol') == 'Gerente General')
                 var produccionDiariaData = @json($dashboardData['produccionDiaria'] ?? []);
                 var labels = produccionDiariaData.map(item => item.fecha);
                 var data = produccionDiariaData.map(item => item.total_ordenes);

                 var barChartCanvas = $('#produccionDiariaChart').get(0).getContext('2d');
                 new Chart(barChartCanvas, {
                     type: 'bar',
                     data: {
                         labels: labels,
                         datasets: [{
                             label: 'Órdenes Completadas',
                             backgroundColor: 'rgba(60,141,188,0.9)',
                             borderColor: 'rgba(60,141,188,0.8)',
                             pointRadius: false,
                             pointColor: '#3b8bba',
                             pointStrokeColor: 'rgba(60,141,188,1)',
                             pointHighlightFill: '#fff',
                             pointHighlightStroke: 'rgba(60,141,188,1)',
                             data: data
                         }]
                     },
                     options: {
                         responsive: true,
                         maintainAspectRatio: false,
                         datasetFill: false
                     }
                 });
             @endif

             // Gráfico de Pastel de Lotes por Calidad para Supervisor de Calidad
             @if (($userRole ?? 'sin_rol') == 'Supervisor de Calidad')
                 var lotesAprobados = {{ $dashboardData['lotesAprobados'] ?? 0 }};
                 var lotesRechazados = {{ $dashboardData['lotesRechazados'] ?? 0 }};
                 var lotesEnRevision = {{ $dashboardData['lotesEnRevision'] ?? 0 }};
                 var lotesPendientes = {{ $dashboardData['lotesPendientes'] ?? 0 }};

                 var pieChartCanvas = $('#lotesCalidadPieChart').get(0).getContext('2d');
                 new Chart(pieChartCanvas, {
                     type: 'pie',
                     data: {
                         labels: ['Aprobados', 'Rechazados', 'En Revisión', 'Pendientes'],
                         datasets: [{
                             data: [lotesAprobados, lotesRechazados, lotesEnRevision,
                                 lotesPendientes
                             ],
                             backgroundColor: ['#28a745', '#dc3545', '#ffc107',
                                 '#17a2b8'
                             ], // Colores de AdminLTE (verde, rojo, amarillo, info)
                         }]
                     },
                     options: {
                         maintainAspectRatio: false,
                         responsive: true,
                         legend: {
                             position: 'right',
                         },
                         plugins: {
                             tooltip: {
                                 callbacks: {
                                     label: function(context) {
                                         let label = context.label || '';
                                         if (label) {
                                             label += ': ';
                                         }
                                         label += context.raw;
                                         return label;
                                     }
                                 }
                             }
                         }
                     }
                 });
             @endif
         });
     </script>
 @endsection
