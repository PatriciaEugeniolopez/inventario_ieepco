@extends('layouts.admin')

@section('title', 'Usuarios del Sistema')

@section('breadcrumbs')
<li class="active">Usuarios</li>
@endsection

@section('styles')
@endsection

@section('content')
<div class="container mt-4">
    <h2>Usuarios de sistema</h2>

    <a href="{{ route('usuarios.create') }}" class="btn btn-registrar mb-3">
        Ingresar Usuario
    </a>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <p class="text-muted">
        Mostrando 1-{{ $usuarios->count() }} de {{ $usuarios->count() }} elementos.
        @if(request('id') || request('nombre') || request('email'))
        <a href="{{ route('usuarios.index') }}" class="btn btn-sm btn-secondary ms-2">
            <i class="fas fa-times"></i> Limpiar filtros
        </a>
        @endif
    </p>

    <!-- FORMULARIO CON FILTROS -->
    <form method="GET" action="{{ route('usuarios.index') }}" id="filtroForm">
        <div class="table-responsive">
            <table class="table table-bordered table-hover tabla-usuarios-small">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th style="width:100px;">
                            ID
                            <input type="text"
                                name="id"
                                value="{{ request('id') }}"
                                class="form-control form-control-sm"
                                placeholder="Buscar ID..."
                                onchange="this.form.submit()">
                        </th>
                        <th>
                            Usuario
                            <input type="text"
                                name="nombre"
                                value="{{ request('nombre') }}"
                                class="form-control form-control-sm"
                                placeholder="Buscar usuario..."
                                onchange="this.form.submit()">
                        </th>
                        <th>
                            Correo Electrónico
                            <input type="text"
                                name="email"
                                value="{{ request('email') }}"
                                class="form-control form-control-sm"
                                placeholder="Buscar email..."
                                onchange="this.form.submit()">
                        </th>
                        <th class="text-center" style="width:140px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($usuarios as $index => $usuario)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $usuario->id }}</td>
                        <td>
                            {{ $usuario->nombre }}
                            @if($usuario->empleado)
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-id-badge"></i> {{ $usuario->empleado->nombre_completo }}
                            </small>
                            @endif
                           
                        </td>
                        <td>{{ $usuario->email }}</td>
                        <td class="text-center">
                            <!-- Botones de acción -->
                            <button type="button"
                                class="btn btn-sm btn-success btn-ver-usuario"
                                data-id="{{ $usuario->id }}"
                                title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>

                            <a href="{{ route('usuarios.edit', $usuario->id) }}"
                                class="btn btn-sm btn-primary"
                                title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('usuarios.destroy', $usuario->id) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="btn btn-sm btn-danger"
                                    title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            No hay usuarios registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>


            </table>
        </div>
    </form>
</div>

<!-- MODAL VER USUARIO -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" id="modalUsuarioContent">
            <!-- Contenido AJAX -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).on('click', '.btn-ver-usuario', function() {
        let userId = $(this).data('id');

        $.get('/usuarios/' + userId, function(response) {
            $('#modalUsuarioContent').html(response);

            let modal = new bootstrap.Modal(
                document.getElementById('modalUsuario')
            );
            modal.show();
        }).fail(function() {
            alert('Error al cargar la información del usuario');
        });
    });
</script>
@endsection