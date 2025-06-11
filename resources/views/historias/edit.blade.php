@extends('layouts\admin')

@section('content')
    <div class="content" style="margin-left: 20px">

        <h3> Actualizacion de Historias </h3>
        <br>
        <div class="row">
            <div class="col-md-11">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">Historia</h3>
                    </div>

                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('historias.update', $historia->id) }}" role="form"
                            enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Fecha de Consulta</label> <b>*</b>
                                        <input type="date" name="fecha_consulta" value="{{ $historia->fecha_consulta }}"
                                            class="form-control" required>
                                        @error('fecha_consulta')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Motivo de Consulta</label> <b>*</b>
                                        <input type="text" name="motivo_consulta"
                                            value="{{ $historia->motivo_consulta }}" class="form-control" required>
                                        @error('motivo_consulta')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Antecedentes Personales</label>
                                        <input type="text" name="antecedentes_personales"
                                            value="{{ $historia->antecedentes_personales }}" class="form-control" required>
                                        @error('antecedentes_personales')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Exploracion Fisica</label>
                                        <input type="text" name="exploracion_fisica"
                                            value="{{ $historia->exploracion_fisica }}" class="form-control" required>
                                        @error('exploracion_fisica')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Diagnostico</label>
                                        <input type="text" name="diagnostico" value="{{ $historia->diagnostico }}"
                                            class="form-control" required>
                                        @error('diagnostico')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Tratamiento</label>
                                        <input type="text" name="tratamiento" value="{{ $historia->tratamiento }}"
                                            class="form-control" required>
                                        @error('tratamiento')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Id Paciente</label>
                                        <input type="text" name="paciente_id" value="{{ $historia->paciente_id }}"
                                            class="form-control" required>
                                        @error('paciente_id')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>

                                </div>

                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <a href="{{ url('/historias') }}" class="btn btn-secondary">Cancelar</a>
                                        <button type="submit" class="btn btn-success">Actualizar</button>
                                    </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
