@extends('layouts\admin')


@section('content')
    <div class="content" style="margin-left: 20px">

        <h3> Ingreso de Pacientes </h3>
        <br>
        <div class="row">
            <div class="col-md-11">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Nuevo Paciente</h3>
                    </div>

                    <div class="card-body" style"...">

                        <form action="{{ url('/pacientes') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Nombre y Apellido</label> <b>*</b>
                                        <input type="text" name="nombre_paciente" class="form-control" required>
                                        @error('nombre_paciente')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Cedula</label> <b>*</b>
                                        <input type="text" name="cedula" class="form-control" required>
                                        @error('cedula')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Fecha Nacimiento</label> <b>*</b>
                                        <input type="date" name="fecha_nacimiento" class="form-control" required>
                                        @error('fecha_nacimiento')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="sexo">Sexo</label>
                                        <select name="sexo" class="form-control" id="">
                                            <option value="Femenino">Femenino</option>
                                            <option value="Masculino">Masculino</option>
                                        </select>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Edad</label><b>*</b>
                                        <input type="text" name="edad" class="form-control" required>
                                        @error('edad')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>

                                </div>

                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <a href="{{ url('/') }}" class="btn btn-secondary">Cancelar</a>
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>


                        </form>

                    </div>
                </div>


            </div>
        @endsection
