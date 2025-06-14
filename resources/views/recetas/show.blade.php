@extends('layouts.admin') {{-- Asegúrate de que esto extiende tu layout principal --}}

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Detalles de Receta</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('recetas.index') }}">Recetas</a></li>
                        <li class="breadcrumb-item active">Detalles</li>
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
                    <h3 class="card-title">Información de la Receta</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="form-group">
                        <strong>Nombre de la Receta:</strong>
                        <p>{{ $receta->nombre_receta }}</p>
                    </div>
                    <div class="form-group">
                        <strong>Producto Terminado:</strong>
                        <p>{{ $receta->productoTerminado->nombre_producto_terminado ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <strong>Descripción:</strong>
                        <p>{{ $receta->descripcion ?? 'Sin descripción' }}</p>
                    </div>

                    <hr>
                    <h4>Ingredientes</h4>
                    @if ($receta->ingredientes->isNotEmpty())
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Materia Prima</th>
                                    <th>Cantidad Necesaria</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receta->ingredientes as $ingrediente)
                                    <tr>
                                        <td>{{ $ingrediente->materiaPrima->nombre_materia_prima ?? 'N/A' }}</td>
                                        <td>{{ $ingrediente->cantidad_necesaria }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No hay ingredientes registrados para esta receta.</p>
                    @endif
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <a href="{{ route('recetas.edit', $receta->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i>
                        Editar</a>
                    <a href="{{ route('recetas.index') }}" class="btn btn-secondary">Volver al Listado</a>
                </div>
            </div>
            <!-- /.card -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
