@extends('layouts.admin')

@section('title', 'Rentas de Mobiliario')

@section('content')
<div class="container-fluid">
    <h2>Mobiliario de Renta</h2>
    <div>
        <a href="{{ route('mobiliario_renta.create') }}" class="btn-nueva-area">
            <i class="fas fa-plus"></i> Registrar Nuevo Equipo de Renta
        </a>
    </div>


    </br>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Tabla de rentas --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover tabla-usuarios-small">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre del Mobiliario</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Proveedor</th>
                    <th>Area Asignada</th>
                    <th>Cantidad</th>
                    <th>Numero de Serie</th>
                    <th>Acciones</th>

                </tr>
            </thead>
            <tbody>
                @forelse($rentas as $renta)
                <tr>
                    <td>{{ $renta->id_renta }}</td>
                    <td>{{ $renta->mobiliario->nombre_mobiliario ?? '' }}</td>
                    <td>{{ $renta->mobiliario->modelo->nombre ?? '' }}</td>
                    <td>{{ $renta->mobiliario->marca->nombre ?? '' }}</td>
                    <td>{{ $renta->mobiliario->proveedor->nombre_prov ?? 'N/A' }}</td>
                    <td>{{ $renta->cantidad }}</td>
                    <td>{{ $renta->cantidad }}</td>
                    <td>{{ $renta->cantidad }}</td>
                    <td class="text-center">
                        <button type="button"
                            class="btn btn-sm btn-success btn-ver-renta"
                            data-id="{{ $renta->id_renta }}"
                            title="Ver">
                            <i class="fa fa-eye"></i>
                        </button>
                        <a href="{{ route('mobiliario_renta.edit', $renta->id_renta) }}"
                            class="btn btn-sm btn-primary"
                            title="Editar">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <form action="{{ route('mobiliario_renta.destroy', $renta->id_renta) }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="return confirm('¿Está seguro de eliminar esta área?');">
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
                    <td colspan="12" class="text-center">No hay rentas registradas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div>
            {{ $rentas->links() }}
        </div>
    </div>
</div>
@endsection