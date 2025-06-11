@extends('layouts\admin')

@section('content')
    <div class="content" style="margin-left: 20px">

        <h1>Pagina principal</h1><br>

    </div>
    <section class="content" style="margin-left: 10px">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-3">

                    <div class="small-box bg" style="background-color: #0bb817" style="height: 160px">
                        <div class="inner">
                            <?php $contador_de_usuario = 0; ?>
                            @foreach ($usuarios as $usuario)
                                <?php $contador_de_usuario = $contador_de_usuario + 1; ?>
                            @endforeach

                            <h3 style= 'color:whitesmoke'> <?= $contador_de_usuario ?> </h3>
                            <p style= 'color:whitesmoke'>Usuarios</p>
                        </div>
                        <div class="icon">
                            <i class="bi bi-person-circle" style= "color:whitesmoke"></i>
                        </div>
                        <a href="{{ 'usuarios' }}   " class="small-box-footer" style= "margin-top: 20px">Mas
                            Informacion <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3">

                    <div class="small-box bg" style="background-color: rgb(0,89, 129)" style="height: 160px">
                        {{-- <div class="inner">
                            <?php $contador_de_paciente = 0; ?>
                            @foreach ($pacientes as $paciente)
                                <?php $contador_de_paciente = $contador_de_paciente + 1; ?>
                            @endforeach

                            <h3 style= 'color:whitesmoke'> <?= $contador_de_paciente ?> </h3>
                            <p style= 'color:whitesmoke'>Inventario</p>
                        </div>
                        <div class="icon">
                            <i class="bi bi-basket" style= "color:whitesmoke"></i>
                        </div>
                        <a href="{{ 'pacientes' }}   " class="small-box-footer" style= "margin-top: 20px">Mas
                            Informacion <i class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
                <div class="col-lg-3">

                    <div class="small-box bg" style="background-color: rgb(0, 173, 198)" style="height: 160px">
                        {{-- <div class="inner">
                            <?php $contador_de_paciente = 0; ?>
                            @foreach ($pacientes as $paciente)
                                <?php $contador_de_paciente = $contador_de_paciente + 1; ?>
                            @endforeach

                            <h3 style= 'color:whitesmoke'> <?= $contador_de_paciente ?> </h3>
                            <p style= 'color:whitesmoke'>Produccion</p>
                        </div>
                        <div class="icon">
                            <i class="bi bi-people-fill" style= "color:whitesmoke"></i>
                        </div>
                        <a href="{{ 'pacientes' }}   " class="small-box-footer" style= "margin-top: 20px">Mas
                            Informacion <i class="fas fa-arrow-circle-right"></i></a>
                    </div> --}}
                    </div>
                    <div class="col-lg-3">

                        <div class="small-box bg" style="background-color: rgb(202, 95, 29)" style="height: 160px">
                            {{-- <div class="inner">
                            <?php $contador_de_historia = 0; ?>
                            @foreach ($historias as $historia)
                                <?php $contador_de_historia = $contador_de_historia + 1; ?>
                            @endforeach

                            <h3 style= 'color:whitesmoke'> <?= $contador_de_historia ?> </h3>
                            <p style= 'color:whitesmoke'>Recetas</p>
                        </div>
                        <div class="icon">
                            <i class="bi bi-journal-medical" style= "color:whitesmoke"></i>
                        </div>
                        <a href="{{ 'historias' }}   " class="small-box-footer" style= "margin-top: 20px">Mas
                            Informacion <i class="fas fa-arrow-circle-right"></i></a>
                    </div> --}}
                        </div>




                    </div>
                @endsection
