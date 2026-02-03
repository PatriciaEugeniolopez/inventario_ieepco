@extends('layouts.admin')

@section('title', 'Editar Marca')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                
                <div class="card-header text-center btn-registrar">
                    <i class="fas fa-edit"></i>Editar Marca
                </div>

                <div class="card-body ">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <form action="{{ route('marca.update', $marca->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label>Nombre de la Marca</label>
                                    <input type="text"
                                        name="nombre_marca"
                                        class="form-control"
                                        value="{{ old('nombre_marca', $marca->nombre_marca) }}"
                                        required>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('marca.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Cancelar
                                    </a>
                                    <button class="btn btn-registrar">
                                        <i class="fas fa-save"></i> Actualizar
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



</div>
@endsection