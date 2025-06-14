<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\ProductoTerminado;
use App\Models\MateriaPrima;
use App\Models\RecetaIngrediente; // Asegúrate de que esto esté importado
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Receta\StoreRecetaRequest;
use App\Http\Requests\Receta\UpdateRecetaRequest;

class RecetaController extends Controller
{
    /**
     * Constructor para aplicar las políticas de autorización a los métodos del recurso.
     */
    public function __construct()
    {
        // Aplica la política de Receta a todos los métodos del recurso.
        // 'receta' es el nombre del parámetro de ruta que contiene la instancia de Receta.
        $this->authorizeResource(Receta::class, 'receta');
    }

    /**
     * Muestra una lista de todas las recetas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Carga las recetas con sus productos terminados asociados y las pagina.
        // Se usa 'with' para evitar el problema de N+1 consultas.
        $recetas = Receta::with('productoTerminado')->paginate(10);
        // Retorna la vista 'recetas.list', pasando las recetas paginadas.
        return view('recetas.list', compact('recetas'));
    }

    /**
     * Muestra el formulario para crear una nueva receta.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Obtiene todos los productos terminados para el dropdown de selección.
        $productosTerminados = ProductoTerminado::all();
        // Obtiene todas las materias primas para la selección de ingredientes.
        $materiasPrimas = MateriaPrima::all();
        // Retorna la vista 'recetas.create', pasando los datos necesarios.
        return view('recetas.create', compact('productosTerminados', 'materiasPrimas'));
    }

    /**
     * Almacena una nueva receta y sus ingredientes asociados en la base de datos.
     * Utiliza StoreRecetaRequest para la validación.
     *
     * @param  \App\Http\Requests\Receta\StoreRecetaRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRecetaRequest $request)
    {
        try {
            // Iniciar una transacción de base de datos para asegurar la atomicidad.
            DB::beginTransaction();

            // Crea la nueva receta con los datos validados.
            $receta = Receta::create($request->validated());

            // Si hay ingredientes en la solicitud, los asocia a la receta.
            // La validación en StoreRecetaRequest asegura que 'ingredientes' es un array válido.
            if ($request->has('ingredientes') && is_array($request->ingredientes)) {
                $recetaIngredientes = [];
                foreach ($request->ingredientes as $ingrediente) {
                    $recetaIngredientes[] = new RecetaIngrediente([
                        'materia_prima_id' => $ingrediente['materia_prima_id'],
                        'cantidad_necesaria' => $ingrediente['cantidad_necesaria'],
                    ]);
                }
                // Guarda todos los ingredientes asociados a la receta.
                $receta->ingredientes()->saveMany($recetaIngredientes);
            }

            // Confirma la transacción si todo fue exitoso.
            DB::commit();

            // Redirige al índice de recetas con un mensaje de éxito.
            return redirect()->route('recetas.index')->with('success', 'Receta creada exitosamente.');
        } catch (\Exception $e) {
            // Si ocurre algún error, revierte la transacción.
            DB::rollBack();
            // Registra el error para depuración.
            Log::error("Error al crear la receta: " . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            // Redirige de vuelta con un mensaje de error.
            return back()->withInput()->with('error', 'Hubo un error al crear la receta: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de una receta específica.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\View\View
     */
    public function show(Receta $receta)
    {
        // Carga la receta con sus relaciones de producto terminado e ingredientes.
        // Esto es útil si los detalles de los ingredientes (ej: nombre de la materia prima) se muestran.
        $receta->load('productoTerminado', 'ingredientes.materiaPrima');
        // Retorna la vista 'recetas.show', pasando la instancia de la receta.
        return view('recetas.show', compact('receta'));
    }

    /**
     * Muestra el formulario para editar una receta existente.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\View\View
     */
    public function edit(Receta $receta)
    {
        // Carga la receta con sus relaciones para precargar el formulario.
        $receta->load('ingredientes');
        $productosTerminados = ProductoTerminado::all();
        $materiasPrimas = MateriaPrima::all();
        // Retorna la vista 'recetas.edit', pasando la receta y otros datos necesarios.
        return view('recetas.edit', compact('receta', 'productosTerminados', 'materiasPrimas'));
    }

