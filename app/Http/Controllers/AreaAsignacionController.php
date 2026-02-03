<?php

namespace App\Http\Controllers;

use App\Models\AreaAsignacion;
use Illuminate\Http\Request;

class AreaAsignacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $areas = AreaAsignacion::all();
        return view('area_asignacion.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('area_asignacion.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(AreaAsignacion $area_asignacion)
    {
        return view('area_asignacion.show', compact('area_asignacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AreaAsignacion $area_asignacion)
    {
        return view('area_asignacion.edit', compact('area_asignacion'));
    }

    /**
     * Update the specified resource in storage.
     */
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