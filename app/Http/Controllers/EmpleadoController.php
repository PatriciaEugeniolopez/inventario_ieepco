<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\AreaAsignacion;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $query = Empleado::with('areaAsignacion');

        if ($request->filled('nombre')) {
            $query->where('nombre_empleado', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('apellido_p')) {
            $query->where('apellido_p', 'like', '%' . $request->apellido_p . '%');
        }

        if ($request->filled('apellido_m')) {
            $query->where('apellido_m', 'like', '%' . $request->apellido_m . '%');
        }

        if ($request->filled('puesto')) {
            $query->where('puesto', 'like', '%' . $request->puesto . '%');
        }

        if ($request->filled('fk_area_trabajo')) {
            $query->whereHas('areaAsignacion', function ($q) use ($request) {
                $q->where('nombre_asignacion', 'like', '%' . $request->fk_area_trabajo . '%');
            });
        }

        $empleados = $query->paginate(20);
        $areas = AreaAsignacion::all();

        return view('empleados.index', compact('empleados', 'areas'));
    }

    public function create()
    {
        $areas = AreaAsignacion::all();
        return view('empleados.create', compact('areas'));
    }

    

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

   
    public function destroy(Empleado $empleado)
    {
        $empleado->delete();

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado eliminado exitosamente.');
    }
}
