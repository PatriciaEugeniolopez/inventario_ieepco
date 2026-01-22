<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'IEEPCO')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">

        <div class="navbar-header">
            <a class="navbar-brand" href="{{ url('/') }}">
                INSTITUTO ESTATAL ELECTORAL Y DE PARTICIPACION CIUDADANA DE OAXACA
            </a>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li><a href="{{ url('/') }}">Inicio</a></li>

            @guest
                <li><a href="{{ route('login') }}">Login</a></li>
            @else
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button class="btn btn-link navbar-btn">
                            Logout ({{ Auth::user()->nombre ?? 'Usuario' }})
                        </button>
                    </form>
                </li>
            @endguest
        </ul>

    </div>
</nav>

<div class="container">
    @yield('content')
</div>
<!-- Bootstrap (puedes cambiar versión después) 
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; IEEPCO {{ date('Y') }}</p>
        <p class="pull-right">Powered by Laravel</p>
    </div>
</footer>-->

</body>
</html>
