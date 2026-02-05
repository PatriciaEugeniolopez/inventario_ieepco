@extends('layouts.admin')

@section('title', 'Nuevo Modelo')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">

                <div class="card-header navbar navbar-expand-lg navbar-dark bg-dark container-fluid justify-content-center">
                    <h2 class="text-white">Nuevo Modelo</h2>
                </div>


                <div class="card-body">
                    <form method="POST" action="{{ route('modelo.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label>Marca</label>
                            <select name="fk_marca" class="form-control" required>
                                <option value="">Seleccione</option>
                                @foreach($marcas as $marca)
                                <option value="{{ $marca->id }}">{{ $marca->nombre_marca }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Nombre Modelo</label>
                            <input type="text" name="nombre_modelo" class="form-control" required>
                        </div>

                        <button class="btn btn-registrar">Guardar</button>
                        <a href="{{ route('modelo.index') }}" class="btn btn-secondary">Cancelar</a>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection