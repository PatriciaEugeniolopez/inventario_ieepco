<div class="modal-header btn btn-registrar row justify-content-center">
    <h5 class="modal-title">Área de Asignación: {{ $area_asignacion->nombre_asignacion }}</h5>
</div>


<div class="modal-body">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th style="width: 30%; background-color: #f8f9fa;">ID:</th>
                <td>{{ $area_asignacion->id_asignacion }}</td>
            </tr>
            <tr>
                <th style="background-color: #f8f9fa;">Nombre de Área:</th>
                <td>{{ $area_asignacion->nombre_asignacion }}</td>
            </tr>
            <tr>
                <th style="background-color: #f8f9fa;">Responsable:</th>
                <td>{{ $area_asignacion->responsable_area ?? 'Sin asignar' }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="modal-footer">
    <a href="{{ route('area_asignacion.index') }}" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i> Volver
    </a>
    <a href="{{ route('area_asignacion.edit', $area_asignacion->id_asignacion) }}"
        class="btn btn-primary">
        <i class="fa fa-pencil"></i> Editar
    </a>
</div>