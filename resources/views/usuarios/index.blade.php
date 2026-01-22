@extends('layouts.admin')

@section('title', 'Usuarios del Sistema')

@section('breadcrumbs')
    <li class="active">Usuarios</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Usuarios de sistema</h2>
        <a href="{{ route('usuarios.create') }}" class="btn btn-danger">Ingresar Usuario</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <p class="text-muted">Mostrando 1-{{ $usuarios->count() }} de {{ $usuarios->count() }} elementos.</p>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Correo Electronico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $index => $usuario)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('usuarios.edit', $usuario->id) }}" 
                               class="btn btn-sm btn-primary" 
                               title="Editar">
                                ✏️
                            </a>
                            <form action="{{ route('usuarios.destroy', $usuario->id) }}" 
                                  method="POST" 
                                  style="display:inline;"
                                  onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-danger" 
                                        title="Eliminar">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No hay usuarios registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection