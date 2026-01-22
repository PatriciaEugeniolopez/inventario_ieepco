@extends('layouts.app')

@section('title', 'Login')

@section('content')

<div class="row">
    <div class="col-sm-4">
        <img src="{{ asset('imagenes/iepcologo.png') }}" class="img-responsive">
    </div>

    <div class="col-sm-5">
        <h2>Ingresar al sistema</h2>
        <p>Complete los campos para ingresar al sistema:</p>

        @if ($errors->any())
            <p class="text-danger">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button class="btn btn-primary">Entrar</button>
        </form>
    </div>
</div>

@endsection
