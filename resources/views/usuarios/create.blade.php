@extends('layouts\admin')

@section('content')
    <div class="content" style="margin-left: 20px">

        <h3> Creacion de nuevo usuarios </h3>
        <br>
        <div class="row">
            <div class="col-md-8">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Llene los datos de forma correcta</h3>

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
                                <input id="name" type="name"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" required autocomplete="name" autofocus>
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
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <br>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="container">
                                <i class="fa fa-key"></i>
                                <label for="">Contraseña</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="container">
                                <i class="fa fa-key"></i>
                                <label for="password-confirm">Confirme Contraseña</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                            <br>
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <a href="{{ url('/') }}" class="btn btn-lg btn-secondary">Cancelar</a>
                                            <button type="submit" class="btn btn-link btn-lg btn-primary"
                                                style="color:white" value="Registrese"> {{ __('Registrar') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
