@extends('layouts.admin')
@section('title', 'Registro de Usuarios')

@section('content')
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header navbar navbar-expand-lg navbar-dark bg-dark container-fluid justify-content-center">
                    <h2 class="text-white">Ingresar Nuevo Usuario</h2>
                </div>
                <div class="card-body">
                    <h6>Por favor requisite los siguientes campos para registrar un usuario:</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('usuarios.store') }}" method="POST">
                        @csrf
                        
                        <!-- SELECCIONAR EMPLEADO CON SELECT2 -->
                        <div class="mb-3">
                            <label for="fk_idempleado" class="form-label">
                                <i class="fas fa-user"></i> Seleccionar Empleado
                            </label>
                            <select class="form-select select2-empleados @error('fk_idempleado') is-invalid @enderror" 
                                    id="fk_idempleado" 
                                    name="fk_idempleado" 
                                    required>
                                <option value="">-- Seleccione un empleado --</option>
                                @foreach($empleados as $empleado)
                                    <option value="{{ $empleado->id_empleado }}" 
                                            {{ old('fk_idempleado') == $empleado->id_empleado ? 'selected' : '' }}
                                            data-puesto="{{ $empleado->puesto }}"
                                            data-area="{{ $empleado->areaAsignacion->nombre_asignacion ?? '' }}">
                                        {{ $empleado->nombre_completo }}
                                        @if($empleado->puesto) - {{ $empleado->puesto }}@endif
                                        @if($empleado->areaAsignacion) ({{ $empleado->areaAsignacion->nombre_asignacion }})@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('fk_idempleado')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            @if($empleados->isEmpty())
                            <div class="alert alert-warning mt-2">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay empleados disponibles sin usuario asignado. 
                                <a href="{{ route('empleados.create') }}" class="alert-link">Crear un empleado primero</a>
                            </div>
                            @else
                            <small class="text-muted">Escriba para buscar por nombre, puesto o área</small>
                            @endif
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">
                                        <i class="fas fa-user-circle"></i> Nombre de Usuario
                                    </label>
                                    <input type="text"
                                        class="form-control @error('nombre') is-invalid @enderror"
                                        id="nombre"
                                        name="nombre"
                                        value="{{ old('nombre') }}"
                                        placeholder="Ej: juan.perez"
                                        required>
                                    @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Este será el nombre para iniciar sesión</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope"></i> Correo Electrónico
                                    </label>
                                    <input type="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="ejemplo@correo.com"
                                        required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock"></i> Contraseña
                                    </label>
                                    <input type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password"
                                        required>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Mínimo 6 caracteres</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="fas fa-lock"></i> Repetir Contraseña
                                    </label>
                                    <input type="password"
                                        class="form-control"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        required>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- SECCIÓN DE ROLES -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user-tag"></i> Roles del Usuario
                            </label>
                            <small class="text-muted d-block mb-2">Seleccione uno o más roles para este usuario</small>
                            
                            <div class="border rounded p-3 bg-light">
                                @forelse($roles as $role)
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="roles[]"
                                           value="{{ $role->name }}"
                                           id="role_{{ $role->name }}"
                                           {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role_{{ $role->name }}">
                                        <strong>{{ ucfirst($role->name) }}</strong>
                                        @if($role->description)
                                        <br><small class="text-muted">{{ $role->description }}</small>
                                        @endif
                                    </label>
                                </div>
                                @empty
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    No hay roles disponibles.
                                </div>
                                @endforelse
                            </div>
                            
                            @error('roles')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="d-flex gap-2 justify-content-between">
                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-registrar" {{ $empleados->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-save"></i> Guardar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Inicializar Select2 en el select de empleados
    $('.select2-empleados').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '-- Seleccione un empleado --',
        allowClear: true,
        language: {
            noResults: function() {
                return "No se encontraron empleados";
            },
            searching: function() {
                return "Buscando...";
            }
        }
    });
});
</script>
@endsection