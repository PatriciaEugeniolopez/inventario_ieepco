@extends('layouts.admin')

@section('content')
<div class="container">

    <h3>Nueva Marca</h3>

    <form method="POST" action="{{ route('marca.store') }}">
        @csrf

        <div class="form-group">
            <label>Nombre Marca</label>
            <input type="text" name="nombre_marca" class="form-control" required>
        </div>

        <button class="btn btn-danger">Guardar</button>
        <a href="{{ route('marca.index') }}" class="btn btn-default">Cancelar</a>
    </form>

</div>
@endsection
