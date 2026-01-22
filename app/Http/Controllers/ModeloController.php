<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modelo;
use App\Models\Marca;

class ModeloController extends Controller
{
    public function index(Request $request)
    {
        $query = Modelo::with('marca');

        if ($request->filled('nombre_modelo')) {
            $query->where('nombre_modelo', 'like', '%'.$request->nombre_modelo.'%');
        }

        if ($request->filled('fk_marca')) {
            $query->where('fk_marca', $request->fk_marca);
        }

        $modelos = $query->paginate(20);
        $marcas  = Marca::orderBy('nombre_marca')->get();

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
        ]);

        $modelo = Modelo::findOrFail($id);
        $modelo->update($request->all());

        return redirect()->route('modelo.index');
    }
}
