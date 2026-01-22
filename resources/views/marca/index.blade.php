@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <h2>Marcas</h2>

    <a href="{{ route('marca.create') }}" class="btn btn-danger mb-3">
        Ingresar Marca
    </a>

    <div class="panel panel-default">

        {{-- Barra superior gris --}}
        <div class="panel-heading" style="background:#444;color:white">
            <div class="pull-right">
                Mostrando {{ $marcas->firstItem() }}-{{ $marcas->lastItem() }}
                de {{ $marcas->total() }} elementos.
            </div>
            <div class="clearfix"></div>
        </div>

        <form method="GET">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>
                            <span class="text-danger">Nombre Marca</span>
                            <input type="text"
                                   name="nombre_marca"
                                   value="{{ request('nombre_marca') }}"
                                   class="form-control">
                        </th>
                        <th style="width:120px">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($marcas as $i => $marca)
                    <tr>
                        <td>{{ $marcas->firstItem() + $i }}</td>
                        <td>{{ $marca->nombre_marca }}</td>
                        <td class="text-center">

                            {{-- Ver (opcional) --}}
                            <a href="#" class="text-danger">
                                <i class="glyphicon glyphicon-eye-open"></i>
                            </a>

                            {{-- Editar --}}
                            <a href="{{ route('marca.edit',$marca->id) }}" class="text-danger">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </a>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </form>

        <div class="panel-footer text-center">
            {{ $marcas->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection
