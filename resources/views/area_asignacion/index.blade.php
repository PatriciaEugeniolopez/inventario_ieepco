@extends('layouts.admin')
@section('title', 'Areas de Asignacion')
@section('content')
<div class="container mt-4">
    <h2>Áreas de Asignación</h2>
    <a href="{{ route('area_asignacion.create') }}"
        class="btn-nueva-area">Registrar Nueva Área
    </a>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" style="margin: 15px 20px;" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <p>
        Mostrando {{ $areas->firstItem() ?? 0 }}-{{ $areas->lastItem() ?? 0 }}
        de {{ $areas->total() }} elementos.
    </p>

    <!-- Formulario de búsqueda -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover tabla-usuarios-small">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <form method="GET" action="{{ route('area_asignacion.index') }}" id="filtroForm">
                        <th style="width:100px;">
                            NOMBRE DE ÁREA
                            <input type="text"
                                name="nombre_asignacion"
                                value="{{ request('nombre_asignacion') }}"
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
                    </form>
                    <th class="text-center" style="width: 120px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($areas as $index => $area)
                <tr>
                    <td>{{ ($areas->currentPage() - 1) * $areas->perPage() + $loop->iteration }}</td>
                    <td>{{ $area->nombre_asignacion }}</td>
                    <td>{{ $area->responsable_area ?? '' }}</td>
                    <td class="text-center">
                        <button type="button"
                            class="btn btn-sm btn-success btn-ver-area"
                            data-id="{{ $area->id_asignacion }}"
                            title="Ver">
                            <i class="fa fa-eye"></i>
                        </button>
                        <a href="{{ route('area_asignacion.edit', $area->id_asignacion) }}"
                            class="btn btn-sm btn-primary"
                            title="Editar">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <form action="{{ route('area_asignacion.destroy', $area->id_asignacion) }}"
                            method="POST"
                            class="d-inline"
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
                    <td colspan="4" class="text-center" style="padding: 30px;">
                        @if(request('nombre_asignacion') || request('responsable_area'))
                        No se encontraron resultados para la búsqueda.
                        @else
                        No hay áreas registradas
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div>
            {{ $areas->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<!-- MODAL VER ASIGNACION ÁREA -->
<div class="modal fade" id="modalAreaAsignacion" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" id="modalAreaAsignacionContent">
            <!-- Contenido AJAX -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Mostrar modal de área
    $(document).on('click', '.btn-ver-area', function() {
        let areaId = $(this).data('id');

        $.ajax({
            url: '/area_asignacion/' + areaId,
            method: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#modalAreaAsignacionContent').html(response);
                let modal = new bootstrap.Modal(document.getElementById('modalAreaAsignacion'));
                modal.show();
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Error al cargar el área de asignación');
            }
        });
    });
</script>
@endsection