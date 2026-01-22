@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="form-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Editar Empleado</h2>
            <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
                Volver
            </a>
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

        <form action="{{ route('empleados.update', $empleado->id_empleado) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre_empleado" class="form-label">Nombre *</label>
                    <input type="text" 
                           class="form-control @error('nombre_empleado') is-invalid @enderror" 
                           id="nombre_empleado" 
                           name="nombre_empleado" 
                           value="{{ old('nombre_empleado', $empleado->nombre_empleado) }}" 
                           placeholder="Ej: EMANUEL"
                           required>
                    @error('nombre_empleado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="apellido_p" class="form-label">Apellido Paterno *</label>
                    <input type="text" 
                           class="form-control @error('apellido_p') is-invalid @enderror" 
                           id="apellido_p" 
                           name="apellido_p" 
                           value="{{ old('apellido_p', $empleado->apellido_p) }}" 
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
                           value="{{ old('apellido_m', $empleado->apellido_m) }}"
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
                           value="{{ old('puesto', $empleado->puesto) }}"
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
                            <option value="{{ $area->id_asignacion }}" 
                                {{ old('fk_area_trabajo', $empleado->fk_area_trabajo) == $area->id_asignacion ? 'selected' : '' }}>
                                {{ $area->nombre_asignacion }}
                            </option>
                        @endforeach
                    </select>
                    @error('fk_area_trabajo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-actualizar">
                    <i class="fas fa-save me-2"></i> Actualizar Empleado
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-container {
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .btn-actualizar {
        background-color: #8B1538;
        color: white;
        border: none;
        padding: 10px 30px;
    }
    
    .btn-actualizar:hover {
        background-color: #6d0f2a;
        color: white;
    }
    
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    
    .form-control, .form-select {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 10px 15px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #8B1538;
        box-shadow: 0 0 0 0.2rem rgba(139, 21, 56, 0.25);
    }
</style>
@endsection