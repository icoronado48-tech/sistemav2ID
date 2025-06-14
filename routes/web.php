<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\MateriaPrimaController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\AjusteInventarioController;
use App\Http\Controllers\StockAlertaController;
use App\Http\Controllers\RecepcionMateriaPrimaController;
use App\Http\Controllers\OrdenProduccionController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\ProductoTerminadoController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaDespachoController;
use App\Http\Controllers\DetalleVentaDespachoController; // If keeping index/show
use App\Http\Controllers\ControlCalidadController;
use App\Http\Controllers\ReporteProduccionController;
use App\Http\Controllers\DashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rutas de Autenticación (accesibles para usuarios no autenticados)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Rutas que requieren autenticación
Route::middleware(['auth'])->group(function () {
    // Rutas Generales
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/', function () {
        return redirect()->route('dashboard');
    }); // Redirige la raíz a dashboard si ya está autenticado

    // Módulo: Usuarios y Roles
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    // Módulo: Proveedores y Materias Primas
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('materias-primas', MateriaPrimaController::class);
    Route::resource('ordenes-compra', OrdenCompraController::class); // <--- Añade esta línea
    // Si necesitas rutas para addDetalle, updateDetalle, removeDetalle en OrdenCompraController,
    // y no las integraste en el `store`/`update` principal:
    // Route::post('ordenes-compra/{orden_compra}/detalles', [OrdenCompraController::class, 'addDetalle'])->name('ordenes-compra.addDetalle');
    // Route::put('ordenes-compra/{orden_compra}/detalles/{detalle_id}', [OrdenCompraController::class, 'updateDetalle'])->name('ordenes-compra.updateDetalle');
    // Route::delete('ordenes-compra/{orden_compra}/detalles/{detalle_id}', [OrdenCompraController::class, 'removeDetalle'])->name('ordenes-compra.removeDetalle');


    // Módulo: Inventario y Ajustes
    Route::resource('productos-terminados', ProductoTerminadoController::class);
    Route::resource('ajustes-inventario', AjusteInventarioController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('stock-alertas', StockAlertaController::class)->only(['index', 'show', 'edit', 'update']);
    Route::post('stock-alertas/{stock_alerta}/resolve', [StockAlertaController::class, 'markAsResolved'])->name('stock-alertas.resolve');
    Route::resource('recepciones-materia-prima', RecepcionMateriaPrimaController::class)->only(['index', 'create', 'store', 'show']);


    // Módulo: Producción y Lotes
    Route::resource('ordenes-produccion', OrdenProduccionController::class);
    Route::post('ordenes-produccion/{orden_produccion}/status', [OrdenProduccionController::class, 'updateStatus'])->name('ordenes-produccion.updateStatus');
    Route::resource('lotes', LoteController::class)->only(['index', 'show']);
    Route::post('lotes/{lote}/quality-status', [LoteController::class, 'updateQualityStatus'])->name('lotes.updateQualityStatus');
    Route::resource('recetas', RecetaController::class);


    // Módulo: Ventas y Despachos
    Route::resource('clientes', ClienteController::class); // <--- Añade esta línea
    Route::resource('ventas-despachos', VentaDespachoController::class); // <--- Añade esta línea
    // Si mantienes DetalleVentaDespachoController para solo index/show:
    Route::resource('detalle-ventas-despachos', DetalleVentaDespachoController::class)->only(['index', 'show']);


    // Módulo: Calidad y Reportes
    Route::resource('controles-calidad', ControlCalidadController::class); // <--- Añade esta línea
    Route::resource('reportes-produccion', ReporteProduccionController::class)->only(['index', 'create', 'store', 'show']); // <--- Añade esta línea


    // Tablero Principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Aquí irían las rutas para otros módulos futuros, si los hay.
});

// Ruta raíz (si un usuario no está autenticado)
Route::get('/', function () {
    return view('welcome');
});
