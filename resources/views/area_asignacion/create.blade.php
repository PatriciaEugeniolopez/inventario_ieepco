@extends('layouts.admin')

@section('title', 'Registrar Área de Asignación')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">

                <div class="card-header navbar navbar-expand-lg navbar-dark bg-dark container-fluid justify-content-center">
                    <h2 class="text-white">Nueva Área de Asignación</h2>
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
                    <form method="POST" action="{{ route('area_asignacion.store') }}" >
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="nombre_asignacion" class="form-label">Nombre de Área </label>
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

                        <div class="d-flex gap-2 justify-content-between">
                            <a href="{{ route('area_asignacion.index') }}" class="btn btn-secondary"> 
                                <i class="fas fa-arrow-left"></i> Cancelar 
                            </a>

                            <button type="submit" class="btn btn-registrar">
                                <i class="fas fa-save"></i> Guardar Área
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection