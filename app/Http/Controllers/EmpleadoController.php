<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\AreaAsignacion;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empleados = Empleado::with('areaAsignacion')->get();
        $areas = AreaAsignacion::all();
        return view('empleados.index', compact('empleados', 'areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $areas = AreaAsignacion::all();
        return view('empleados.create', compact('areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_empleado' => 'required|max:100',
            'apellido_p' => 'required|max:100',
            'apellido_m' => 'nullable|max:100',
            'puesto' => 'nullable|max:100',
            'fk_area_trabajo' => 'nullable|exists:area_asignacion,id_asignacion',
        ]);

        Empleado::create($validated);

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $empleado)
    {
        $empleado->load('areaAsignacion');
        return view('empleados.show', compact('empleado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empleado $empleado)
    {
        $areas = AreaAsignacion::all();
        return view('empleados.edit', compact('empleado', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empleado $empleado)
    {
        $validated = $request->validate([
            'nombre_empleado' => 'required|max:100',
            'apellido_p' => 'required|max:100',
            'apellido_m' => 'nullable|max:100',
            'puesto' => 'nullable|max:100',
            'fk_area_trabajo' => 'nullable|exists:area_asignacion,id_asignacion',
        ]);

        $empleado->update($validated);

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empleado $empleado)
    {
        $empleado->delete();

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado eliminado exitosamente.');
    }
}