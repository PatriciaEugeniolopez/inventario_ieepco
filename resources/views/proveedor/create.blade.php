@extends('layouts.admin')

@section('title', 'Registro de Proveedores')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header navbar navbar-expand-lg navbar-dark bg-dark container-fluid justify-content-center">
                    <h3 class="text-white">Registrar Proveedor</h3>
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
                    <form action="{{ route('proveedor.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre_prov" class="form-label">Nombre del Proveedor</label>
                                <input type="text"
                                    class="form-control @error('nombre_prov') is-invalid @enderror"
                                    id="nombre_prov"
                                    name="nombre_prov"
                                    value="{{ old('nombre_prov') }}"
                                    required>
                                @error('nombre_prov')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="rfc" class="form-label">RFC</label>
                                <input type="text"
                                    class="form-control @error('rfc') is-invalid @enderror"
                                    id="rfc"
                                    name="rfc"
                                    value="{{ old('rfc') }}"
                                    required>
                                @error('rfc')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="calle" class="form-label">Calle</label>
                                <input type="text"
                                    class="form-control @error('calle') is-invalid @enderror"
                                    id="calle"
                                    name="calle"
                                    value="{{ old('calle') }}"
                                    required>
                                @error('calle')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="numero_ext" class="form-label">Número Ext</label>
                                <input type="number"
                                    class="form-control @error('numero_ext') is-invalid @enderror"
                                    id="numero_ext"
                                    name="numero_ext"
                                    value="{{ old('numero_ext') }}">
                                @error('numero_ext')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="numero_int" class="form-label">Número Int</label>
                                <input type="number"
                                    class="form-control @error('numero_int') is-invalid @enderror"
                                    id="numero_int"
                                    name="numero_int"
                                    value="{{ old('numero_int') }}">
                                @error('numero_int')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    id="telefono"
                                    name="telefono"
                                    value="{{ old('telefono', $proveedor->telefono ?? '') }}"
                                    required>
                                @error('colonia')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>


                            <div class="col-md-6">
                                <label for="colonia" class="form-label">Colonia</label>
                                <input type="text"
                                    class="form-control @error('colonia') is-invalid @enderror"
                                    id="colonia"
                                    name="colonia"
                                    value="{{ old('colonia') }}"
                                    required>
                                @error('colonia')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="municipio" class="form-label">Municipio</label>
                                <input type="text"
                                    class="form-control @error('municipio') is-invalid @enderror"
                                    id="municipio"
                                    name="municipio"
                                    value="{{ old('municipio') }}"
                                    required>
                                @error('municipio')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="estado" class="form-label">Estado</label>
                                <input type="text"
                                    class="form-control @error('estado') is-invalid @enderror"
                                    id="estado"
                                    name="estado"
                                    value="{{ old('estado') }}"
                                    required>
                                @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="pais" class="form-label">País</label>
                                <input type="text"
                                    class="form-control @error('pais') is-invalid @enderror"
                                    id="pais"
                                    name="pais"
                                    value="{{ old('pais', 'México') }}"
                                    required>
                                @error('pais')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="codigo_postal" class="form-label">Código Postal</label>
                                <input type="number"
                                    class="form-control @error('codigo_postal') is-invalid @enderror"
                                    id="codigo_postal"
                                    name="codigo_postal"
                                    value="{{ old('codigo_postal') }}">
                                @error('codigo_postal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('proveedor.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver

                            </a>
                            <button type="submit" class="btn btn-registrar">
                                <i class="fas fa-save"></i> Guardar Proveedor
                            </button>

                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection