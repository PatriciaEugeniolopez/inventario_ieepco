@extends('layouts.admin')

@section('title', 'Detalle de Marca')

@section('content')
<div class="container mt-4">

    <h2>Detalle de Marca</h2>

    <div class="panel panel-default">
        <div class="panel-body">


            <form action="{{ route('marca.update', $marca->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nombre de la Marca</label>
                    <input type="text"
                           name="nombre_marca"
                           class="form-control"
                           value="{{ old('nombre_marca', $marca->nombre_marca) }}"
                           required>
                </div>

                <div class="form-group text-right">
                    <a href="{{ route('marca.index') }}" class="btn btn-default">
                        Regresar
                    </a>
                    <button class="btn btn-primary">
                        Guardar Cambios
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
