<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
        integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>email</title>
</head>

<body>
    <div class="container"> <br> <br>
        <div class="row">
            <div class="col align-content-center"><img src="{{ url('dist/img/logo.png') }}" width="450"
                    height="450" class="Logo"></div>
            <div class="col">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <br> <br> <br>
                    <h3 align="center">RESTABLECER CONTRASEÑA</h3> <br><br><br>
                    <div class="container">
                        <i class="fa fa-envelope"></i>
                        <label for=""">Correo Electronico</label><br>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div> <br><br>
                    <div class="row mb-0">
                        <div class="col-md-12 offset-md-4">
                            <button type="submit" class="btn  btn-lg btn-primary">
                                {{ __('Enviar Correo ') }}
                            </button>
                        </div>

                </form>
            </div>
        </div>
    </div>
    </div>
    </div>


</body>

</html>
