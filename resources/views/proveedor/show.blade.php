<div class="modal-header bg-success text-white">
    <h5 class="modal-title">Detalle del Proveedor</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <p><strong>Nombre:</strong> {{ $proveedor->nombre_prov }}</p>
    <p><strong>RFC:</strong> {{ $proveedor->rfc }}</p>
    <p><strong>Dirección:</strong>
        {{ $proveedor->calle }} #{{ $proveedor->numero_ext }},
        {{ $proveedor->colonia }}
    </p>
    <p><strong>Municipio:</strong> {{ $proveedor->municipio }}</p>
    <p><strong>Estado:</strong> {{ $proveedor->estado }}</p>
    <p><strong>Código Postal:</strong> {{ $proveedor->codigo_postal }}</p>
</div>

<div class="modal-footer">
    <button class="btn btn-secondary" data-bs-dismiss="modal">
        Cerrar
    </button>
</div>
