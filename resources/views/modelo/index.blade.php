@extends('layouts.admin')
@section('title', 'Modelos')
@section('content')
<div class="container mt-4">
    <h2>Modelos</h2>
    <a href="{{ route('modelo.create') }}" class="btn btn-registrar">
        Ingresar Modelo
    </a>


    <p>Mostrando {{ $modelos->firstItem() }}-{{ $modelos->lastItem() }}
        de {{ $modelos->total() }} elementos.
        @if(request('nombre_modelo') || request('fk_marca'))
        <a href="{{ route('modelo.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-times"></i> Limpiar filtros
        </a>
        @endif
    </p>

    <!-- FORMULARIO-->
    <form method="GET" action="{{ route('modelo.index') }}" id="filtroForm">
        <div class="table-responsive">
            <table class="table table-bordered table-hover tabla-usuarios-small">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>
                            Nombre Modelo
                            <input type="text"
                                name="nombre_modelo"
                                value="{{ request('nombre_modelo') }}"
                                class="form-control"
                                placeholder="Buscar modelo..."
                                onchange="document.getElementById('filtroForm').submit()">
                        </th>
                        <th>Marca
                            <select name="fk_marca"
                                class="form-control"
                                onchange="document.getElementById('filtroForm').submit()">
                                <option value="">Todas las marcas</option>
                                @foreach($marcas as $marca)
                                <option value="{{ $marca->id }}"
                                    {{ request('fk_marca') == $marca->id ? 'selected' : '' }}>
                                    {{ $marca->nombre_marca }}
                                </option>
                                @endforeach
                            </select>
                        </th>
                        <th class="text-center" style="width:150px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($modelos as $i => $modelo)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $modelo->nombre_modelo }}</td>
                        <td>{{ $modelo->marca->nombre_marca ?? 'Sin marca' }}</td>
                        <td class="text-center">
                            <button type="button"
                                class="btn btn-sm btn-success btn-ver-modelo"
                                data-id="{{ $modelo->id }}"
                                title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="{{ route('modelo.edit', $modelo->id) }}"
                                class="btn btn-sm btn-primary"
                                title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('modelo.destroy', $modelo->id) }}"
                                method="POST"
                                style="display: inline-block;"
                                onsubmit="return confirm('¿Estás seguro de eliminar este modelo?')">
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
                        <td colspan="4" class="text-center">
                            No se encontraron modelos.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
            <div class="panel-footer text-center">
                {{ $modelos->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </form>
    <!-- FIN DEL FORMULARIO -->

</div>

<!-- MODAL VER USUARIO -->
<div class="modal fade" id="modalModelo" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" id="modalModeloContent">
            <!-- Contenido AJAX -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).on('click', '.btn-ver-modelo', function() {
        let modeloId = $(this).data('id');

        $.get('/modelo/' + modeloId, function(response) {
            $('#modalModeloContent').html(response);

            let modal = new bootstrap.Modal(
                document.getElementById('modalModelo')
            );
            modal.show();
        }).fail(function() {
            alert('Error al cargar el modelo');
        });
    });
</script>
@endsection