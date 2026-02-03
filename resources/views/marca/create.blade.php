@extends('layouts.admin')

@section('title', 'Nueva Marca')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">

                <div class="card-header navbar navbar-expand-lg navbar-dark bg-dark container-fluid justify-content-center">
                    <h3 class="text-white">Nueva Marca</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('marca.store') }}">
                    @csrf

                    <div class="mb-5">
                        </br>
                        <label>Nombre Marca</label>
                        <input type="text" name="nombre_marca" class="form-control" required>
                    </div>

                    <button class="btn btn-registrar">Guardar</button>
                    <a href="{{ route('marca.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
                </div>


                
            </div>


        </div>

    </div>



</div>
@endsection