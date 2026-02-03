<div class="modal-header btn btn-registrar row justify-content-center">
    <h5 class="modal-title">{{ $usuario->nombre }}</h5>
</div>

<div class="modal-body">
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $usuario->id }}</td>
        </tr>
        <tr>
            <th>Usuario</th>
            <td>{{ $usuario->nombre }}</td>
        </tr>
        <tr>
            <th>Correo Electrónico</th>
            <td>{{ $usuario->email }}</td>
        </tr>
        <tr>
            <th>Fecha de creación</th>
            <td>{{ date('d/m/Y H:i', $usuario->created_at) }}</td>
        </tr>
        <tr>
            <th>Última actualización</th>
            <td>{{ date('d/m/Y H:i', $usuario->updated_at) }}</td>
        </tr>
    </table>
</div>

<div class="modal-footer">
    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-registrar">
        Editar
    </a>
    <a href="{{ route('usuarios.index', $usuario->id) }}" class="btn btn-secondary">
        Cerrar
    </a>
</div>
