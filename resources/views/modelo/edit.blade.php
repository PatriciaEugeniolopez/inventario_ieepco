@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card shadow-sm">
                    <div class="card-header text-center btn-registrar text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-edit"></i> Editar Modelo
                        </h5>
                    </div>

                    <div class="card-body">

                        {{-- Errores --}}
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('modelo.update', $modelo->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Nombre del modelo --}}
                            <div class="mb-3">
                                <label class="form-label">Nombre del modelo</label>
                                <input type="text"
                                    name="nombre_modelo"
                                    class="form-control"
                                    value="{{ old('nombre_modelo', $modelo->nombre_modelo) }}"
                                    required>
                            </div>

                            {{-- Marca --}}
                            <div class="mb-3">
                                <label class="form-label">Marca</label>
                                <select name="fk_marca" class="form-select" required>
                                    <option value="">Seleccione una marca</option>
                                    @foreach ($marcas as $marca)
                                    <option value="{{ $marca->id }}"
                                        {{ $modelo->fk_marca == $marca->id ? 'selected' : '' }}>
                                        {{ $marca->nombre_marca }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Estatus --}}
                            <div class="mb-3">
                                <label class="form-label">Estatus</label>
                                <select name="status" class="form-select">
                                    <option value="1" {{ $modelo->status == 1 ? 'selected' : '' }}>
                                        Activo
                                    </option>
                                    <option value="0" {{ $modelo->status == 0 ? 'selected' : '' }}>
                                        Inactivo
                                    </option>
                                </select>
                            </div>

                            {{-- Botones --}}
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('modelo.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>

                                <button type="submit" class="btn btn-registrar">
                                    <i class="fas fa-save"></i> Guardar cambios
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection