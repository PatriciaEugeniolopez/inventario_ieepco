<div class="modal-header">
    <h5 class="modal-title">Detalle de Renta #{{ $mobiliario_renta->id_renta }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <div class="row">
        {{-- Información del mobiliario --}}
        <div class="col-md-6">
            <h6 class="text-primary">Información del Mobiliario</h6>
            <table class="table table-sm">
                <tr>
                    <th width="40%">Nombre:</th>
                    <td>{{ $mobiliario_renta->mobiliario->nombre_mobiliario ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Modelo:</th>
                    <td>{{ $mobiliario_renta->mobiliario->modelo->nombre ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Marca:</th>
                    <td>{{ $mobiliario_renta->mobiliario->marca->nombre ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Número de Serie:</th>
                    <td>{{ $mobiliario_renta->mobiliario->num_serie ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Cantidad:</th>
                    <td>{{ $mobiliario_renta->cantidad }}</td>
                </tr>
            </table>
        </div>

        {{-- Información de la renta --}}
        <div class="col-md-6">
            <h6 class="text-primary">Información de la Renta</h6>
            <table class="table table-sm">
                <tr>
                    <th width="40%">Proveedor:</th>
                    <td>{{ $mobiliario_renta->mobiliario->proveedor->nombre_prov ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Estado:</th>
                    <td>
                        <span class="badge bg-{{ 
                            $mobiliario_renta->estado_renta === 'activa' ? 'success' : 
                            ($mobiliario_renta->estado_renta === 'finalizada' ? 'secondary' : 'danger')
                        }}">
                            {{ ucfirst($mobiliario_renta->estado_renta) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Fecha Inicio:</th>
                    <td>{{ $mobiliario_renta->fecha_inicio->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Fecha Fin:</th>
                    <td>
                        {{ $mobiliario_renta->fecha_fin->format('d/m/Y') }}
                        @if($mobiliario_renta->esta_activa)
                            @if($mobiliario_renta->diasRestantes() > 0)
                                <small class="text-muted">({{ $mobiliario_renta->diasRestantes() }} días restantes)</small>
                            @else
                                <small class="text-danger">(¡Vencida!)</small>
                            @endif
                        @endif
                    </td>
                </tr>
                @if($mobiliario_renta->fecha_devolucion)
                <tr>
                    <th>Fecha Devolución:</th>
                    <td>{{ $mobiliario_renta->fecha_devolucion->format('d/m/Y') }}</td>
                </tr>
                @endif
                <tr>
                    <th>Duración:</th>
                    <td>{{ $mobiliario_renta->duracionDias() }} días ({{ $mobiliario_renta->duracionMeses() }} meses aprox.)</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <h6 class="text-primary">Información de Registro</h6>
            <table class="table table-sm">
                <tr>
                    <th width="20%">Fecha de Registro:</th>
                    <td>{{ $mobiliario_renta->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @if($mobiliario_renta->updated_at != $mobiliario_renta->created_at)
                <tr>
                    <th>Última Actualización:</th>
                    <td>{{ $mobiliario_renta->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    @if($mobiliario_renta->esta_activa)
        <a href="{{ route('mobiliario_renta.edit', $mobiliario_renta->id_renta) }}" 
           class="btn btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
    @endif
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
</div>