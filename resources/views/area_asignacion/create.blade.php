@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="form-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Nueva Área de Asignación</h2>
            <a href="{{ route('area_asignacion.index') }}" class="btn btn-secondary">
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

        <form action="{{ route('area_asignacion.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="nombre_asignacion" class="form-label">Nombre de Área *</label>
                    <input type="text" 
                           class="form-control @error('nombre_asignacion') is-invalid @enderror" 
                           id="nombre_asignacion" 
                           name="nombre_asignacion" 
                           value="{{ old('nombre_asignacion') }}" 
                           placeholder="Ej: DIRECCIÓN EJECUTIVA DE EDUCACIÓN CÍVICA Y PARTICIPACIÓN CIUDADANA"
                           required>
                    @error('nombre_asignacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="responsable_area" class="form-label">Responsable del Área</label>
                    <input type="text" 
                           class="form-control @error('responsable_area') is-invalid @enderror" 
                           id="responsable_area" 
                           name="responsable_area" 
                           value="{{ old('responsable_area') }}"
                           placeholder="Ej: E.D. LIC. PEDRO CELIS MENDOZA">
                    @error('responsable_area')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-guardar">
                    <i class="fas fa-save me-2"></i> Guardar Área
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
    
    .btn-guardar {
        background-color: #8B1538;
        color: white;
        border: none;
        padding: 10px 30px;
    }
    
    .btn-guardar:hover {
        background-color: #6d0f2a;
        color: white;
    }
    
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    
    .form-control {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 10px 15px;
    }
    
    .form-control:focus {
        border-color: #8B1538;
        box-shadow: 0 0 0 0.2rem rgba(139, 21, 56, 0.25);
    }
</style>
@endsection