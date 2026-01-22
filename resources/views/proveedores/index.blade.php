@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Proveedores</h2>
            <a href="{{ route('proveedores.create') }}" class="btn btn-registrar">
                Registrar Proveedor
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <p class="text-muted">
            Mostrando 1-{{ count($proveedores) }} de {{ count($proveedores) }} elementos.
        </p>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nombre Proveedor</th>
                        <th>RFC</th>
                        <th>Numero Ext</th>
                        <th>Colonia</th>
                        <th>Municipio</th>
                        <th>Estado</th>
                        <th>Codigo Postal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proveedores as $index => $proveedor)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $proveedor->nombre_prov }}</td>
                            <td>{{ $proveedor->rfc }}</td>
                            <td>{{ $proveedor->numero_ext }}</td>
                            <td>{{ $proveedor->colonia }}</td>
                            <td>{{ $proveedor->municipio }}</td>
                            <td>{{ $proveedor->estado }}</td>
                            <td>{{ $proveedor->codigo_postal }}</td>
                            <td class="action-icons">
                                <a href="{{ route('proveedores.show', $proveedor->id_prov) }}" 
                                   title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('proveedores.edit', $proveedor->id_prov) }}" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('proveedores.destroy', $proveedor->id_prov) }}" 
                                      method="POST" 
                                      style="display: inline-block;"
                                      onsubmit="return confirm('¿Está seguro de eliminar este proveedor?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="border: none; background: none; color: #8B1538; cursor: pointer;" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No hay proveedores registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .table-container {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .btn-registrar {
        background-color: #8B1538;
        color: white;
        border: none;
    }
    .btn-registrar:hover {
        background-color: #6d0f2a;
        color: white;
    }
    .action-icons a {
        margin: 0 5px;
        color: #8B1538;
        text-decoration: none;
    }
    .action-icons a:hover {
        color: #6d0f2a;
    }
</style>
@endsection
