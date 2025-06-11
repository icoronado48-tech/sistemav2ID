@extends('layouts\admin')

@section('content')
    <div class="content" style="margin-left: 20px">

        <h3> Listado general de historias </h3>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Historias Registradas</h3>

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
                            <a href="{{ url('/historias/create') }} " class= "btn btn-primary">
                                <i class="bi bi-person-add">Agregar nueva historia</i></a>
                        </div>
                    </div>

                    <div class="card-body" style"display: block;">

                        <table id="example1" class="table table-bordered table-striped table-sm">
                            <thead>
                                <th>Nro.</th>
                                {{-- <th>Nombre Paciente</th> --}}
                                {{-- <th>Cedula</th> --}}
                                <th>Fecha Consulta</th>
                                <th>Motivo Consulta</th>
                                <th>Diagnostico</th>
                                <th>Tratamiento</th>
                                <th>Paciente Id</th>
                                <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $contador = 0; ?>
                                @foreach ($historias as $historia)
                                    <tr>
                                        <td><?php echo $contador = $contador + 1; ?></td>

                                        {{-- <td>{{ $historia->nombre_paciente }}</td> --}}
                                        {{-- <td>{{ $historia->cedula }}</td> --}}
                                        <td>{{ $historia->fecha_consulta }}</td>
                                        <td>{{ $historia->motivo_consulta }}</td>
                                        <td>{{ $historia->diagnostico }}</td>
                                        <td>{{ $historia->tratamiento }}</td>
                                        <td>{{ $historia->paciente_id }}</td>
                                        <td style="text-align: center">
                                            <form action="{{ route('historias.destroy', $historia->id) }}" method="POST">
                                                <a class="btn btn-sm btn-primary "
                                                    href="{{ route('historias.show', $historia->id) }}"><i
                                                        class="fa fa-fw fa-eye"></i></a>
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('historias.edit', $historia->id) }}"><i
                                                        class="fa fa-fw fa-edit"></i></a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick= "return confirm ('¿Esta seguro de eliminar la historia?')"
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
