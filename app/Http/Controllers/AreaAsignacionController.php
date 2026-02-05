<?php

namespace App\Http\Controllers;

use App\Models\AreaAsignacion;
use Illuminate\Http\Request;

class AreaAsignacionController extends Controller
{
    public function index(Request $request)
    {
        $query = AreaAsignacion::query();

        // Filtro por nombre de asignación
        if ($request->filled('nombre_asignacion')) {
            $query->where('nombre_asignacion', 'like', '%' . $request->nombre_asignacion . '%');
        }

        // Filtro por responsable del área
        if ($request->filled('responsable_area')) {
            $query->where('responsable_area', 'like', '%' . $request->responsable_area . '%');
        }

        $areas = $query->get();
        $areas = $query->paginate(20);
        
        return view('area_asignacion.index', compact('areas'));
    }

    public function create()
    {
        return view('area_asignacion.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_asignacion' => 'required|max:125',
            'responsable_area' => 'nullable|max:150',
        ]);

        AreaAsignacion::create($validated);

        return redirect()->route('area_asignacion.index')
            ->with('success', 'Área creada exitosamente.');
    }

    public function show(AreaAsignacion $area_asignacion)
    {
        if (request()->ajax()) {
            return view('area_asignacion.modal', compact('area_asignacion'));
        }
        return view('area_asignacion.show', compact('area_asignacion'));
    }

    public function edit(AreaAsignacion $area_asignacion)
    {
        return view('area_asignacion.edit', compact('area_asignacion'));
    }

    public function update(Request $request, AreaAsignacion $area_asignacion)
    {
        $validated = $request->validate([
            'nombre_asignacion' => 'required|max:125',
            'responsable_area' => 'nullable|max:150',
        ]);

        $area_asignacion->update($validated);

        return redirect()->route('area_asignacion.index')
            ->with('success', 'Área actualizada exitosamente.');
    }


    public function destroy(AreaAsignacion $area_asignacion)
    {
        $area_asignacion->delete();

        return redirect()->route('area_asignacion.index')
            ->with('success', 'Área eliminada exitosamente.');
    }
}
