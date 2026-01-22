@extends('layouts.admin')

@section('content')
<div class="container">

    <h3>Nuevo Modelo</h3>

    <form method="POST" action="{{ route('modelo.store') }}">
        @csrf

        <div class="form-group">
            <label>Nombre Modelo</label>
            <input type="text" name="nombre_modelo" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Marca</label>
            <select name="fk_marca" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach($marcas as $marca)
                    <option value="{{ $marca->id }}">{{ $marca->nombre_marca }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-danger">Guardar</button>
        <a href="{{ route('modelo.index') }}" class="btn btn-default">Cancelar</a>

    </form>
</div>
@endsection
