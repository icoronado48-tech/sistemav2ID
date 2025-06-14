@extends('layouts.admin') {{-- Asegúrate de que esto extiende tu layout principal --}}

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Detalle del Rol</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active">Detalle</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8"> {{-- Un ancho razonable para mostrar los detalles --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Información del Rol: {{ $role->nombre_rol }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="id_rol" class="col-sm-3 col-form-label">ID del Rol:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">{{ $role->id }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nombre_rol" class="col-sm-3 col-form-label">Nombre del Rol:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">{{ $role->nombre_rol }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="descripcion" class="col-sm-3 col-form-label">Descripción:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">{{ $role->descripcion ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="created_at" class="col-sm-3 col-form-label">Fecha de Creación:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">{{ $role->created_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="updated_at" class="col-sm-3 col-form-label">Última Actualización:</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">{{ $role->updated_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>
                            {{-- Aquí podrías añadir más detalles si el rol tuviera relaciones, por ejemplo, usuarios asociados --}}
                            {{-- <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Usuarios Asociados:</label>
                                <div class="col-sm-9">
                                    @if ($role->users->count() > 0)
                                        <ul>
                                            @foreach ($role->users as $user)
                                                <li>{{ $user->name }} ({{ $user->email }})</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="form-control-static">No hay usuarios asignados a este rol.</p>
                                    @endif
                                </div>
                            </div> --}}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Volver a la Lista</a>
                            @can('update', $role)
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary float-right">Editar
                                    Rol</a>
                            @endcan
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
