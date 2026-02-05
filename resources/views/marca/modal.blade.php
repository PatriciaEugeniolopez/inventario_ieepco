<div class="modal-header">
    <h5 class="modal-title">
        <i class="fas fa-tag"></i> {{ $marca->nombre_marca }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th style="width: 30%">ID</th>
            <td>{{ $marca->id }}</td>
        </tr>
        <tr>
            <th>Nombre de la Marca</th>
            <td>{{ $marca->nombre_marca }}</td>
        </tr>
        <tr>
            <th>Estado</th>
            <td>
                @if($marca->status ?? 1)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-danger">Inactivo</span>
                @endif
            </td>
        </tr>
    </table>
</div>

<div class="modal-footer">
    <a href="{{ route('marca.edit', $marca->id) }}"
        class="btn btn-registrar">
        <i class="fas fa-edit"></i> Editar
    </a>

    <button type="button"
        class="btn btn-secondary"
        data-bs-dismiss="modal"> Cerrar
    </button>

    
</div>
