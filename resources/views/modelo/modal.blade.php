<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="fas fa-tag"></i> {{ $modelo->nombre_modelo }}
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th style="width: 35%">ID</th>
            <td>{{ $modelo->id }}</td>
        </tr>

        <tr>
            <th>Modelo</th>
            <td>{{ $modelo->nombre_modelo }}</td>
        </tr>

        <tr>
            <th>Marca</th>
            <td>
                <span class="badge bg-secondary">
                    {{ $modelo->marca->nombre_marca ?? 'Sin marca' }}
                </span>
            </td>
        </tr>

        <tr>
            <th>Estatus</th>
            <td>
                @if($modelo->status == 1)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-danger">Inactivo</span>
                @endif
            </td>
        </tr>
    </table>
</div>

<div class="modal-footer">
    <a href="{{ route('modelo.edit', $modelo->id) }}" 
    class="btn btn-registrar">
        <i class="fas fa-edit"></i> Editar
    </a>

    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        Cerrar
    </button>
</div>
