@extends('layouts\admin')

@section('content')
    <div class="content" style="margin-left: 20px">

        <h3> Listado de Pacientes </h3>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Pacientes Registrados</h3>

                        @if ($message = Session::get('mensaje'))
                            <script>
                                Swal.fire({
                                    title: "¡Buen Trabajo!",
                                    text: "{{ $message }}",
                                    icon: "success"
                                });
                            </script>
                        @endif

                        <div class="card-tools">
                            <a href="{{ url('/pacientes/create') }} " class= "btn btn-primary">
                                <i class="bi bi-person-add">Agregar nuevo Paciente</i></a>
                        </div>
                    </div>

                    <div class="card-body" style"display: block;">

                        <table id="example1" class="table table-bordered table-striped table-sm">
                            <thead>
                                <th>Nro.</th>
                                <th>Nombre y Apellido</th>
                                <th>Cedula</th>
                                <th>Fecha Nacimiento</th>
                                <th>Edad</th>
                                <th>Sexo</th>
                                <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $contador = 0; ?>
                                @foreach ($pacientes as $paciente)
                                    <tr>
                                        <td><?php echo $contador = $contador + 1; ?></td>

                                        <td>{{ $paciente->nombre_paciente }}</td>
                                        <td>{{ $paciente->cedula }}</td>
                                        <td>{{ $paciente->fecha_nacimiento }}</td>
                                        <td>{{ $paciente->edad }}</td>
                                        <td>{{ $paciente->sexo }}</td>
                                        <td style="text-align: center">
                                            <form action="{{ route('pacientes.destroy', $paciente->id) }}" method="POST">
                                                <a class="btn btn-sm btn-primary "
                                                    href="{{ route('pacientes.show', $paciente->id) }}"><i
                                                        class="fa fa-fw fa-eye"></i></a>
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('pacientes.edit', $paciente->id) }}"><i
                                                        class="fa fa-fw fa-edit"></i></a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick= "return confirm ('¿Esta seguro de eliminar el paciente?')"
                                                    class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>


                    </div>

                </div>

            </div>


        </div>

    </div>
    </div>
@endsection
