@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="container mt-5">
    <div class="row align-items-center">
        <!-- Logo a la izquierda -->
        <div class="col-md-4 text-center">
            <img src="{{ asset('imagenes/iepcologo.png') }}" class="img-fluid" style="max-width: 350px;">
        </div>
        
        <!-- Formulario a la derecha -->
        <div class="col-md-4">
            <h2>Ingresar al sistema</h2>
            <p>Complete los campos para ingresar al sistema:</p>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}" style="max-width: 400px;">
                @csrf
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-registrar">Entrar</button>
            </form>
        </div>
    </div>
</div>
@endsection