<?php

namespace App\Http\Controllers;

use App\Models\Mobiliario;
use App\Models\MobiliarioAsignacion;
use App\Models\Empleado;
use App\Models\AreaAsignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MobiliarioAsignacionController extends Controller
{
    /**
     * Mostrar listado de asignaciones
     */
    public function index(Request $request)
    {
        $query = MobiliarioAsignacion::with([
            'mobiliario.modelo',
            'mobiliario.marca',
            'empleado',
            'area',
            'usuarioRegistra'
        ]);

        // Filtro por estado
        if ($request->filled('estado_asignacion')) {
            $query->where('estado_asignacion', $request->estado_asignacion);
        }

        // Filtro por empleado
        if ($request->filled('id_empleado')) {
            $query->where('id_empleado', $request->id_empleado);
        }

        // Filtro por área
        if ($request->filled('fk_area_asignacion')) {
            $query->where('fk_area_asignacion', $request->fk_area_asignacion);
        }

        // Filtro por mobiliario
        if ($request->filled('id_mobiliario')) {
            $query->where('id_mobiliario', $request->id_mobiliario);
        }

        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->where('fecha_asignacion', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_asignacion', '<=', $request->fecha_hasta);
        }

        $asignaciones = $query->orderBy('created_at', 'desc')->paginate(20);

        // Para los filtros
        $empleados = Empleado::orderBy('nombre_empleado')->get();
        $areas = AreaAsignacion::orderBy('nombre_asignacion')->get();
        $mobiliarios = Mobiliario::orderBy('nombre_mobiliario')->get();

        return view('mobiliario_asignacion.index', compact(
            'asignaciones',
            'empleados',
            'areas',
            'mobiliarios'
        ));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $mobiliarios = Mobiliario::where('cantidad_disponible', '>', 0)
                                 ->with(['modelo', 'marca'])
                                 ->orderBy('nombre_mobiliario')
                                 ->get();
        
        $empleados = Empleado::orderBy('nombre_empleado')->get();
        $areas = AreaAsignacion::orderBy('nombre_asignacion')->get();

        return view('mobiliario_asignacion.create', compact(
            'mobiliarios',
            'empleados',
            'areas'
        ));
    }

    /**
     * Guardar nueva asignación
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mobiliario' => 'required|exists:mobiliario,id_mobiliario',
            'id_empleado' => 'required|exists:empleados,id_empleado',
            'fk_area_asignacion' => 'required|exists:area_asignacion,id_asignacion',
            'cantidad' => 'required|integer|min:1',
            'fecha_asignacion' => 'required|date',
            'ubicacion' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'responsable_entrega' => 'nullable|string|max:150',
        ], [
            'id_mobiliario.required' => 'Debe seleccionar un mobiliario',
            'id_empleado.required' => 'Debe seleccionar un empleado',
            'fk_area_asignacion.required' => 'Debe seleccionar un área',
            'cantidad.required' => 'La cantidad es obligatoria',
            'cantidad.min' => 'La cantidad debe ser al menos 1',
            'fecha_asignacion.required' => 'La fecha de asignación es obligatoria',
        ]);

        try {
            DB::beginTransaction();

            // Verificar stock disponible
            $mobiliario = Mobiliario::findOrFail($validated['id_mobiliario']);
            
            if ($mobiliario->cantidad_disponible < $validated['cantidad']) {
                throw new \Exception(
                    "Stock insuficiente. Disponible: {$mobiliario->cantidad_disponible}, Solicitado: {$validated['cantidad']}"
                );
            }

            // Crear asignación (el modelo se encarga de actualizar stock y crear historial)
            $asignacion = MobiliarioAsignacion::create($validated);

            DB::commit();

            return redirect()->route('mobiliario_asignacion.index')
                           ->with('success', 'Asignación creada exitosamente. Stock actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al crear asignación: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de una asignación
     */
    public function show(MobiliarioAsignacion $mobiliario_asignacion)
    {
        $mobiliario_asignacion->load([
            'mobiliario.modelo',
            'mobiliario.marca',
            'empleado',
            'area',
            'usuarioRegistra'
        ]);

        if (request()->ajax()) {
            return view('mobiliario_asignacion.modal', compact('mobiliario_asignacion'));
        }

        return view('mobiliario_asignacion.show', compact('mobiliario_asignacion'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(MobiliarioAsignacion $mobiliario_asignacion)
    {
        // Solo permitir editar si está asignado
        if ($mobiliario_asignacion->estado_asignacion !== 'asignado') {
            return redirect()->route('mobiliario_asignacion.index')
                           ->with('error', 'Solo se pueden editar asignaciones en estado "Asignado"');
        }

        $mobiliarios = Mobiliario::with(['modelo', 'marca'])
                                 ->orderBy('nombre_mobiliario')
                                 ->get();
        
        $empleados = Empleado::orderBy('nombre_empleado')->get();
        $areas = AreaAsignacion::orderBy('nombre_asignacion')->get();

        return view('mobiliario_asignacion.edit', compact(
            'mobiliario_asignacion',
            'mobiliarios',
            'empleados',
            'areas'
        ));
    }

    /**
     * Actualizar asignación
     */
    public function update(Request $request, MobiliarioAsignacion $mobiliario_asignacion)
    {
        // Solo permitir editar si está asignado
        if ($mobiliario_asignacion->estado_asignacion !== 'asignado') {
            return redirect()->route('mobiliario_asignacion.index')
                           ->with('error', 'Solo se pueden editar asignaciones en estado "Asignado"');
        }

        $validated = $request->validate([
            'id_empleado' => 'required|exists:empleados,id_empleado',
            'fk_area_asignacion' => 'required|exists:area_asignacion,id_asignacion',
            'ubicacion' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $mobiliario_asignacion->update($validated);

            return redirect()->route('mobiliario_asignacion.index')
                           ->with('success', 'Asignación actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al actualizar asignación: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de devolución
     */
    public function formDevolucion(MobiliarioAsignacion $mobiliario_asignacion)
    {
        if ($mobiliario_asignacion->estado_asignacion !== 'asignado') {
            return redirect()->route('mobiliario_asignacion.index')
                           ->with('error', 'Esta asignación ya fue devuelta');
        }

        return view('mobiliario_asignacion.devolucion', compact('mobiliario_asignacion'));
    }

    /**
     * Procesar devolución
     */
    public function devolver(Request $request, MobiliarioAsignacion $mobiliario_asignacion)
    {
        if ($mobiliario_asignacion->estado_asignacion !== 'asignado') {
            return redirect()->route('mobiliario_asignacion.index')
                           ->with('error', 'Esta asignación ya fue devuelta');
        }

        $validated = $request->validate([
            'fecha_devolucion' => 'required|date|after_or_equal:fecha_asignacion',
            'estado_asignacion' => 'required|in:devuelto,extraviado,dañado',
            'observaciones' => 'nullable|string',
            'responsable_recepcion' => 'nullable|string|max:150',
        ], [
            'fecha_devolucion.required' => 'La fecha de devolución es obligatoria',
            'fecha_devolucion.after_or_equal' => 'La fecha de devolución no puede ser anterior a la fecha de asignación',
            'estado_asignacion.required' => 'Debe seleccionar el estado',
        ]);

        try {
            DB::beginTransaction();

            // El modelo se encarga de actualizar el stock automáticamente
            $mobiliario_asignacion->update([
                'fecha_devolucion' => $validated['fecha_devolucion'],
                'estado_asignacion' => $validated['estado_asignacion'],
                'observaciones' => $validated['observaciones'] ?? $mobiliario_asignacion->observaciones,
                'responsable_recepcion' => $validated['responsable_recepcion'],
            ]);

            DB::commit();

            return redirect()->route('mobiliario_asignacion.index')
                           ->with('success', 'Devolución registrada exitosamente. Stock actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al registrar devolución: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar asignación
     */
    public function destroy(MobiliarioAsignacion $mobiliario_asignacion)
    {
        // Solo permitir eliminar si no ha sido procesada
        if ($mobiliario_asignacion->estado_asignacion !== 'asignado') {
            return redirect()->route('mobiliario_asignacion.index')
                           ->with('error', 'No se puede eliminar una asignación ya procesada');
        }

        try {
            DB::beginTransaction();

            // Restaurar stock
            $mobiliario = $mobiliario_asignacion->mobiliario;
            $mobiliario->cantidad_disponible += $mobiliario_asignacion->cantidad;
            $mobiliario->cantidad_asignada -= $mobiliario_asignacion->cantidad;
            $mobiliario->save();

            // Eliminar asignación
            $mobiliario_asignacion->delete();

            DB::commit();

            return redirect()->route('mobiliario_asignacion.index')
                           ->with('success', 'Asignación eliminada y stock restaurado.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('mobiliario_asignacion.index')
                           ->with('error', 'Error al eliminar asignación: ' . $e->getMessage());
        }
    }

    /**
     * Verificar stock disponible (AJAX)
     */
    public function verificarStock($id_mobiliario)
    {
        try {
            $mobiliario = Mobiliario::with(['modelo', 'marca'])
                                   ->findOrFail($id_mobiliario);
            
            return response()->json([
                'success' => true,
                'nombre' => $mobiliario->nombre_mobiliario,
                'modelo' => $mobiliario->modelo->nombre_modelo ?? 'N/A',
                'marca' => $mobiliario->marca->fk_marca ?? 'N/A',
                'cantidad_disponible' => $mobiliario->cantidad_disponible,
                'cantidad_total' => $mobiliario->cantidad_total,
                'cantidad_asignada' => $mobiliario->cantidad_asignada,
                'tiene_stock' => $mobiliario->cantidad_disponible > 0,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información del mobiliario'
            ], 404);
        }
    }

    /**
     * Reporte de asignaciones por empleado
     */
    public function reportePorEmpleado(Request $request)
    {
        $query = Empleado::with(['asignacionesActivas.mobiliario.modelo', 'asignacionesActivas.mobiliario.marca']);

        if ($request->filled('id_empleado')) {
            $query->where('id_empleado', $request->id_empleado);
        }

        $empleados = $query->has('asignacionesActivas')->get();

        return view('mobiliario_asignacion.reporte-empleado', compact('empleados'));
    }

    /**
     * Reporte de asignaciones por área
     */
    public function reportePorArea(Request $request)
    {
        $query = AreaAsignacion::with(['asignaciones' => function($q) {
            $q->where('estado_asignacion', 'asignado')
              ->with(['mobiliario.modelo', 'mobiliario.marca', 'empleado']);
        }]);

        if ($request->filled('fk_area_asignacion')) {
            $query->where('id_asignacion', $request->fk_area_asignacion);
        }

        $areas = $query->has('asignaciones')->get();

        return view('mobiliario_asignacion.reporte-area', compact('areas'));
    }
}