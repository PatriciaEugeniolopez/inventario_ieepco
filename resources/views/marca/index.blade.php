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
                                class="btn btn-sm btn-success btn-ver-marca"
                                data-id="{{ $marca->id }}"
                                title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>


                            <a href="{{ route('marca.edit', $marca->id) }}"
                                class="btn btn-sm btn-primary"
                                title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
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

<!-- Modal Ver Marca -->
<div class="modal fade" id="modalMarca" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" id="modalMarcaContent">
             <!-- Contenido AJAX -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).on('click', '.btn-ver-marca', function() {
        let marcaId = $(this).data('id');

        $.get('/marca/' + marcaId, function(response) {
            $('#modalMarcaContent').html(response);

            let modal = new bootstrap.Modal(
                document.getElementById('modalMarca')
            );
            modal.show();
        }).fail(function() {
            alert('Error al cargar la marca');
        });
      
    });
</script>
@endsection