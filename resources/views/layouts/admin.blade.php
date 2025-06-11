<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Medical Center</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- Datatables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- JQuery -->
    <script>
        src = {{ asset('/plugins/jquery/jquery.js') }}
    </script>
    <!-- SwettAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper" style="background-color:white">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar" style= "color: rgb(0, 89, 129)">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"
                            style="color:rgb(0, 89, 129)"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('/') }}" class="nav-link"><b style="color: rgb(0, 89, 129)"> Sistema de Gestion
                            de Produccion
                        </b></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" style= "color:rgb(40, 43,51)" data-widget="fullscreen" href="#"
                        role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style= "color:rgb(40, 43,51)" data-widget="control-sidebar" data-slide="true"
                        href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-ligth elevation-4">

            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="brand-link">
                <img src="{{ url('dist/img/logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3"
                    style="opacity: 10">
                <span class="brand-text font-weight-light" style="color: rgb(0, 89, 129);"><strong>
                        DELIGESTION</strong></span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">

                    <div class="info">
                        <a href="#" class="d-block" style="color: rgb(40, 43, 51);"> {{ Auth::user()->name }}
                        </a>
                    </div>
                </div>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item menu">
                            <a href="#" class="nav-link active" style="background-color: rgb(2, 74, 108)">
                                <i class="nav-icon fas">
                                    <i class="bi bi-person-circle"></i></i>
                                <p>
                                    Usuarios
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ url('usuarios') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Usuarios</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('usuarios/create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i></i>
                                        <p>Nuevo Usuario</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item menu">
                            <a href="#" class="nav-link active" style="background-color: rgb(2, 74, 108)">
                                <i class="nav-icon fas">
                                    <i class="bi bi-basket"></i></i>
                                <p>
                                    Inventario
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ url('medicos') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Ingredientes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('medicos/create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i></i>
                                        <p>Nuevo Ingrediente</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item menu">
                            <a href="#" class="nav-link active" style="background-color: rgb(2, 74, 108)">
                                <i class="nav-icon fas">
                                    <i class="bi bi-people-fill"></i></i>
                                <p>
                                    Produccion
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('pacientes') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Pacientes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('pacientes/create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i></i>
                                        <p>Nuevo paciente</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item menu">
                            <a href="#" class="nav-link active" style="background-color: rgb(2, 74, 108)">
                                <i class="nav-icon fas">
                                    <i class="bi bi-journal-medical"></i></i>
                                <p>
                                    Recetas
                                    <i class="bi bi-fork-knife"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('historias') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Recetas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('historias/create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i></i>
                                        <p>Nueva Receta</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();"
                                style="background-color: #a5161d"> <i class="nav-icon fas"><i
                                        class="bi bi-door-closed" style=color:white></i></i>
                                <p style="color:white">Salir</p>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="background-color: white">
            <br>
            @yield('content')
            <div class="content" style="background-color: white"></div>

            <!-- /.col-md-6 -->
        </div>

    </div>
    <!-- /.content -->

    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar" style="background-color:aliceblue">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer" style="background-color: white">
        <!-- To the right -->

        <!-- Default to the left -->
        <strong>Copyright &copy; 2025 <a href="https://deligestion.com">Deligestión</a>.</strong> Todos los
        derechos reservados.
    </footer>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- JQuery -->
    <script src="{{ 'plugins/jquery/jquery.min.js' }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>


    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }} "></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        });
    </script>



</body>

</html>
