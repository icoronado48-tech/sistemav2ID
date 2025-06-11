@extends('layouts\admin')


@section('content')
    <div class="content" style="margin-left: 20px">

        <h3> Datos del Paciente Registrado </h3>
        <br>
        <div class="row">
            <div class="col-md-8">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Ficha del Paciente</h3>
                    </div>
                    <div class="card-body" style"...">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Nombre y Apellido</label>
                                    <input type="text" name="nombre_paciente" value="{{ $paciente->nombre_paciente }}"
                                        class="form-control" disabled>
                                    @error('nombre_paciente')
                                        <small style="color:red">Este campo es requerido</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Cedula</label>
                                    <input type="text" name="cedula" value="{{ $paciente->cedula }}"
                                        class="form-control" disabled>
                                    @error('cedula')
                                        <small style="color:red">Este campo es requerido</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Fecha Nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" value="{{ $paciente->fecha_nacimiento }}"
                                        class="form-control" disabled>
                                    @error('fecha_nacimiento')
                                        <small style="color:red">Este campo es requerido</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sexo">Sexo</label>
                                    <select name="sexo" value="{{ $paciente->sexo }}" class="form-control" id=""
                                        disabled>
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
                                    <input type="text" name="edad"value="{{ $paciente->edad }}" class="form-control"
                                        disabled>
                                    @error('edad')
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
                            <a href="{{ url('/pacientes') }}" class="btn btn-info">Regresar</a>
                        </div>
                    </div>
                </div>
            @endsection
