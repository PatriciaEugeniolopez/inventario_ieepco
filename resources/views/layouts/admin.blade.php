<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistema-de-inventario')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="hold-transition skin-red-light sidebar-mini">

    <header class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    SISTEMA DE INVENTARIO DE INFORMÁTICA
                </a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ route('dashboard') }}">Inicio</a></li>

                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button class="btn btn-link navbar-btn">
                            Salir ({{ Auth::user()->nombre ?? 'Usuario' }})
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR -->
            <aside class="col-sm-2">
                <img src="{{ asset('imagenes/iee2.png') }}" class="img-responsive" style="margin-bottom:15px;">

                <h4>Menú</h4>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li><a href="#">Inventarios</a></li>
                    <li><a href="{{ route('area_asignacion.index') }}">Áreas Asignación</a></li>
                    <li><a href="{{ route('empleados.index') }}">Empleados</a></li>
                    <li><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
                </ul>

                <hr>

                <h4>Catálogos</h4>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="{{ route('marca.index') }}">Marcas</a></li>
                    <li><a href="{{ route('modelo.index') }}">Modelos</a></li>
                    <li><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
                </ul>
            </aside>

           
            <main class="col-sm-10">

                <!--<ol class="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">IEEPCO</a></li>
                    @yield('breadcrumbs')
                </ol>-->

                @yield('content')

            </main>


        </div>
    </div>
    <!-- Breadcrumbs (opcional) 
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; IEEPCO {{ date('Y') }}</p>
        <p class="pull-right">Laravel</p>
    </div>
</footer>-->

</body>

</html>