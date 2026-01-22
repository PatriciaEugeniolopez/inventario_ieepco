@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Editar Usuario</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="fk_idempleado" class="form-label">ID Empleado</label>
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

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de Usuario</label>
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

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
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

                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña (dejar en blanco para mantener la actual)</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password">
                            <small class="form-text text-muted">Solo completa este campo si deseas cambiar la contraseña</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection