@extends('layouts.admin')

@section('title', 'Registro de empleados')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header navbar navbar-expand-lg navbar-dark bg-dark container-fluid justify-content-center">
                    <h3 class="text-white">Registrar Empleado</h3>
                </div>

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="card-body">
                    <form action="{{ route('empleados.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="nombre_empleado" class="form-label">Nombre</label>
                                <input type="text"
                                    class="form-control @error('nombre_empleado') is-invalid @enderror"
                                    id="nombre_empleado"
                                    name="nombre_empleado"
                                    value="{{ old('nombre_empleado') }}"
                                    placeholder="Ej: EMANUEL"
                                    required>
                                @error('nombre_empleado')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="apellido_p" class="form-label">Apellido Paterno</label>
                                <input type="text"
                                    class="form-control @error('apellido_p') is-invalid @enderror"
                                    id="apellido_p"
                                    name="apellido_p"
                                    value="{{ old('apellido_p') }}"
                                    placeholder="Ej: MORALES"
                                    required>
                                @error('apellido_p')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="apellido_m" class="form-label">Apellido Materno</label>
                                <input type="text"
                                    class="form-control @error('apellido_m') is-invalid @enderror"
                                    id="apellido_m"
                                    name="apellido_m"
                                    value="{{ old('apellido_m') }}"
                                    placeholder="Ej: ZARATE">
                                @error('apellido_m')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="puesto" class="form-label">Puesto</label>
                                <input type="text"
                                    class="form-control @error('puesto') is-invalid @enderror"
                                    id="puesto"
                                    name="puesto"
                                    value="{{ old('puesto') }}"
                                    placeholder="Ej: AUXILIAR ADMINISTRATIVO">
                                @error('puesto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="fk_area_trabajo" class="form-label">Área de Asignación</label>
                                <select class="form-select @error('fk_area_trabajo') is-invalid @enderror"
                                    id="fk_area_trabajo"
                                    name="fk_area_trabajo">
                                    <option value="">Seleccione una opción</option>
                                    @foreach($areas as $area)
                                    <option value="{{ $area->id_asignacion }}" {{ old('fk_area_trabajo') == $area->id_asignacion ? 'selected' : '' }}>
                                        {{ $area->nombre_asignacion }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('fk_area_trabajo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>

                        <div class="d-flex gap-2 justify-content-between">

                                <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                          
                            <button type="submit" class="btn btn-registrar">
                                <i class="fas fa-save"></i> Guardar Empleado
                            </button>


                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection