@extends('layouts\admin')


@section('content')
    <div class="content" style="margin-left: 20px">

        <h3> Datos de la historia </h3>
        <br>
        <div class="row">
            <div class="col-md-8">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Historia</h3>
                    </div>
                    <div class="card-body" style"...">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Fecha de Consulta</label>
                                    <input type="date" name="fecha_consulta" value="{{ $historia->fecha_consulta }}"
                                        class="form-control" disabled>
                                    @error('fecha_consulta')
                                        <small style="color:red">Este campo es requerido</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Motivo de Consulta</label>
                                    <input type="text" name="motivo_consulta" value="{{ $historia->motivo_consulta }}"
                                        class="form-control" disabled>
                                    @error('motivo_consulta')
                                        <small style="color:red">Este campo es requerido</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Antecedentes Personales</label>
                                    <input type="text" name="antecedentes_personales"
                                        value="{{ $historia->antecedentes_personales }}" class="form-control" disabled>
                                    @error('antecedentes_personales')
                                        <small style="color:red">Este campo es requerido</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Exploracion Fisica</label>
                                    <input type="text" name="exploracion_fisica"
                                        value="{{ $historia->exploracion_fisica }}" class="form-control" disabled>
                                    @error('exploracion_fisica')
                                        <small style="color:red">Este campo es requerido</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Diagnostico</label>
                                    <input type="text" name="diagnostico" value="{{ $historia->diagnostico }}"
                                        class="form-control" disabled>
                                    @error('diagnostico')
                                        <small style="color:red">Este campo es requerido</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Tratamiento</label>
                                    <input type="text" name="tratamiento" value="{{ $historia->tratamiento }}"
                                        class="form-control" disabled>
                                    @error('tratamiento')
                                        <small style="color:red">Este campo es requerido</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Id Paciente</label>
                                    <input type="text" name="paciente_id" value="{{ $historia->paciente_id }}"
                                        class="form-control" disabled>
                                    @error('paciente_id')
                                        <small style="color:red">Este campo es requerido</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <a href="{{ url('/historias') }}" class="btn btn-info">Regresar</a>
                        </div>
                    </div>
                </div>
            @endsection
