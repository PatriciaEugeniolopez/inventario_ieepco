<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedor = Proveedor::all();
        return view('proveedor.index', compact('proveedor'));
    }

    public function create()
    {
        return view('proveedor.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_prov' => 'required|max:200',
            'rfc' => 'required|max:150',
            'telefono' => 'nullable|string|max:20',
            'calle' => 'required|max:150',
            'numero_ext' => 'nullable|integer',
            'numero_int' => 'nullable|integer',
            'colonia' => 'required|max:150',
            'municipio' => 'required|max:150',
            'estado' => 'required|max:150',
            'pais' => 'required|max:150',
            'codigo_postal' => 'nullable|integer',
        ]);

        Proveedor::create($validated);

        return redirect()->route('proveedor.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }


    public function show(Proveedor $proveedor)
    {
        return view('proveedor.show', compact('proveedor'));
    }


    public function edit(Proveedor $proveedor)
    {
        return view('proveedor.edit', compact('proveedor'));
    }


    public function update(Request $request, Proveedor $proveedor)
    {
        $validated = $request->validate([
            'nombre_prov' => 'required|max:200',
            'rfc' => 'required|max:150',
            'telefono' => 'nullable|string|max:10',
            'calle' => 'required|max:150',
            'numero_ext' => 'nullable|integer',
            'numero_int' => 'nullable|integer',
            'colonia' => 'required|max:150',
            'municipio' => 'required|max:150',
            'estado' => 'required|max:150',
            'pais' => 'required|max:150',
            'codigo_postal' => 'nullable|integer',
        ]);

        $proveedor->update($validated);

        return redirect()->route('proveedor.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('proveedor.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}
