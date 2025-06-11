<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
        integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>

    <div class="container"> <br> <br>
        <div class="row">
            <div class="col align-content-center"><img src="{{ url('dist/img/logo.png') }}" width="450"
                    height="450" class="Logo"></div>

            <div class="col">

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <br> <br> <br>
                    <h3 align="center">REGISTRO DE USUARIO</h3> <br>

                    <div class="container">
                        <i class="fa fa-users"></i>
                        <label for="">Nombre y Apellido</label>
                        <input id="name" type="name" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
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
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            required autocomplete="new-password">
                    </div>

                    <br>

                    <div class="container">
                        <button type="submit" class="btn btn-link btn-lg btn-primary btn-block" style="color:white"
                            value="Registrese"> {{ __('Registrese') }}


                    </div>



            </div>



        </div>






        <br>




    </div>

    </form>





</body>

</html>
