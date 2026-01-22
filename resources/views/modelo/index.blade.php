@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <h2>Modelos</h2>

    <a href="{{ route('modelo.create') }}" class="btn btn-danger mb-3">
        Ingresar Modelo
    </a>

    <div class="panel panel-default">

        <div class="panel-heading" style="background:#444;color:white">
            <div class="pull-right">
                Mostrando {{ $modelos->firstItem() }}-{{ $modelos->lastItem() }}
                de {{ $modelos->total() }} elementos.
            </div>
            <div class="clearfix"></div>
        </div>

        <form method="GET">
            <table class="table table-bordered table-hover">

                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>
                            <span class="text-danger">Nombre Modelo</span>
                            <input type="text" name="nombre_modelo"
                                   value="{{ request('nombre_modelo') }}"
                                   class="form-control">
                        </th>
                        <th>
                            <span class="text-danger">Marca</span>
                            <select name="fk_marca" class="form-control">
                                <option value="">Seleccione una opción</option>
                                @foreach($marcas as $marca)
                                    <option value="{{ $marca->id }}"
                                        {{ request('fk_marca') == $marca->id ? 'selected' : '' }}>
                                        {{ $marca->nombre_marca }}
                                    </option>
                                @endforeach
                            </select>
                        </th>
                        <th style="width:120px">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($modelos as $i => $modelo)
                    <tr>
                        <td>{{ $modelos->firstItem() + $i }}</td>
                        <td>{{ $modelo->nombre_modelo }}</td>
                        <td>{{ $modelo->marca->nombre_marca ?? '' }}</td>
                        <td class="text-center">
                            <a href="#" class="text-danger">
                                <i class="glyphicon glyphicon-eye-open"></i>
                            </a>
                            <a href="{{ route('modelo.edit',$modelo->id) }}" class="text-danger">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </form>

        <div class="panel-footer text-center">
            {{ $modelos->appends(request()->query())->links() }}
        </div>

    </div>
</div>
@endsection
