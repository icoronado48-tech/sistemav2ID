@extends('layouts\admin')

@section('content')
    <div class="content" style="margin-left: 20px">

        <h3> Listado de Usuarios </h3>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Usuarios Registrados</h3>

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
                            <a href="{{ url('/usuarios/create') }} " class= "btn btn-primary">
                                <i class="bi bi-person-add">Agregar nuevo Usuario</i></a>
                        </div>
                    </div>

                    <div class="card-body" style"display: block;">

                        <table id="example1" class="table table-bordered table-striped table-sm">
                            <thead>
                                <th>Nro.</th>
                                <th>Nombre del usuario</th>
                                <th>Email</th>
                                <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $contador = 0; ?>
                                @foreach ($usuarios as $usuario)
                                    <tr>
                                        <td><?php echo $contador = $contador + 1; ?></td>

                                        <td>{{ $usuario->name }}</td>
                                        <td>{{ $usuario->email }}</td>
                                        <td style="text-align: center">
                                            <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST">
                                                <a class="btn btn-sm btn-primary "
                                                    href="{{ route('usuarios.show', $usuario->id) }}"><i
                                                        class="fa fa-fw fa-eye"></i></a>
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('usuarios.edit', $usuario->id) }}"><i
                                                        class="fa fa-fw fa-edit"></i></a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick= "return confirm ('¿Esta seguro de eliminar el usuario?')"
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


    </div>




    </div>
@endsection
