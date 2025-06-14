<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User; // Import the User model
use App\Policies\UserPolicy; // Import the UserPolicy
use App\Models\Role; // Import the Role model
use App\Policies\RolePolicy; // Import the RolePolicy
use App\Models\Proveedor; // Importa el modelo Proveedor
use App\Policies\ProveedorPolicy; // Importa la política ProveedorPolicy
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\MateriaPrima; // Importa el modelo MateriaPrima
use App\Policies\MateriaPrimaPolicy; // Importa la política MateriaPrimaPolicy
use App\Models\ProductoTerminado; // Importa el modelo ProductoTerminado
use App\Policies\ProductoTerminadoPolicy; // Importa la política ProductoTerminadoPolicy
use App\Models\AjusteInventario; // Importa el modelo AjusteInventario
use App\Policies\AjusteInventarioPolicy; // Importa la política AjusteInventarioPolicy
use App\Models\StockAlerta; // Importa el modelo StockAlerta
use App\Policies\StockAlertaPolicy; // Importa la política StockAlertaPolicy
use App\Models\RecepcionMateriaPrima;    // <--- Asegúrate de que esta línea esté aquí
use App\Policies\RecepcionMateriaPrimaPolicy; // <--- Asegúrate de que esta línea esté aquí
use App\Models\OrdenProduccion;
use App\Policies\OrdenProduccionPolicy;
use App\Models\Lote; // Importa el modelo Lote
use App\Policies\LotePolicy; // Importa la política LotePolicy
use App\Models\Receta; // Importa el modelo Receta
use App\Policies\RecetaPolicy; // Importa la política RecetaPolicy
use App\Models\OrdenCompra;
use App\Policies\OrdenCompraPolicy;
use App\Models\Cliente;
use App\Policies\ClientePolicy;
use App\Models\VentaDespacho;
use App\Policies\VentaDespachoPolicy;
use App\Models\DetalleVentaDespacho; // For DetalleVentaDespachoPolicy
use App\Policies\DetalleVentaDespachoPolicy; // For DetalleVentaDespachoPolicy
use App\Models\ControlCalidad;
use App\Policies\ControlCalidadPolicy;
use App\Models\ReporteProduccion;
use App\Policies\ReporteProduccionPolicy;





class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Proveedor::class => ProveedorPolicy::class, // <--- Añade esta línea
        MateriaPrima::class => MateriaPrimaPolicy::class, // <--- Añade esta línea
        ProductoTerminado::class => ProductoTerminadoPolicy::class, // <--- Añade esta línea
        AjusteInventario::class => AjusteInventarioPolicy::class, // <--- Añade esta línea
        StockAlerta::class => StockAlertaPolicy::class, // <--- Añade esta línea
        RecepcionMateriaPrima::class => RecepcionMateriaPrimaPolicy::class, // <--- Añade esta línea
        OrdenProduccion::class => OrdenProduccionPolicy::class, // <--- Añade esta línea
        Lote::class => LotePolicy::class, // <--- Añade esta línea
        Receta::class => RecetaPolicy::class, // <--- Añade esta línea
        OrdenCompra::class => OrdenCompraPolicy::class, // <--- Añade esta línea
        Cliente::class => ClientePolicy::class, // <--- Añade esta línea
        VentaDespacho::class => VentaDespachoPolicy::class,      // <--- Añade esta línea
        DetalleVentaDespacho::class => DetalleVentaDespachoPolicy::class, // <--- Añade esta línea
        ControlCalidad::class => ControlCalidadPolicy::class, // <--- Añade esta línea
        ReporteProduccion::class => ReporteProduccionPolicy::class, // <--- Añade esta línea




    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
