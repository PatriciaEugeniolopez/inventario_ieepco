<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;

class MarcaController extends Controller
{
     public function index(Request $request)
    {
        $query = Marca::query();

        if ($request->filled('nombre_marca')) {
            $query->where('nombre_marca', 'like', '%'.$request->nombre_marca.'%');
        }

        $marcas = $query->paginate(20);

        return view('marca.index', compact('marcas'));
    }

    public function create()
    {
        return view('marca.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_marca' => 'required|string|max:150'
        ]);

        Marca::create($request->all());

        return redirect()->route('marca.index');
    }

    public function edit($id)
    {
        $marca = Marca::findOrFail($id);
        return view('marca.edit', compact('marca'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_marca' => 'required|string|max:150'
        ]);

        $marca = Marca::findOrFail($id);
        $marca->update($request->all());

        return redirect()->route('marca.index');
    }
}
