@extends('layouts.admin') {{-- Asegúrate de que esto extiende tu layout principal --}}

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Editar Receta</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('recetas.index') }}">Recetas</a></li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Formulario de Edición de Receta</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {{-- La acción del formulario apunta a la ruta 'update' y usa el método PUT --}}
                <form action="{{ route('recetas.update', $receta->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Importante para las solicitudes de actualización --}}

                    <div class="card-body">
                        <div class="form-group">
                            <label for="producto_terminado_id">Producto Terminado <span class="text-danger">*</span></label>
                            <select class="form-control @error('producto_terminado_id') is-invalid @enderror" id="producto_terminado_id" name="producto_terminado_id" required>
                                <option value="">Seleccione un producto terminado</option>
                                @foreach($productosTerminados as $producto)
                                    <option value="{{ $producto->id }}" {{ old('producto_terminado_id', $receta->producto_terminado_id) == $producto->id ? 'selected' : '' }}>
                                        {{ $producto->nombre_producto_terminado }}
                                    </option>
                                @endforeach
                            </select>
                            @error('producto_terminado_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nombre_receta">Nombre de la Receta <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre_receta') is-invalid @enderror" id="nombre_receta" name="nombre_receta" value="{{ old('nombre_receta', $receta->nombre_receta) }}" placeholder="Ej: Receta de Pastel de Chocolate" required>
                            @error('nombre_receta')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3" placeholder="Descripción detallada de la receta">{{ old('descripcion', $receta->descripcion) }}</textarea>
                            @error('descripcion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <hr>
                        <h4>Ingredientes <span class="text-danger">*</span></h4>
                        <div id="ingredientes_container">
                            {{-- Los ingredientes existentes se cargan aquí --}}
                            @forelse(old('ingredientes', $receta->ingredientes) as $index => $ingrediente)
                                {{-- Aquí aseguramos que $ingrediente es un objeto o array con la estructura esperada --}}
                                @php
                                    $selectedMateriaPrimaId = is_object($ingrediente) ? $ingrediente->materia_prima_id : $ingrediente['materia_prima_id'];
                                    $cantidadNecesaria = is_object($ingrediente) ? $ingrediente->cantidad_necesaria : $ingrediente['cantidad_necesaria'];
                                @endphp
                                @include('recetas.partials.ingrediente_row', [
                                    'index' => $index,
                                    'materiasPrimas' => $materiasPrimas,
                                    'selectedMateriaPrimaId' => $selectedMateriaPrimaId,
                                    'cantidadNecesaria' => $cantidadNecesaria
                                ])
                            @empty
                                {{-- Fila inicial si no hay ingredientes o old input --}}
                                @include('recetas.partials.ingrediente_row', [
                                    'index' => 0,
                                    'materiasPrimas' => $materiasPrimas,
                                    'selectedMateriaPrimaId' => null,
                                    'cantidadNecesaria' => null
                                ])
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-info btn-sm mt-3" id="add_ingrediente_btn"><i class="fas fa-plus"></i> Añadir Ingrediente</button>

                        @error('ingredientes')
                            <div class="alert alert-danger mt-3" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Actualizar Receta</button>
                        <a href="{{ route('recetas.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    {{-- Script para manejar la adición/eliminación dinámica de ingredientes --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa el índice para nuevas filas. Si ya hay ingredientes, comienza después del último.
            // old('ingredientes') prevalece sobre $receta->ingredientes para mantener los datos en caso de error de validación.
            let ingredienteIndex = {{ old('ingredientes') ? count(old('ingredientes')) : count($receta->ingredientes) }};

            // Prepara las materias primas como un objeto JSON para usar en JavaScript
            const materiasPrimas = @json($materiasPrimas->pluck('nombre_materia_prima', 'id'));

            const addButton = document.getElementById('add_ingrediente_btn');
            const container = document.getElementById('ingredientes_container');

            if (addButton) {
                // Función para añadir una nueva fila de ingrediente
                addButton.addEventListener('click', function() {
                    let optionsHtml = '<option value="">Seleccione una materia prima</option>';
                    for (const id in materiasPrimas) {
                        optionsHtml += `<option value="${id}">${materiasPrimas[id]}</option>`;
                    }

                    const newRowHtml = `
                        <div class="row ingrediente-row border border-info rounded p-2 mb-2">
                            <div class="form-group col-md-5">
                                <label for="ingredientes_${ingredienteIndex}_materia_prima_id">Materia Prima <span class="text-danger">*</span></label>
                                <select class="form-control" id="ingredientes_${ingredienteIndex}_materia_prima_id" name="ingredientes[${ingredienteIndex}][materia_prima_id]" required>
                                    ${optionsHtml}
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="ingredientes_${ingredienteIndex}_cantidad_necesaria">Cantidad Necesaria <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="ingredientes_${ingredienteIndex}_cantidad_necesaria" name="ingredientes[${ingredienteIndex}][cantidad_necesaria]" min="0.01" step="0.01" value="" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end mb-3">
                                <button type="button" class="btn btn-danger btn-sm remove-ingrediente-btn"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', newRowHtml);
                    ingredienteIndex++;
                });
            }

            // Función para eliminar una fila de ingrediente
            if (container) {
                container.addEventListener('click', function(event) {
                    if (event.target.classList.contains('remove-ingrediente-btn') || event.target.closest('.remove-ingrediente-btn')) {
                        const rowToRemove = event.target.closest('.ingrediente-row');
                        if (rowToRemove) {
                            rowToRemove.remove();
                        }
                    }
                });
            }
        });
    </script>
@endsection
