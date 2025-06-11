@extends('layouts\admin')


@section('content')
    <div class="content" style="margin-left: 20px">

        <h3> Ingreso de Historias </h3>
        <br>
        <div class="row">
            <div class="col-md-11">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Nueva Historia</h3>
                    </div>

                    <div class="card-body" style"...">

                        <form action="{{ url('/historias') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Id Paciente</label> <b>*</b>
                                        <input type="text" name="paciente_id" class="form-control" required>
                                        @error('paciente_id')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">FECHA DE CONSULTA</label> <b>*</b>
                                        <input type="date" name="fecha_consulta" class="form-control" required>
                                        @error('fecha_consulta')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Motivo Consulta</label> <b>*</b>
                                        <input type="text" name="motivo_consulta" class="form-control" required>
                                        @error('motivo_consulta')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Antecedentes Personales</label> <b>*</b>
                                        <input type="text" name="antecedentes_personales" class="form-control" required>
                                        @error('antecedentes_personales')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Exploracion Fisica</label> <b>*</b>
                                        <input type="text" name="exploracion_fisica" class="form-control" required>
                                        @error('exploracion_fisica')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Diagnostico</label><b>*</b>
                                        <input type="text" name="diagnostico" class="form-control" required>
                                        @error('diagnostico')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Tratamiento</label><b>*</b>
                                        <input type="text" name="tratamiento" class="form-control" required>
                                        @error('tratamiento')
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
