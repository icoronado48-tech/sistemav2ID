@extends('layouts\admin')

@section('content')
    <div class="content" style="margin-left: 20px">

        <h3> Actualizacion de Datos del Pacientes </h3>
        <br>
        <div class="row">
            <div class="col-md-11">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">Paciente</h3>
                    </div>

                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('pacientes.update', $paciente->id) }}" role="form"
                            enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Nombre y Apellido</label> <b>*</b>
                                        <input type="text" name="nombre_paciente"
                                            value="{{ $paciente->nombre_paciente }}" class="form-control" required>
                                        @error('nombre_paciente')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Cedula</label> <b>*</b>
                                        <input type="text" name="cedula" value="{{ $paciente->cedula }}"
                                            class="form-control" required>
                                        @error('nombre')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Fecha Nacimiento</label>
                                        <input type="date" name="fecha_nacimiento"
                                            value="{{ $paciente->fecha_nacimiento }}" class="form-control" required>
                                        @error('fecha_nacimiento')
                                            <small style="color:red">Este campo es requerido</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="sexo">Sexo</label>
                                        <select name="sexo" value="{{ $paciente->sexo }}" class="form-control"
                                            id="" required>

                                            @if ($paciente->sexo == 'FEMENINO')
                                                <option value="FEMENINO">FEMENINO</option>
                                                <option value="MASCULINO">MASCULINO</option>
                                            @else
                                                <option value="MASCULINO">MASCULINO</option>
                                                <option value="FEMENINO">FEMENINO</option>
                                            @endif



                                        </select>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Edad</label>
                                        <input type="text" name="edad" value="{{ $paciente->edad }}"
                                            class="form-control" required>
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
                                        <button type="submit" class="btn btn-success">Actualizar</button>
                                    </div>


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
