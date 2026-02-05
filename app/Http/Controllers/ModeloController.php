<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modelo;
use App\Models\Marca;
use Illuminate\View\View;

class ModeloController extends Controller
{
    public function index(Request $request)
    {
        $query = Modelo::with('marca');

        if ($request->filled('nombre_modelo')) {
            $query->where('nombre_modelo', 'like', '%' . $request->nombre_modelo . '%');
        }

        if ($request->filled('fk_marca')) {
            $query->where('fk_marca', $request->fk_marca);
        }

        $modelos = $query->get();
        $marcas  = Marca::orderBy('nombre_marca')->get();
        
        $modelos = $query->paginate(20);
        return view('modelo.index', compact('modelos', 'marcas'));
    }

    public function create()
    {
        $marcas = Marca::orderBy('nombre_marca')->get();
        return view('modelo.create', compact('marcas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_modelo' => 'required|string|max:150',
            'fk_marca'      => 'required|integer',
        ]);

        Modelo::create([
            'nombre_modelo' => $request->nombre_modelo,
            'fk_marca'      => $request->fk_marca,
            'status'        => 1
        ]);

        return redirect()->route('modelo.index');
    }

    public function show($id)
    {
        $modelo = Modelo::with('marca')->findOrFail($id);

        if (request()->ajax()) {
            return view('modelo.modal', compact('modelo'));
        }

        return view('modelo.show', compact('modelo'));
    }


    public function edit($id)
    {
        $modelo = Modelo::findOrFail($id);
        $marcas = Marca::orderBy('nombre_marca')->get();

        return view('modelo.edit', compact('modelo', 'marcas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_modelo' => 'required|string|max:150',
            'fk_marca'      => 'required|integer',
            'status'        => 'required|boolean',
        ]);

        $modelo = Modelo::findOrFail($id);

        $modelo->update([
            'nombre_modelo' => $request->nombre_modelo,
            'fk_marca'      => $request->fk_marca,
            'status'        => $request->status,
        ]);

        return redirect()
            ->route('modelo.index')
            ->with('success', 'Modelo actualizado correctamente');
    }

    public function destroy($id)
    {
        $modelo = Modelo::findOrFail($id);

        // Eliminado físico
        $modelo->delete();

        return redirect()
            ->route('modelo.index')
            ->with('success', 'Modelo eliminado correctamente');
    }
}
