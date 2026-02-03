@extends('layouts.admin')

@section('title', 'Marcas')
@section('breadcrumbs')
<li class="active">Marca</li>
@endsection

@section('styles')
@endsection

@section('content')
<div class="container mt-4">

    <h2>Marcas</h2>

    <a href="{{ route('marca.create') }}" class="btn btn-registrar">
        Ingresar Marca
    </a>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="panel panel-default">

        <p class="text-muted">
            Mostrando {{ $marcas->firstItem() }}-{{ $marcas->lastItem() }}
            de {{ $marcas->total() }} elementos.
            @if(request('nombre_marca'))
            <a href="{{ route('marca.index') }}" class="btn btn-sm btn-secondary ms-2">
                <i class="fas fa-times"></i> Limpiar filtros
            </a>
            @endif
        </p>

        <div class="table-responsive">
            <table class="table table-bordered table-hover tabla-usuarios-small">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px">#</th>
                        <th>
                            <form method="GET" action="{{ route('marca.index') }}">
                                <span>Nombre de la Marca</span>
                                <input type="text"
                                    name="nombre_marca"
                                    value="{{ request('nombre_marca') }}"
                                    class="form-control"
                                    placeholder="Buscar marca..."
                                    onchange="this.form.submit()">
                            </form>
                        </th>

                        <th class="text-center" style="width:130px">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($marcas as $i => $marca)
                    <tr>
                        <td>{{ $marcas->firstItem() + $i }}</td>
                        <td>{{ $marca->nombre_marca }}</td>
                        <td class="text-center">

                            <button
                                class="btn btn-sm btn-success"
                                data-toggle="modal"
                                data-target="#modalMarca"
                                data-id="{{ $marca->id }}"
                                data-nombre="{{ $marca->nombre_marca }}"
                                title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>


                            <a href="{{ route('marca.edit', $marca->id) }}"
                                class="btn btn-sm btn-primary"
                                title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('marca.destroy', $marca->id) }}"
                                method="POST"
                                style="display:inline-block"
                                onsubmit="return confirm('¿Eliminar esta marca?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="panel-footer text-center">
                {{ $marcas->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalMarca" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form id="formMarca" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h4 class="modal-title">Detalle de Marca</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Nombre de la Marca</label>
                        <input type="text"
                            name="nombre_marca"
                            id="nombre_marca_modal"
                            class="form-control"
                            required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Guardar Cambios
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


<script>
    $('#modalMarca').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);

        var id = button.data('id');
        var nombre = button.data('nombre');

        var modal = $(this);

        modal.find('#nombre_marca_modal').val(nombre);

        // Ruta dinámica para actualizar
        modal.find('#formMarca').attr('action', '/marca/' + id);
    });
</script>


@endsection