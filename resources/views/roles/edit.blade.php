@extends('layouts.admin') {{-- Asegúrate de que esto extiende tu layout principal --}}

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Editar Rol</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6"> {{-- Usar el mismo ancho que el formulario de creación --}}
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Formulario de Edición de Rol</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('roles.update', $role->id) }}" method="POST">
                            @csrf
                            @method('PUT') {{-- Importante para indicar que es una solicitud PUT para la actualización --}}
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nombre_rol">Nombre del Rol</label>
                                    <input type="text" class="form-control @error('nombre_rol') is-invalid @enderror"
                                        id="nombre_rol" name="nombre_rol" value="{{ old('nombre_rol', $role->nombre_rol) }}"
                                        placeholder="Ingrese el nombre del rol" required>
                                    @error('nombre_rol')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion"
                                        rows="3" placeholder="Ingrese una descripción para el rol">{{ old('descripcion', $role->descripcion) }}</textarea>
                                    @error('descripcion')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Actualizar Rol</button>
                                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- No se requieren scripts adicionales para este formulario simple --}}
