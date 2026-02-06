@extends('layouts.admin')
@section('title', 'Registro de Mobiliario de Renta')

@section('content')
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div>
                    <div class="card-header navbar navbar-expand-lg navbar-dark bg-dark container-fluid justify-content-center">
                        <h2 class="text-white">Ingresar Nuevo Mobiliario de renta</h2>
                    </div>

                    <form method="POST" action="{{ route('mobiliario_renta.store') }}">
                        @csrf

                        {{-- Selección de mobiliario --}}
                        <div class="form-group">
                            <label>Mobiliario</label>
                            <select id="mobiliario_id" name="id_mobiliario" class="form-control" required>
                                <option value="">Seleccione un mobiliario</option>
                                @foreach($mobiliarios as $m)
                                <option value="{{ $m->id_mobiliario }}"
                                    data-nombre="{{ $m->nombre_mobiliario }}"
                                    data-modelo="{{ $m->modelo->nombre ?? '' }}"
                                    data-marca="{{ $m->marca->nombre ?? '' }}"
                                    data-proveedor="{{ $m->proveedor->nombre_prov ?? '' }}">
                                    {{ $m->nombre_mobiliario }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Datos automáticos --}}
                        <div class="row">
                            <div class="col-md-3">
                                <label>Modelo</label>
                                <input type="text" id="modelo" class="form-control" readonly>
                            </div>
                            <div class="col-md-3">
                                <label>Marca</label>
                                <input type="text" id="marca" class="form-control" readonly>
                            </div>
                            <div class="col-md-3">
                                <label>Proveedor</label>
                                <input type="text" id="proveedor" class="form-control" readonly>
                            </div>
                            <div class="col-md-3">
                                <label>Área asignada</label>
                                <input type="text" name="area_asignada" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-3">
                                <label>Cantidad</label>
                                <input type="number" name="cantidad" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <label>Número de serie</label>
                                <input type="text" name="numero_serie" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>Fecha inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Fecha fin</label>
                                <input type="date" name="fecha_fin" class="form-control" required>
                            </div>
                        </div>

                        <br>

                        <button class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar Renta
                        </button>
                        <a href="{{ route('mobiliario_renta.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script>
        document.getElementById('mobiliario_id').addEventListener('change', function() {
            let option = this.options[this.selectedIndex];

            document.getElementById('modelo').value = option.dataset.modelo || '';
            document.getElementById('marca').value = option.dataset.marca || '';
            document.getElementById('proveedor').value = option.dataset.proveedor || '';
        });
    </script>
    @endpush