    /**
     * Actualiza una receta y sus ingredientes asociados en la base de datos.
     * Utiliza UpdateRecetaRequest para la validación.
     *
     * @param  \App\Http\Requests\Receta\UpdateRecetaRequest  $request
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRecetaRequest $request, Receta $receta)
    {
        try {
            // Iniciar una transacción de base de datos.
            DB::beginTransaction();

            // Actualiza los datos de la receta con los datos validados.
            $receta->update($request->validated());

            // Sincronizar ingredientes:
            // 1. Elimina todos los ingredientes existentes para esta receta.
            $receta->ingredientes()->delete();

            // 2. Vuelve a crear los ingredientes basándose en los datos del request.
            if ($request->has('ingredientes') && is_array($request->ingredientes)) {
                $recetaIngredientes = [];
                foreach ($request->ingredientes as $ingrediente) {
                    $recetaIngredientes[] = new RecetaIngrediente([
                        'materia_prima_id' => $ingrediente['materia_prima_id'],
                        'cantidad_necesaria' => $ingrediente['cantidad_necesaria'],
                    ]);
                }
                $receta->ingredientes()->saveMany($recetaIngredientes);
            }

            // Confirma la transacción.
            DB::commit();

            // Redirige con un mensaje de éxito.
            return redirect()->route('recetas.index')->with('success', 'Receta actualizada exitosamente.');
        } catch (\Exception $e) {
            // Si hay un error, revierte la transacción.
            DB::rollBack();
            // Registra el error.
            Log::error("Error al actualizar la receta: " . $e->getMessage(), ['exception' => $e, 'receta_id' => $receta->id, 'request' => $request->all()]);
            // Redirige con un mensaje de error.
            return back()->withInput()->with('error', 'Hubo un error al actualizar la receta: ' . $e->getMessage());
        }
    }

    /**
     * Elimina una receta de la base de datos.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Receta $receta)
    {
        try {
            // Aquí puedes añadir una lógica para verificar dependencias si no está manejado por
            // restricciones de clave foránea en la base de datos o si quieres un mensaje más amigable.
            // Por ejemplo, si la receta está asociada a órdenes de producción activas.
            // if ($receta->ordenesProduccion()->exists()) {
            //     return back()->with('error', 'No se puede eliminar la receta porque está asociada a órdenes de producción.');
            // }

            // Si RecetaIngrediente tiene ON DELETE CASCADE desde Receta, esto los eliminará implícitamente.
            // De lo contrario, los eliminarías manualmente antes de eliminar la receta: $receta->ingredientes()->delete();

            // Inicia una transacción para la eliminación.
            DB::beginTransaction();
            $receta->delete(); // Esto debería eliminar en cascada RecetaIngrediente si está configurado en la migración.
            // Confirma la transacción.
            DB::commit();
            // Redirige con un mensaje de éxito.
            return redirect()->route('recetas.index')->with('success', 'Receta eliminada exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Manejo específico para errores de clave foránea si la receta está en uso.
            Log::error("Error al eliminar receta por restricción de clave foránea: " . $e->getMessage(), ['exception' => $e, 'receta_id' => $receta->id]);
            return back()->with('error', 'No se puede eliminar la receta. Verifique si aún tiene registros dependientes inesperados.');
        } catch (\Exception $e) {
            // Revierte la transacción en caso de cualquier otro error.
            DB::rollBack();
            // Registra el error general.
            Log::error("Error general al eliminar receta: " . $e->getMessage(), ['exception' => $e, 'receta_id' => $receta->id]);
            // Redirige con un mensaje de error general.
            return back()->with('error', 'Hubo un error inesperado al eliminar la receta. Por favor, inténtelo de nuevo.');
        }
    }
}
