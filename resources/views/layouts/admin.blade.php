<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Gestión de Producción - Deligestión</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme icons (Bootstrap Icons) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Theme style (AdminLTE) -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- Datatables CSS -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- SweetAlert2 CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper" style="background-color:white">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"
                            style="color:rgb(40, 43,51)"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('/') }}" class="nav-link"><b style="color: rgb(40, 43,51)"> Sistema de Gestión
                            de Producción
                        </b></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" style="color:rgb(40, 43,51)" data-widget="fullscreen" href="#"
                        role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="color:rgb(40, 50, 51)" data-widget="control-sidebar" data-slide="true"
                        href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">

            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="brand-link">
                <img src="{{ asset('dist/img/logo.png') }}" alt="Deligestión Logo"
                    class="brand-image img-circle elevation-3" style="opacity: 10">
                <span class="brand-text font-weight-light" style="color: rgb(0, 89, 129);"><strong>
                        DELIGESTIÓN</strong></span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    {{-- <div class="image">
                           <img src="https://placehold.co/160x160/cccccc/ffffff?text=U" class="img-circle elevation-2"
                               alt="User Image">
                       </div> --}}
                    <div class="info">
                        {{-- Muestra el nombre del usuario autenticado --}}
                        <a href="#" class="d-block" style="color: rgb(0, 0, 0);">
                            {{ Auth::user()->name ?? 'Invitado' }}
                        </a>
                    </div>
                </div>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Dashboard Link -->
                        <li class="nav-item {{ Request::is('roles*') ? 'menu-open' : '' }}">
                            <a href="{{ route('dashboard') }}" {{-- Usar route() helper --}}
                                class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}"
                                style="background-color: rgb(2, 74, 108); color: white">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        {{-- Módulo de Gestión de Roles --}}
                        <li class="nav-item {{ Request::is('roles*') ? 'menu-open' : '' }}"> {{-- Cambiado a 'users' --}}
                            <a href="#" class="nav-link {{ Request::is('roles*') ? 'active' : '' }}"
                                {{-- Cambiado a 'users' --}} style="background-color: rgb(2, 74, 108); color: white">
                                <i class="nav-icon fas">
                                    <i class="bi bi-person-circle"></i></i>
                                <p>
                                    Roles
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('roles') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Roles</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('roles.create') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('roles/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Nuevo rol</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Módulo de Usuarios -->
                        <li class="nav-item {{ Request::is('users*') ? 'menu-open' : '' }}"> {{-- Cambiado a 'users' --}}
                            <a href="#" class="nav-link {{ Request::is('users*') ? 'active' : '' }}"
                                {{-- Cambiado a 'users' --}} style="background-color: rgb(2, 74, 108); color: white">
                                <i class="nav-icon fas">
                                    <i class="bi bi-person-circle"></i></i>
                                <p>
                                    Usuarios
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('users') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Usuarios</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('users.create') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('users.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Nuevo Usuario</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Módulo de Inventario (Materia Prima) -->
                        <li class="nav-item {{ Request::is('materias-primas*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('materias-primas*') ? 'active' : '' }}"
                                style="background-color: rgb(2, 74, 108); color: white">
                                <i class="nav-icon fas">
                                    <i class="bi bi-box-seam"></i></i> {{-- Nuevo ícono para Materia Prima --}}
                                <p>
                                    Materia Prima
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('materias-primas.index') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('materias-primas') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Materias Primas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('materias-primas.create') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('materias-primas/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Nueva Materia Prima</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Módulo de Inventario (Producto Terminado) -->
                        <li class="nav-item {{ Request::is('productos-terminados*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ Request::is('productos-terminados*') ? 'active' : '' }}"
                                style="background-color: rgb(2, 74, 108); color: white">
                                <i class="nav-icon fas">
                                    <i class="bi bi-box"></i></i> {{-- Nuevo ícono para Producto Terminado --}}
                                <p>
                                    Producto Terminado
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('productos-terminados.index') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('productos-terminados') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Productos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('productos-terminados.create') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('productos-terminados/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Nuevo Producto</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Módulo de Órdenes de Producción -->
                        <li class="nav-item {{ Request::is('ordenes-produccion*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ Request::is('ordenes-produccion*') ? 'active' : '' }}"
                                style="background-color: rgb(2, 74, 108); color: white">
                                <i class="nav-icon fas">
                                    <i class="bi bi-tools"></i></i> {{-- Nuevo ícono para Producción --}}
                                <p>
                                    Órdenes de Producción
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('ordenes-produccion.index') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('ordenes-produccion') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Órdenes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('ordenes-produccion.create') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('ordenes-produccion/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Nueva Orden</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Módulo de Recetas -->
                        <li class="nav-item {{ Request::is('recetas*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('recetas*') ? 'active' : '' }}"
                                style="background-color: rgb(2, 74, 108); color: white">
                                <i class="nav-icon fas">
                                    <i class="bi bi-journal-check"></i></i> {{-- Nuevo ícono para Recetas --}}
                                <p>
                                    Recetas
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('recetas.index') }}" {{-- ¡CORRECCIÓN CLAVE AQUÍ! Usar route() helper --}}
                                        class="nav-link {{ Request::is('recetas') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Recetas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('recetas.create') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('recetas/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Nueva Receta</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Módulo de Clientes -->
                        <li class="nav-item {{ Request::is('clientes*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('clientes*') ? 'active' : '' }}"
                                style="background-color: rgb(2, 74, 108); color: white">
                                <i class="nav-icon fas">
                                    <i class="bi bi-people-fill"></i></i> {{-- Ícono de clientes --}}
                                <p>
                                    Clientes
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('clientes.index') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('clientes') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Clientes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('clientes.create') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('clientes/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Nuevo Cliente</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Módulo de Ventas/Despachos -->
                        <li class="nav-item {{ Request::is('ventas-despachos*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('ventas-despachos*') ? 'active' : '' }}"
                                style="background-color: rgb(2, 74, 108); color: white">
                                <i class="nav-icon fas">
                                    <i class="bi bi-cart-check"></i></i> {{-- Ícono de ventas --}}
                                <p>
                                    Ventas / Despachos
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('ventas-despachos.index') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('ventas-despachos') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Ventas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('ventas-despachos.create') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('ventas-despachos/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Nueva Venta</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Módulo de Proveedores -->
                        <li class="nav-item {{ Request::is('proveedores*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('proveedores*') ? 'active' : '' }}"
                                style="background-color: rgb(2, 74, 108); color: white">
                                <i class="nav-icon fas">
                                    <i class="bi bi-truck"></i></i> {{-- Ícono de proveedores --}}
                                <p>
                                    Proveedores
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('proveedores.index') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('proveedores') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Proveedores</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('proveedores.create') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('proveedores/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Nuevo Proveedor</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Módulo de Recepciones -->
                        <li class="nav-item {{ Request::is('recepciones-materia-prima*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ Request::is('recepciones-materia-prima*') ? 'active' : '' }}"
                                style="background-color: rgb(2, 74, 108); color: white">
                                <i class="nav-icon fas">
                                    <i class="bi bi-box-arrow-in-down"></i></i> {{-- Ícono de recepciones --}}
                                <p>
                                    Recepciones
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('recepciones-materia-prima.index') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('recepciones-materia-prima') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Recepciones</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('recepciones-materia-prima.create') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('recepciones-materia-prima/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Nueva Recepción</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Módulo de Ajustes de Inventario -->
                        <li class="nav-item {{ Request::is('ajustes-inventario*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ Request::is('ajustes-inventario*') ? 'active' : '' }}"
                                style="background-color: rgb(2, 74, 108); color: white">
                                <i class="nav-icon fas">
                                    <i class="bi bi-arrow-left-right"></i></i> {{-- Ícono de ajustes --}}
                                <p>
                                    Ajustes Inventario
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('ajustes-inventario.index') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('ajustes-inventario') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Ajustes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('ajustes-inventario.create') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('ajustes-inventario/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Nuevo Ajuste</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Módulo de Alertas de Stock -->
                        <li class="nav-item {{ Request::is('stock-alertas*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('stock-alertas*') ? 'active' : '' }}"
                                style="background-color: rgb(2, 74, 108); color: white">
                                <i class="nav-icon fas">
                                    <i class="bi bi-bell-fill"></i></i> {{-- Ícono de alertas --}}
                                <p>
                                    Alertas de Stock
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('stock-alertas.index') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('stock-alertas') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Alertas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Módulo de Reportes de Producción -->
                        <li class="nav-item {{ Request::is('reportes-produccion*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ Request::is('reportes-produccion*') ? 'active' : '' }}"
                                style="background-color: rgb(2, 74, 108); color:white">
                                <i class="nav-icon fas">
                                    <i class="bi bi-file-earmark-bar-graph-fill"></i></i> {{-- Ícono de reportes --}}
                                <p>
                                    Reportes Producción
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('reportes-produccion.index') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('reportes-produccion') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Reportes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('reportes-produccion.create') }}" {{-- Usar route() helper --}}
                                        class="nav-link {{ Request::is('reportes-produccion/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Generar Reporte</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Enlace para Salir -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();"
                                style="background-color: #a5161d"> <i class="nav-icon fas"><i
                                        class="bi bi-door-closed" style="color:white"></i></i>
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
            {{-- Sección principal de contenido que se llena con @yield('content') --}}
            @yield('content')
            <div class="content" style="background-color: white"></div>

            <!-- /.col-md-6 -->
        </div>

    </div>
    <!-- /.content -->

    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-ligth" style="background-color:rgb(255, 255, 255)">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Opciones Adicionales</h5>
            <p>Contenido del Sidebar de Control</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer" style="background-color: white; color:rgb(40, 43,51)">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            Sistema de Gestión de Producción
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; {{ date('Y') }} <a href="https://deligestion.com">Deligestión</a>.</strong> Todos
        los
        derechos reservados.
    </footer>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

    <!-- Datatables JS -->
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
            // Inicialización de DataTables para tablas con id 'example1'
            // Solo se inicializa si el elemento #example1 existe en la página
            if ($('#example1').length) {
                $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    "language": { // Configuración del idioma a español
                        "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                    }
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }
        });

        // Script para manejar SweetAlert2 si hay mensajes de sesión
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: '{{ session('error') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            @endif
        });
    </script>
</body>

</html>
