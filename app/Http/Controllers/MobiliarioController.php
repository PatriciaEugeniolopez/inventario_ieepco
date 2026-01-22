<?php

namespace App\Http\Controllers;


use App\Models\Mobiliario;
use Illuminate\Http\Request;



class MobiliarioController extends Controller
{
    
   
    public function index(Request $request)
    {
        $query = Mobiliario::with(['marca','modelo','asignacion','personal','condiciones']);

        if ($request->filled('nombre_mobiliario')) {
            $query->where('nombre_mobiliario', 'like', '%'.$request->nombre_mobiliario.'%');
        }

        if ($request->filled('clave_inventario')) {
            $query->where('clave_inventario', 'like', '%'.$request->clave_inventario.'%');
        }

        $mobiliarios = $query->paginate(10);

        return view('mobiliario.index', compact('mobiliarios'));
    }

    public function create()
    {
        return view('mobiliario.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_mobiliario' => 'required',
            'id_marcafk' => 'required',
            'id_modelofk' => 'required',
            'clave_inventario' => 'unique:mobiliario',
        ]);

        Mobiliario::create($request->all());

        return redirect()->route('mobiliario.index')
            ->with('success','Mobiliario registrado correctamente');
    }

    public function show($id)
    {
        $mobiliario = Mobiliario::findOrFail($id);
        return view('mobiliario.show', compact('mobiliario'));
    }

    public function edit($id)
    {
        $mobiliario = Mobiliario::findOrFail($id);
        return view('mobiliario.edit', compact('mobiliario'));
    }

    public function update(Request $request, $id)
    {
        $mobiliario = Mobiliario::findOrFail($id);
        $mobiliario->update($request->all());

        return redirect()->route('mobiliario.index');
    }
}
