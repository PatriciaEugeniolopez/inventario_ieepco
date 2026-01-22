@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Registro de Empleados</h2>
            <a href="{{ route('empleados.create') }}" class="btn btn-registrar">
                Registrar Empleado
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="info-bar">
            <p class="mb-0">Mostrando <strong>1-{{ count($empleados) }}</strong> de <strong>{{ count($empleados) }}</strong> elementos.</p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre Empleado</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Puesto</th>
                        <th>Area Asignacion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($empleados as $index => $empleado)
                        <tr>
                            <td style="color: #8B1538; font-weight: 600;">{{ $index + 1 }}</td>
                            <td style="color: #4169E1;">{{ strtoupper($empleado->nombre_empleado) }}</td>
                            <td>{{ strtoupper($empleado->apellido_p) }}</td>
                            <td>{{ strtoupper($empleado->apellido_m ?? '') }}</td>
                            <td>{{ strtoupper($empleado->puesto ?? '') }}</td>
                            <td>{{ strtoupper($empleado->areaAsignacion->nombre_asignacion ?? '') }}</td>
                            <td class="action-icons text-center">
                                <a href="{{ route('empleados.show', $empleado->id_empleado) }}" 
                                   title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('empleados.edit', $empleado->id_empleado) }}" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('empleados.destroy', $empleado->id_empleado) }}" 
                                      method="POST" 
                                      style="display: inline-block;"
                                      onsubmit="return confirm('¿Está seguro de eliminar este empleado?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="border: none; background: none; cursor: pointer; padding: 0;" 
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay empleados registrados</td>
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
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .btn-registrar {
        background-color: #8B1538;
        color: white;
        border: none;
        padding: 8px 20px;
        font-size: 14px;
    }
    
    .btn-registrar:hover {
        background-color: #6d0f2a;
        color: white;
    }
    
    .info-bar {
        background-color: #4a4a4a;
        color: white;
        padding: 10px 15px;
        margin: 0 -15px;
    }
    
    .table {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table thead th {
        background-color: white;
        color: #8B1538;
        font-weight: 700;
        border-bottom: 1px solid #dee2e6;
        border-top: 1px solid #dee2e6;
        padding: 12px;
        text-transform: uppercase;
        font-size: 11px;
        white-space: nowrap;
    }
    
    .table tbody td {
        padding: 12px;
        vertical-align: middle;
        font-size: 13px;
        border-bottom: 1px solid #e9ecef;
        color: #333;
    }
    
    .table tbody tr {
        background-color: white;
    }
    
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    
    .table tbody tr:hover {
        background-color: #f1f3f5;
    }
    
    .action-icons {
        white-space: nowrap;
    }
    
    .action-icons a,
    .action-icons button {
        color: #8B1538;
        margin: 0 6px;
        font-size: 16px;
        transition: all 0.2s;
        text-decoration: none;
    }
    
    .action-icons a:hover,
    .action-icons button:hover {
        color: #6d0f2a;
        transform: scale(1.1);
    }
    
    .action-icons button i {
        color: #8B1538;
    }
    
    .action-icons button:hover i {
        color: #6d0f2a;
    }
</style>
@endsection