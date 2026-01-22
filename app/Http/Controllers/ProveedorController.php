<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedor::all();
        return view('proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_prov' => 'required|max:200',
            'rfc' => 'required|max:150',
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

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        return view('proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $validated = $request->validate([
            'nombre_prov' => 'required|max:200',
            'rfc' => 'required|max:150',
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

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}