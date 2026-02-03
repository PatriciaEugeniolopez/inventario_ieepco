@extends('layouts.admin')
@section('title', 'Editar Usuario')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header navbar navbar-expand-lg navbar-dark bg-dark container-fluid justify-content-center">
                    <h2 class="text-white">Editar Usuario</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del empleado *</label>
                                    <input type="text"
                                        class="form-control @error('nombre') is-invalid @enderror"
                                        id="nombre"
                                        name="nombre"
                                        value="{{ old('nombre', $usuario->nombre) }}"
                                        required>
                                    @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fk_idempleado" class="form-label">ID Empleado *</label>
                                    <input type="number"
                                        class="form-control @error('fk_idempleado') is-invalid @enderror"
                                        id="fk_idempleado"
                                        name="fk_idempleado"
                                        value="{{ old('fk_idempleado', $usuario->fk_idempleado) }}"
                                        required>
                                    @error('fk_idempleado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico *</label>
                            <input type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email', $usuario->email) }}"
                                required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        Nueva Contraseña 
                                        <small class="text-muted">(dejar en blanco para mantener actual)</small>
                                    </label>
                                    <input type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password">
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Repetir Contraseña</label>
                                    <input type="password"
                                        class="form-control"
                                        id="password_confirmation"
                                        name="password_confirmation">
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
                                @php
                                    $userRoles = $usuario->getRoleNames();
                                @endphp
                                
                                @forelse($roles as $role)
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="roles[]"
                                           value="{{ $role->name }}"
                                           id="role_{{ $role->name }}"
                                           {{ in_array($role->name, old('roles', $userRoles)) ? 'checked' : '' }}>
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
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection