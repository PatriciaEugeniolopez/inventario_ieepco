@extends('layouts.admin')

@section('title', 'Mobiliario')
@section('content')
<div class="container mt-4">

    <h2>Mobiliarios</h2>

    <a href="{{ route('mobiliarios.create') }}" class="btn btn-registrar">
        Ingresar Mobiliario
    </a>

    <div class="panel panel-default">

        <p class="text-muted">
            Mostrando {{ $mobiliarios->firstItem() }}-{{ $mobiliarios->lastItem() }}
            de {{ $mobiliarios->total() }} elementos.
        </p>



        <div class="table-responsive">
            <table class="table table-bordered table-hover tabla-usuarios-small">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>

                        <form method="GET" action="{{ route('mobiliarios.index') }}" id="filtroForm">
                            {{-- Artículo --}}
                            <th>
                                <span class="width:">Equipo</span>
                                <input type="text"
                                    name="nombre_mobiliario"
                                    value="{{ request('nombre_mobiliario') }}"
                                    class="form-control"
                                    placeholder="Buscar Equipo"
                                    onchange="document.getElementById('filtroForm').submit()">
                            </th>
                            <th>
                                <span class="width:">Serie</span>
                                <input type="text"
                                    name="num_serie"
                                    value="{{ request('num_serie') }}"
                                    class="form-control"
                                    placeholder="Buscar Serie"
                                    onchange="document.getElementById('filtroForm').submit()">
                            </th>

                            {{-- Área --}}
                            <th>Área de Asignación
                                <select name="fk_asignacion" 
                                class="form-control"
                                onchange="document.getElementById('filtroForm').submit()">
                                    <option value="">Seleccione una opción</option>
                                    @foreach ($areas as $area)
                                    <option value="{{ $area->id_asignacion }}"
                                        {{ request('fk_asignacion') == $area->id_asignacion ? 'selected' : '' }}>
                                        {{ $area->nombre_asignacion }}
                                    </option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <span class="width:">Usuario</span>
                                <input type="text"
                                    name="responsable_area"
                                    value="{{ request('responsable_area') }}"
                                    class="form-control">
                            </th>

                            {{-- Marca --}}
                            <th>
                                <span class="width:">Marca</span>
                                <select name="id_marcafk" 
                                class="form-control"
                                onchange="document.getElementById('filtroForm').submit()">
                                    <option value="">Seleccione una opción</option>
                                    @foreach ($marcas as $marca)
                                    <option value="{{ $marca->id }}"
                                        {{ request('id_marcafk') == $marca->id ? 'selected' : '' }}>
                                        {{ $marca->nombre_marca }}
                                    </option>
                                    @endforeach
                                </select>
                            </th>

                            {{-- Modelo --}}
                            <th>
                                <span class="width:">Modelo</span>
                                <select name="id_modelofk" 
                                class="form-control"
                                onchange="document.getElementById('filtroForm').submit()">
                                    <option value="">Seleccione una opción</option>
                                    @foreach ($modelos as $modelo)
                                    <option value="{{ $modelo->id }}"
                                        {{ request('id_modelofk') == $modelo->id ? 'selected' : '' }}>
                                        {{ $modelo->nombre_modelo }}
                                    </option>
                                    @endforeach
                                </select>
                            </th>
                        </form>
                        <th>Cantidad</th>

                        <th style="width:140px">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($mobiliarios as $i => $mob)
                    <tr>
                        <td>{{ $mobiliarios->firstItem() + $i }}</td>
                        <td>{{ $mob->nombre_mobiliario ?? ''}}</td>
                        <td>{{ $mob->num_serie ?? ''}}</td>
                        <td>{{ $mob->areaAsignacion->nombre_asignacion ?? '' }}</td>
                        <td>{{ $mob->areaAsignacion->responsable_area ?? '' }}</td>
                        <td>{{ $mob->marca->nombre_marca ?? '' }}</td>
                        <td>{{ $mob->modelo->nombre_modelo ?? '' }}</td>
                        <td>{{ $mob->cantidad_total }}</td>
                        <td class="text-center">
                            <a href="#"
                                class="btn btn-sm btn-success"
                                title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="#"
                                class="btn btn-sm btn-primary"
                                title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="#"
                                method="POST"
                                style="display: inline-block;"
                                onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="btn btn-sm btn-danger"
                                    title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>

                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            No se encontraron mobiliarios.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="panel-footer text-center">
            {{ $mobiliarios->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>

    </div>
</div>
@endsection