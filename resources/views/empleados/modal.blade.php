<div class="modal-header bg-success text-white">
    <h5 class="modal-title">
        <i class="fas fa-user"></i> Detalles del Empleado
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <strong><i class="fas fa-user-circle"></i> Información Personal</strong>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> {{ strtoupper($empleado->nombre_empleado) }}</p>
                    <p><strong>Apellido Paterno:</strong> {{ strtoupper($empleado->apellido_p) }}</p>
                    <p><strong>Apellido Materno:</strong> {{ strtoupper($empleado->apellido_m ?? 'N/A') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <strong><i class="fas fa-briefcase"></i> Información Laboral</strong>
                </div>
                <div class="card-body">
                    <p><strong>Puesto:</strong> {{ strtoupper($empleado->puesto ?? 'N/A') }}</p>
                    <p><strong>Área de Asignación:</strong> {{ strtoupper($empleado->areaAsignacion->nombre_asignacion ?? 'N/A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a href="{{ route('empleados.edit', $empleado->id_empleado) }}"
        class="btn btn-registrar">
        <i class="fas fa-edit"></i> Editar
    </a>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fas fa-times"></i> Cerrar
    </button>
</div>