<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use SebastianBergmann\Environment\Console;


class UsuarioController extends Controller
{
     // Mostrar lista de usuarios
    public function index()
    {
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
        dump($usuarios);
    }

    // Mostrar formulario para crear usuario
    public function create()
    {
        return view('usuarios.create');
    }

    // Guardar nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:user,nombre|max:255',
            'email' => 'required|email|unique:user,email|max:255',
            'password' => 'required|min:6',
            'fk_idempleado' => 'required|integer',
        ]);

        User::create([
            'fk_idempleado' => $request->fk_idempleado,
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'auth_key' => bin2hex(random_bytes(16)),
            'status' => 10,
            'created_at' => time(),
            'updated_at' => time(),

        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    // Mostrar formulario para editar usuario
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
        dump($usuario);
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'nombre' => 'required|max:255|unique:user,nombre,' . $id,
            'email' => 'required|email|max:255|unique:user,email,' . $id,
            'fk_idempleado' => 'required|integer',
        ]);

        $usuario->fk_idempleado = $request->fk_idempleado;
        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        
        if ($request->filled('password')) {
            $usuario->password_hash = Hash::make($request->password);
        }
        
        $usuario->updated_at = time();
        $usuario->save();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }
}
