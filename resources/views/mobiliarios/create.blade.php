@extends('layouts.admin')

@section('title', 'Nuevo Mobiliario')
@section('content')
<div class="container-fluid">

    <h2>Ingresar Mobiliario</h2>

    <a href="{{ route('mobiliarios.store') }}" class="btn btn-default mb-3">
        ← Regresar
    </a>

    <div class="panel panel-default">

        <div class="panel-heading" style="background:#444;color:white">
            Registro de mobiliario
        </div>

        <div class="panel-body">

            {{-- ERRORES --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('mobiliarios.store') }}">
                @csrf

                <div class="row">

                    {{-- ARTÍCULO --}}
                    <div class="col-md-6 form-group">
                        <label class="text-danger">Artículo</label>
                        <input type="text"
                               name="nombre_mobiliario"
                               value="{{ old('nombre_mobiliario') }}"
                               class="form-control"
                               required>
                    </div>

                    {{-- CLAVE INVENTARIO --}}
                    <div class="col-md-6 form-group">
                        <label>Clave inventario</label>
                        <input type="text"
                               name="clave_inventario"
                               value="{{ old('clave_inventario') }}"
                               class="form-control">
                    </div>

                    {{-- MARCA --}}
                    <div class="col-md-4 form-group">
                        <label class="text-danger">Marca</label>
                        <select name="id_marcafk" class="form-control" required>
                            <option value="">Seleccione una opción</option>
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca->id }}"
                                    {{ old('id_marcafk') == $marca->id ? 'selected' : '' }}>
                                    {{ $marca->nombre_marca }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- MODELO --}}
                    <div class="col-md-4 form-group">
                        <label class="text-danger">Modelo</label>
                        <select name="id_modelofk" class="form-control" required>
                            <option value="">Seleccione una opción</option>
                            @foreach ($modelos as $modelo)
                                <option value="{{ $modelo->id }}"
                                    {{ old('id_modelofk') == $modelo->id ? 'selected' : '' }}>
                                    {{ $modelo->nombre_modelo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ÁREA ASIGNACIÓN --}}
                    <div class="col-md-4 form-group">
                        <label class="text-danger">Área de asignación</label>
                        <select name="fk_asignacion" class="form-control" required>
                            <option value="">Seleccione una opción</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id_asignacion }}"
                                    {{ old('fk_asignacion') == $area->id_asignacion ? 'selected' : '' }}>
                                    {{ $area->nombre_asignacion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- NÚMERO DE SERIE --}}
                    <div class="col-md-6 form-group">
                        <label>Número de serie</label>
                        <input type="text"
                               name="num_serie"
                               value="{{ old('num_serie') }}"
                               class="form-control">
                    </div>

                    {{-- PROVEEDOR 
                    <div class="col-md-6 form-group">
                        <label>Proveedor</label>
                        <select name="fk_provedor" class="form-control">
                            <option value="">Seleccione una opción</option>
                            @foreach ($proveedor as $prov)
                                <option value="{{ $prov->id_prov }}"
                                    {{ old('fk_provedor') == $prov->id_prov ? 'selected' : '' }}>
                                    {{ $prov->nombre_prov }}
                                </option>
                            @endforeach
                        </select>
                    </div>--}}

                </div>

                <hr>

                <div class="text-right">
                    <button type="submit" class="btn btn-danger">
                        Guardar
                    </button>
                    <a href="{{ route('mobiliarios.index') }}" class="btn btn-default">
                        Cancelar
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
