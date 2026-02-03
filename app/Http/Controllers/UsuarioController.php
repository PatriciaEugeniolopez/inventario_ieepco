<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use App\Models\AuthItem;
use App\Models\AuthAssignment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'empleado']);

        if ($request->filled('id')) {
            $query->where('id', 'LIKE', "%{$request->id}%");
        }

        if ($request->filled('nombre')) {
            $query->where('nombre', 'LIKE', "%{$request->nombre}%");
        }

        if ($request->filled('email')) {
            $query->where('email', 'LIKE', "%{$request->email}%");
        }

        $usuarios = $query->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = AuthItem::roles()->orderBy('name')->get();

        // Obtener solo empleados que NO tienen usuario asignado
        $empleados = Empleado::with('areaAsignacion')
            ->whereDoesntHave('usuario')
            ->orderBy('nombre_empleado')
            ->orderBy('apellido_p')
            ->get();

        return view('usuarios.create', compact('roles', 'empleados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fk_idempleado' => 'required|exists:empleados,id_empleado|unique:user,fk_idempleado',
            'nombre' => 'required|unique:user,nombre|max:255',
            'email' => 'required|email|unique:user,email|max:255',
            'password' => 'required|min:6|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:auth_item,name',
        ], [
            'fk_idempleado.required' => 'Debe seleccionar un empleado',
            'fk_idempleado.exists' => 'El empleado seleccionado no existe',
            'fk_idempleado.unique' => 'Este empleado ya tiene un usuario asignado',
        ]);

        $usuario = User::create([
            'fk_idempleado' => $request->fk_idempleado,
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'auth_key' => bin2hex(random_bytes(16)),
            'status' => 10,
            'created_at' => time(),
            'updated_at' => time(),
        ]);



        if ($request->has('roles') && !empty($request->roles)) {
            $usuario->syncRoles($request->roles);
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    public function show($id)
    {
        $usuario = User::with(['roles', 'empleado.areaAsignacion'])->findOrFail($id);

        if (request()->ajax()) {
            return view('usuarios.modal', compact('usuario'));
        }

        return view('usuarios.show', compact('usuario'));
    }

    public function edit($id)
    {
        $usuario = User::with(['roles', 'empleado'])->findOrFail($id);
        $roles = AuthItem::roles()->orderBy('name')->get();

        // Empleados sin usuario o el empleado actual del usuario
        $empleados = Empleado::with('areaAsignacion')
            ->where(function ($query) use ($usuario) {
                $query->whereDoesntHave('usuario')
                    ->orWhere('id_empleado', $usuario->fk_idempleado);
            })
            ->orderBy('nombre_empleado')
            ->orderBy('apellido_p')
            ->get();

        return view('usuarios.edit', compact('usuario', 'roles', 'empleados'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'fk_idempleado' => 'required|exists:empleados,id_empleado|unique:user,fk_idempleado,' . $id,
            'nombre' => 'required|max:255|unique:user,nombre,' . $id,
            'email' => 'required|email|max:255|unique:user,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:auth_item,name',
        ], [
            'fk_idempleado.required' => 'Debe seleccionar un empleado',
            'fk_idempleado.exists' => 'El empleado seleccionado no existe',
            'fk_idempleado.unique' => 'Este empleado ya tiene otro usuario asignado',
        ]);

        $usuario->fk_idempleado = $request->fk_idempleado;
        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;

        if ($request->filled('password')) {
            $usuario->password_hash = Hash::make($request->password);
        }

        $usuario->updated_at = time();
        $usuario->save();

        if ($request->has('roles')) {
            $usuario->syncRoles($request->roles);
        } else {
            $usuario->syncRoles([]);
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        // Eliminar asignaciones de roles
        AuthAssignment::where('user_id', $id)->delete();

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }
}
