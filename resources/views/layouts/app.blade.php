<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'IEEPCO')</title>
    

     @vite(['resources/css/app.css', 'resources/js/app.js'])
     <link href="{{ asset('css/style.css') }}" rel="stylesheet">
 

</head>

<body class="hold-transition skin-red-light sidebar-mini" >
    <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid justify-content-center">
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
                                Login
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

    <div class="container">
        @yield('content')
    </div>
</body>

</html>