<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistema-de-inventario')</title>
    
    <!-- Bootstrap, Font Awesome y jQuery  -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>

<body class="hold-transition skin-red-light sidebar-mini">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    SISTEMA DE INVENTARIO DE INFORMÁTICA
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button class="btn btn-link nav-link text-white text-decoration-none" type="submit" style="border: none;">
                                    Salir ({{ Auth::user()->nombre ?? 'Usuario' }})
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="container-fluid">
        <div class="row">
            <!-- SIDEBAR -->
            <aside class="col-sm-2">
                <img src="{{ asset('imagenes/iee2.png') }}" class="img-responsive" style="margin-bottom:15px;">

                <div class="sidebar-menu">
                    <h4>Menú Principal</h4>
                    <ul>
                        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}">
                                <i class="fa fa-home"></i> Inicio
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- INVENTARIO -->
                <div class="sidebar-menu">
                    <h4>Inventario</h4>
                    <ul>
                        <li class="{{ request()->routeIs('mobiliarios.*') ? 'active' : '' }}">
                            <a href="{{ route('mobiliarios.index') }}">
                                <i class="fas fa-box"></i> Mobiliario
                            </a>
                        </li>
                        <!-- <li class="{{ request()->routeIs('mobiliario_asignacion.*') ? 'active' : '' }}">
                            <a href="{{ route('mobiliario_asignacion.index') }}">
                                <i class="fas fa-hand-holding"></i> Asignaciones
                            </a>
                        </li> -->
                        <li class="{{ request()->routeIs('mobiliario_renta.*') ? 'active' : '' }}">
                            <a href="{{ route('mobiliario_renta.index') }}">
                                <i class="fas fa-calendar-alt"></i> Rentas
                            </a>
                        </li>
                        <!-- <li class="{{ request()->routeIs('mobiliario_historial.*') ? 'active' : '' }}">
                            <a href="{{ route('mobiliario_historial.index') }}">
                                <i class="fas fa-history"></i> Historial
                            </a>
                        </li> -->
                    </ul>
                </div>

                <!-- GESTIÓN -->
                <div class="sidebar-menu">
                    <h4>Gestión</h4>
                    <ul>
                        <li class="{{ request()->routeIs('area_asignacion.*') ? 'active' : '' }}">
                            <a href="{{ route('area_asignacion.index') }}">
                                <i class="fas fa-building"></i> Áreas
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('empleados.*') ? 'active' : '' }}">
                            <a href="{{ route('empleados.index') }}">
                                <i class="fas fa-users"></i> Empleados
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('proveedor.*') ? 'active' : '' }}">
                            <a href="{{ route('proveedor.index') }}">
                                <i class="fas fa-truck"></i> Proveedores
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- CATÁLOGOS -->
                <div class="sidebar-menu">
                    <h4>Catálogos</h4>
                    <ul>
                        <li class="{{ request()->routeIs('marca.*') ? 'active' : '' }}">
                            <a href="{{ route('marca.index') }}">
                                <i class="fas fa-copyright"></i> Marcas
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('modelo.*') ? 'active' : '' }}">
                            <a href="{{ route('modelo.index') }}">
                                <i class="fas fa-laptop"></i> Modelos
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                            <a href="{{ route('usuarios.index') }}">
                                <i class="fa fa-user"></i> Usuarios
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>

            <main class="col-sm-10">
                @yield('content')
            </main>
        </div>
    </div>

 
    @yield('scripts')
</body>

</html>