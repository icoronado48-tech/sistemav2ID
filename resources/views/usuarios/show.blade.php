@extends('layouts\admin')

@section('content')
    <div class="content" style="margin-left: 20px">

        <h3> Datos del usuarios </h3>
        <br>
        <div class="row">
            <div class="col-md-8">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Información del usuario
                            @if ($message = Session::get('mensaje'))
                                <script>
                                    Swal.fire({
                                        title: "¡Buen Trabajo!",
                                        text: "{{ $message }}",
                                        icon: "success"
                                    });
                                </script>
                            @endif
                    </div>
                    <div class="col-md-10">
                        <form method="POST" action="{{ url('/usuarios') }}">
                            @csrf
                            <br>
                            <div class="container">
                                <i class="fa fa-users"></i>
                                <label for="">Nombre y Apellido</label>
                                <input id="name" type="name" class="form-control" value="{{ $usuario->name }}"
                                    disabled>
                                <br>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="container">
                                <i class="fa fa-envelope"></i>
                                <label for="">Correo Electronico</label>
                                <input id="email" type="email" class="form-control" value="{{ $usuario->email }}"
                                    disabled>
                                <br>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group" style="margin-left: 10px">
                                        <a href="{{ url('/usuarios') }}" class="btn btn-md btn-info">Regresar</a>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
