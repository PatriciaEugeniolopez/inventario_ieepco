@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Areas de Asignacion</h2>
            <a href="{{ route('area_asignacion.create') }}" class="btn btn-nueva-area">
                Nueva Área
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="info-bar">
            <p class="mb-0">Mostrando <strong>1-{{ count($areas) }}</strong> de <strong>{{ count($areas) }}</strong> elementos.</p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NOMBRE DE ÁREA</th>
                        <th>RESPONSABLE</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($areas as $index => $area)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $area->nombre_asignacion }}</td>
                            <td>{{ $area->responsable_area ?? '' }}</td>
                            <td class="action-icons">
                                <a href="{{ route('area_asignacion.show', $area->id_asignacion) }}" 
                                   title="Ver"
                                   class="text-danger">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('area_asignacion.edit', $area->id_asignacion) }}" 
                                   title="Editar"
                                   class="text-danger">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('area_asignacion.destroy', $area->id_asignacion) }}" 
                                      method="POST" 
                                      style="display: inline-block;"
                                      onsubmit="return confirm('¿Está seguro de eliminar esta área?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="border: none; background: none; cursor: pointer;" 
                                            class="text-danger"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay áreas registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <button class="btn btn-todo">
                <i class="fas fa-check"></i> Todo
            </button>
        </div>
    </div>
</div>

<style>
    .table-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .btn-nueva-area {
        background-color: #8B1538;
        color: white;
        border: none;
        padding: 8px 20px;
        font-size: 14px;
    }
    
    .btn-nueva-area:hover {
        background-color: #6d0f2a;
        color: white;
    }
    
    .info-bar {
        background-color: #4a4a4a;
        color: white;
        padding: 10px 15px;
        margin: 0 -15px;
    }
    
    .table thead th {
        background-color: #f8f9fa;
        color: #8B1538;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        padding: 12px;
        text-transform: uppercase;
        font-size: 12px;
    }
    
    .table tbody td {
        padding: 12px;
        vertical-align: middle;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .action-icons a,
    .action-icons button {
        margin: 0 8px;
        font-size: 16px;
        transition: all 0.2s;
    }
    
    .action-icons a:hover,
    .action-icons button:hover {
        opacity: 0.7;
    }
    
    .table-footer {
        padding: 15px;
        text-align: right;
        border-top: 1px solid #dee2e6;
    }
    
    .btn-todo {
        background-color: #4a4a4a;
        color: white;
        border: none;
        padding: 8px 20px;
        font-size: 14px;
    }
    
    .btn-todo:hover {
        background-color: #333;
        color: white;
    }
</style>
@endsection