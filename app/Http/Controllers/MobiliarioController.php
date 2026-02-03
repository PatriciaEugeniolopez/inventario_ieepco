<?php

namespace App\Http\Controllers;

use App\Models\Mobiliario;
use App\Models\AreaAsignacion;
use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Http\Request;

class MobiliarioController extends Controller
{
    /* =======================
     * LISTADO + FILTROS
     * =======================*/
    public function index(Request $request)
    {
        $query = Mobiliario::with(['marca', 'modelo', 'areaAsignacion', 'user']);

        if ($request->filled('nombre_mobiliario')) {
            $query->where('nombre_mobiliario', 'like', '%' . $request->nombre_mobiliario . '%');
        }

        if ($request->filled('fk_asignacion')) {
            $query->where('fk_asignacion', $request->fk_asignacion);
        }

        if ($request->filled('id_marcafk')) {
            $query->where('id_marcafk', $request->id_marcafk);
        }

        if ($request->filled('id_modelofk')) {
            $query->where('id_modelofk', $request->id_modelofk);
        }

        $mobiliarios = $query->paginate(20);

        return view('mobiliarios.index', [
            'mobiliarios' => $mobiliarios,
            'areas'       => AreaAsignacion::orderBy('nombre_asignacion')->get(),
            'marcas'      => Marca::orderBy('nombre_marca')->get(),
            'modelos'     => Modelo::orderBy('nombre_modelo')->get(),
        ]);
    }

    /* =======================
     * FORMULARIO CREATE
     * =======================*/
    public function create()
    {
        return view('mobiliarios.create', [
            'areas'   => AreaAsignacion::orderBy('nombre_asignacion')->get(),
            'marcas'  => Marca::orderBy('nombre_marca')->get(),
            'modelos' => Modelo::orderBy('nombre_modelo')->get(),
        ]);
    }

    /* =======================
     * GUARDAR REGISTRO
     * =======================*/
    public function store(Request $request)
    {
        $request->validate([
            'nombre_mobiliario' => 'required|string|max:150',
            'id_marcafk'        => 'required|integer',
            'id_modelofk'       => 'required|integer',
            'fk_asignacion'     => 'required|integer',
        ]);

        Mobiliario::create($request->all());

        return redirect()
            ->route('mobiliarios.index')
            ->with('success', 'Mobiliario registrado correctamente');
    }

    /* =======================
     * ELIMINAR REGISTRO
     * =======================*/
    public function destroy($id)
    {
        $mobiliario = Mobiliario::findOrFail($id);
        $mobiliario->delete();

        return redirect()
            ->route('mobiliarios.index')
            ->with('success', 'Mobiliario eliminado correctamente');
    }
}
