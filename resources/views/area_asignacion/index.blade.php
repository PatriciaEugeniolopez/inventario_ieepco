@extends('layouts.admin')
@section('title', 'Areas de Asignacion')
@section('content')

<div class="container mt-4">
    <h2>Áreas de Asignación</h2>
    <a href="{{ route('area_asignacion.create') }}" class="btn-nueva-area">Nueva Área</a>


    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" style="margin: 15px 20px;" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif


    <p class="mb-0">Mostrando <strong>1-{{ count($areas) }}</strong> de <strong>{{ count($areas) }}</strong> elementos.</p>


    <div class="table-responsive">
        <table class="table table-bordered  table-hover tabla-usuarios-small">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width:100px;">
                        NOMBRE DE ÁREA
                        <input type="text"
                            name="nombre_asignacion"
                            value="{{ request('responsable_area') }}"
                            class="form-control form-control-sm"
                            placeholder="Buscar nombre del área"
                            onchange="this.form.submit()">
                    </th>
                    <th style="width:100px;">
                        RESPONSABLE
                        <input type="text"
                            name="responsable_area"
                            value="{{ request('responsable_area') }}"
                            class="form-control form-control-sm"
                            placeholder="Buscar el responsable del área"
                            onchange="this.form.submit()">
                    </th>

                    <th class="text-center" style="width: 120px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($areas as $index => $area)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $area->nombre_asignacion }}</td>
                    <td>{{ $area->responsable_area ?? '' }}</td>
                    <td class="text-center">
                        <a href="{{ route('area_asignacion.show', $area->id_asignacion) }}"
                            class="btn btn-sm btn-success"
                            title="Ver">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="{{ route('area_asignacion.edit', $area->id_asignacion) }}"
                            class="btn btn-sm btn-primary"
                            title="Editar">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <form action="{{ route('area_asignacion.destroy', $area->id_asignacion) }}"
                            method="POST"
                            style="display: inline-block;"
                            onsubmit="return confirm('¿Está seguro de eliminar esta área?');">
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
                    <td colspan="4" class="text-center" style="padding: 30px;">No hay áreas registradas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <button class="btn-todo">
            <i class="fa fa-check"></i> Todo
        </button>
    </div>

</div>
@endsection