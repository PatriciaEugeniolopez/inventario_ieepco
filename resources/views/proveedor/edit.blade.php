<form action="{{ route('proveedor.update', $proveedor) }}" method="POST">
@csrf
@method('PUT')

<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">Editar Proveedor</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <div class="row">

        <div class="col-md-6 mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre_prov"
                   class="form-control"
                   value="{{ $proveedor->nombre_prov }}">
        </div>

        <div class="col-md-6 mb-3">
            <label>RFC</label>
            <input type="text" name="rfc"
                   class="form-control"
                   value="{{ $proveedor->rfc }}">
        </div>

        <div class="col-md-4 mb-3">
            <label>Núm. Ext</label>
            <input type="number" name="numero_ext"
                   class="form-control"
                   value="{{ $proveedor->numero_ext }}">
        </div>

        <div class="col-md-4 mb-3">
            <label>Colonia</label>
            <input type="text" name="colonia"
                   class="form-control"
                   value="{{ $proveedor->colonia }}">
        </div>

        <div class="col-md-4 mb-3">
            <label>Código Postal</label>
            <input type="number" name="codigo_postal"
                   class="form-control"
                   value="{{ $proveedor->codigo_postal }}">
        </div>

        <div class="col-md-6 mb-3">
            <label>Municipio</label>
            <input type="text" name="municipio"
                   class="form-control"
                   value="{{ $proveedor->municipio }}">
        </div>

        <div class="col-md-6 mb-3">
            <label>Estado</label>
            <input type="text" name="estado"
                   class="form-control"
                   value="{{ $proveedor->estado }}">
        </div>

    </div>
</div>

<div class="modal-footer">
    <button type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal">
        Cancelar
    </button>
    <button class="btn btn-primary" type="submit">
        Guardar Cambios
    </button>
</div>
</form>
