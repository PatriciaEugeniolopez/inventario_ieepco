@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col">
            <h2>Editar Renta #{{ $mobiliario_renta->id_renta }}</h2>
        </div>
        <div class="col-auto">
            <a href="{{ route('mobiliario_renta.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('mobiliario_renta.update', $mobiliario_renta->id_renta) }}">
                @csrf
                @method('PUT')

                {{-- Información del mobiliario (solo lectura) --}}
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <strong>Mobiliario:</strong> {{ $mobiliario_renta->mobiliario->nombre_mobiliario }}<br>
                            <strong>Modelo:</strong> {{ $mobiliario_renta->mobiliario->modelo->nombre ?? 'N/A' }}<br>
                            <strong>Marca:</strong> {{ $mobiliario_renta->mobiliario->marca->nombre ?? 'N/A' }}<br>
                            <strong>Proveedor:</strong> {{ $mobiliario_renta->mobiliario->proveedor->nombre_prov ?? 'N/A' }}<br>
                            <strong>Fecha Inicio:</strong> {{ $mobiliario_renta->fecha_inicio->format('d/m/Y') }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Cantidad --}}
                    <div class="col-md-4 mb-3">
                        <label for="cantidad" class="form-label">Cantidad <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad" id="cantidad" 
                               class="form-control @error('cantidad') is-invalid @enderror" 
                               value="{{ old('cantidad', $mobiliario_renta->cantidad) }}" 
                               min="1" required>
                        @error('cantidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Fecha Fin --}}
                    <div class="col-md-4 mb-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_fin" id="fecha_fin" 
                               class="form-control @error('fecha_fin') is-invalid @enderror" 
                               value="{{ old('fecha_fin', $mobiliario_renta->fecha_fin->format('Y-m-d')) }}" 
                               required>
                        @error('fecha_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Duración calculada --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Duración</label>
                        <input type="text" class="form-control bg-light" id="duracionCalculada" readonly>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('mobiliario_renta.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInicio = '{{ $mobiliario_renta->fecha_inicio->format("Y-m-d") }}';
    const fechaFin = document.getElementById('fecha_fin');
    const duracionCalculada = document.getElementById('duracionCalculada');

    function calcularDuracion() {
        if (fechaFin.value) {
            const inicio = new Date(fechaInicio);
            const fin = new Date(fechaFin.value);
            
            if (fin > inicio) {
                const diffTime = Math.abs(fin - inicio);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const meses = Math.ceil(diffDays / 30);
                
                duracionCalculada.value = `${diffDays} días (aprox. ${meses} mes${meses > 1 ? 'es' : ''})`;
            }
        }
    }

    // Calcular al cargar
    calcularDuracion();

    fechaFin.addEventListener('change', calcularDuracion);
});
</script>
@endpush
@endsection