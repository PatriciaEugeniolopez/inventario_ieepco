@extends('layouts.admin')

@section('title', 'Proveedores')

@section('content')
<div class="container mt-4">

    <h2>Proveedores</h2>

    <a href="{{ route('proveedor.create') }}" 
       class="btn btn-registrar mb-3">
        Registrar Proveedor
    </a>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" 
                class="btn-close" 
                data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover tabla-usuarios-small">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>RFC</th>
                <th>Teléfono</th>
                <th>Núm. Ext</th>
                <th>Colonia</th>
                <th>Municipio</th>
                <th>Estado</th>
                <th>Código Postal</th>
                <th style="width:140px">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($proveedor as $index => $proveedor)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $proveedor->nombre_prov }}</td>
                <td>{{ $proveedor->rfc }}</td>
                <td>{{ $proveedor->telefono }}</td>
                <td>{{ $proveedor->numero_ext }}</td>
                <td>{{ $proveedor->colonia }}</td>
                <td>{{ $proveedor->municipio }}</td>
                <td>{{ $proveedor->estado }}</td>
                <td>{{ $proveedor->codigo_postal }}</td>
                <td class="text-center">
                    <!-- VER -->
                    <button type="button"
                        class="btn btn-sm btn-success btn-ver-proveedor"
                        data-id="{{ $proveedor->id_prov }}"
                        title="Ver">
                        <i class="fas fa-eye"></i>
                    </button>

                    <!-- EDITAR -->
                    <button type="button"
                        class="btn btn-sm btn-primary btn-editar-proveedor"
                        data-id="{{ $proveedor->id_prov }}"
                        title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>


                    <form action="{{ route('proveedor.destroy', $proveedor) }}"
                        method="POST"
                        style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                            onclick="return confirm('¿Eliminar proveedor?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>

                </td>
            </tr>

            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection


<div class="modal fade" id="modalProveedor" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" id="modalProveedorContent">
            <!-- CONTENIDO AJAX -->
        </div>
    </div>
</div>



@section('scripts')
<script>
/* VER */
$(document).on('click', '.btn-ver-proveedor', function () {
    let id = $(this).data('id');

    $.get('/proveedor/' + id, function (response) {
        $('#modalProveedorContent').html(response);

        let modal = new bootstrap.Modal(
            document.getElementById('modalProveedor')
        );
        modal.show();
    }).fail(() => alert('Error al cargar proveedor'));
});

/* EDITAR */
$(document).on('click', '.btn-editar-proveedor', function () {
    let id = $(this).data('id');

    $.get('/proveedor/' + id + '/edit', function (response) {
        $('#modalProveedorContent').html(response);

        let modal = new bootstrap.Modal(
            document.getElementById('modalProveedor')
        );
        modal.show();
    }).fail(() => alert('Error al cargar formulario'));
});
</script>
@endsection
