@extends('layouts.admin')

@section('title', 'Empleados')
@section('content')
<div class="container mt-4">

    <h2>Registro de Empleados</h2>
    <a href="{{ route('empleados.create') }}" class="btn btn-registrar">
        Registrar Empleado
    </a>


    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <p>Mostrando {{ $empleados->firstItem() }}-{{ $empleados->lastItem() }}
        de {{ $empleados->total() }} elementos.
        @if(request()->query())
        <a href="{{ route('empleados.index') }}"
            class="btn btn-sm btn-secondary ms-2">
            <i class="fas fa-times"></i> Limpiar filtros
        </a>
        @endif
    </p>



    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center tabla-usuarios-small">
            <thead>
                <tr>
                    <th>#</th>
                    <th>
                        <form method="GET" action="{{ route('empleados.index') }}">
                            Nombre Empleado
                            <input type="text"
                                name="nombre"
                                value="{{ request('nombre') }}"
                                class="form-control form-control-sm"
                                placeholder="Buscar nombre"
                                onchange="this.form.submit()">
                    </th>
                    <th>
                        Apellido Paterno
                        <input type="text"
                            name="apellido_p"
                            value="{{ request('apellido_p') }}"
                            class="form-control form-control-sm"
                            placeholder="Buscar apellido paterno"
                            onchange="this.form.submit()">
                    </th>
                    <th>
                        Apellido Materno
                        <input type="text"
                            name="apellido_m"
                            value="{{ request('apellido_m') }}"
                            class="form-control form-control-sm"
                            placeholder="Buscar apellido materno"
                            onchange="this.form.submit()">
                    </th>
                    <th>
                        Puesto
                        <input type="text"
                            name="puesto"
                            value="{{ request('puesto') }}"
                            class="form-control form-control-sm"
                            placeholder="Buscar puesto"
                            onchange="this.form.submit()">
                    </th>
                    <th>
                        Área de asignación
                        <input type="text"
                            name="fk_area_trabajo"
                            value="{{ request('fk_area_trabajo') }}"
                            class="form-control form-control-sm"
                            placeholder="Buscar area"
                            onchange="this.form.submit()">
                    </th>
                    </form>
                    <th style="width:130px">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empleados as $index => $empleado)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ strtoupper($empleado->nombre_empleado) }}</td>
                    <td>{{ strtoupper($empleado->apellido_p) }}</td>
                    <td>{{ strtoupper($empleado->apellido_m ?? '') }}</td>
                    <td>{{ strtoupper($empleado->puesto ?? '') }}</td>
                    <td>{{ strtoupper($empleado->areaAsignacion->nombre_asignacion ?? '') }}</td>
                    <td class="text-center">

                        <button type="button"
                            class="btn btn-sm btn-success btn-ver-empleados"
                            data-id="{{ $empleado->id_empleado }}"
                            title="Ver">
                            <i class="fas fa-eye"></i>
                        </button>

                        <!-- <a href="{{ route('empleados.show', $empleado->id_empleado) }}"
                                class="btn btn-sm btn-success"
                                title="Ver">
                                <i class="fas fa-eye"></i>
                            </a> -->
                        <a href="{{ route('empleados.edit', $empleado->id_empleado) }}"
                            class="btn btn-sm btn-primary"
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
                                class="btn btn-sm btn-danger"
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
        <div class="panel-footer text-center">
            {{ $empleados->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<!-- Modal Ver Empleado -->
<div class="modal fade" id="modalEmpleados" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" id="modalEmpleadosContent">
            <!-- Contenido cargado por AJAX -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).on('click', '.btn-ver-empleados', function() {
        let empleadoId = $(this).data('id'); // Cambiado de 'id_empleado' a 'id'
        
        $.get('/empleados/' + empleadoId, function(response) {
            $('#modalEmpleadosContent').html(response);
            let modal = new bootstrap.Modal(document.getElementById('modalEmpleados'));
            modal.show();
        }).fail(function() {
            alert('Error al cargar los datos del empleado.');
        });
    });
</script>
@endsection