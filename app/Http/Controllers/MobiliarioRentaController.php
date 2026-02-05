<?php

namespace App\Http\Controllers;

use App\Models\Mobiliario;
use App\Models\MobiliarioRenta;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobiliarioRentaController extends Controller
{
    /**
     * Mostrar listado de rentas
     */
    public function index(Request $request)
    {
        $query = MobiliarioRenta::with([
            'mobiliario.modelo',
            'mobiliario.marca',
            'proveedor',
            'usuarioRegistra'
        ]);

        // Filtro por estado
        if ($request->filled('estado_renta')) {
            $query->where('estado_renta', $request->estado_renta);
        }

        // Filtro por proveedor
        if ($request->filled('id_proveedor')) {
            $query->where('id_proveedor', $request->id_proveedor);
        }

        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->where('fecha_inicio', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_fin', '<=', $request->fecha_hasta);
        }

        // Filtro especial: próximas a vencer
        if ($request->filled('proximas_vencer') && $request->proximas_vencer) {
            $dias = $request->filled('dias') ? $request->dias : 30;
            $query->proximasVencer($dias);
        }

        // Filtro especial: vencidas
        if ($request->filled('vencidas') && $request->vencidas) {
            $query->vencidas();
        }

        $rentas = $query->orderBy('created_at', 'desc')->paginate(20);

        // Para los filtros
        $proveedores = Proveedor::orderBy('nombre_prov')->get();

        return view('mobiliario_renta.index', compact('rentas', 'proveedores'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $mobiliarios = Mobiliario::where('tipo_inventario', 'renta')
                                 ->with(['modelo', 'marca'])
                                 ->orderBy('nombre_mobiliario')
                                 ->get();
        
        $proveedores = Proveedor::orderBy('nombre_prov')->get();

        return view('mobiliario_renta.create', compact('mobiliarios', 'proveedores'));
    }

    /**
     * Guardar nueva renta
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mobiliario' => 'required|exists:mobiliario,id_mobiliario',
            'id_proveedor' => 'required|exists:proveedor,id_prov',
            'cantidad' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'costo_mensual' => 'required|numeric|min:0',
            'deposito' => 'nullable|numeric|min:0',
            'notas' => 'nullable|string',
        ], [
            'id_mobiliario.required' => 'Debe seleccionar un mobiliario',
            'id_proveedor.required' => 'Debe seleccionar un proveedor',
            'cantidad.required' => 'La cantidad es obligatoria',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
            'fecha_fin.required' => 'La fecha de fin es obligatoria',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',
            'costo_mensual.required' => 'El costo mensual es obligatorio',
        ]);

        try {
            DB::beginTransaction();

            // Calcular costo total
            $inicio = new \DateTime($validated['fecha_inicio']);
            $fin = new \DateTime($validated['fecha_fin']);
            $meses = $inicio->diff($fin)->m + ($inicio->diff($fin)->y * 12);
            
            $validated['costo_total'] = $validated['costo_mensual'] * max($meses, 1);
            $validated['usuario_registra_id'] = auth()->id;

            $renta = MobiliarioRenta::create($validated);

            // Actualizar mobiliario
            $mobiliario = Mobiliario::findOrFail($validated['id_mobiliario']);
            $mobiliario->cantidad_total += $validated['cantidad'];
            $mobiliario->cantidad_disponible += $validated['cantidad'];
            $mobiliario->save();

            // Registrar en historial
            \App\Models\MobiliarioHistorial::create([
                'id_mobiliario' => $mobiliario->id_mobiliario,
                'tipo_movimiento' => 'entrada',
                'cantidad' => $validated['cantidad'],
                'cantidad_anterior' => $mobiliario->cantidad_total - $validated['cantidad'],
                'cantidad_nueva' => $mobiliario->cantidad_total,
                'user_id' => auth()->id,
                'referencia_id' => $renta->id_renta,
                'tipo_referencia' => 'renta',
                'observaciones' => 'Entrada por renta de proveedor: ' . $renta->proveedor->nombre_prov
            ]);

            DB::commit();

            return redirect()->route('mobiliario_renta.index')
                           ->with('success', 'Renta registrada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al registrar renta: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de una renta
     */
    public function show(MobiliarioRenta $mobiliario_renta)
    {
        $mobiliario_renta->load([
            'mobiliario.modelo',
            'mobiliario.marca',
            'proveedor',
            'usuarioRegistra'
        ]);

        if (request()->ajax()) {
            return view('mobiliario_renta.modal', compact('mobiliario_renta'));
        }

        return view('mobiliario_renta.show', compact('mobiliario_renta'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(MobiliarioRenta $mobiliario_renta)
    {
        // Solo permitir editar si está activa
        if ($mobiliario_renta->estado_renta !== 'activa') {
            return redirect()->route('mobiliario_renta.index')
                           ->with('error', 'Solo se pueden editar rentas activas');
        }

        $mobiliarios = Mobiliario::where('tipo_inventario', 'renta')
                                 ->with(['modelo', 'marca'])
                                 ->orderBy('nombre_mobiliario')
                                 ->get();
        
        $proveedores = Proveedor::orderBy('nombre_prov')->get();

        return view('mobiliario_renta.edit', compact(
            'mobiliario_renta',
            'mobiliarios',
            'proveedores'
        ));
    }

    /**
     * Actualizar renta
     */
    public function update(Request $request, MobiliarioRenta $mobiliario_renta)
    {
        if ($mobiliario_renta->estado_renta !== 'activa') {
            return redirect()->route('mobiliario_renta.index')
                           ->with('error', 'Solo se pueden editar rentas activas');
        }

        $validated = $request->validate([
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'costo_mensual' => 'required|numeric|min:0',
            'deposito' => 'nullable|numeric|min:0',
            'notas' => 'nullable|string',
        ]);

        try {
            // Recalcular costo total
            $inicio = new \DateTime($mobiliario_renta->fecha_inicio);
            $fin = new \DateTime($validated['fecha_fin']);
            $meses = $inicio->diff($fin)->m + ($inicio->diff($fin)->y * 12);
            
            $validated['costo_total'] = $validated['costo_mensual'] * max($meses, 1);

            $mobiliario_renta->update($validated);

            return redirect()->route('mobiliario_renta.index')
                           ->with('success', 'Renta actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al actualizar renta: ' . $e->getMessage());
        }
    }

    /**
     * Finalizar renta
     */
    public function finalizar(Request $request, MobiliarioRenta $mobiliario_renta)
    {
        if ($mobiliario_renta->estado_renta !== 'activa') {
            return redirect()->route('mobiliario_renta.index')
                           ->with('error', 'Esta renta ya fue finalizada');
        }

        $validated = $request->validate([
            'fecha_devolucion' => 'required|date',
            'notas' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $mobiliario_renta->update([
                'fecha_devolucion' => $validated['fecha_devolucion'],
                'estado_renta' => 'finalizada',
                'notas' => $validated['notas'] ?? $mobiliario_renta->notas,
            ]);

            // Reducir stock del mobiliario
            $mobiliario = $mobiliario_renta->mobiliario;
            $mobiliario->cantidad_total -= $mobiliario_renta->cantidad;
            
            // Solo reducir disponible si no están asignados
            if ($mobiliario->cantidad_disponible >= $mobiliario_renta->cantidad) {
                $mobiliario->cantidad_disponible -= $mobiliario_renta->cantidad;
            } else {
                // Si algunos están asignados, ajustar solo lo disponible
                $mobiliario->cantidad_disponible = max(0, $mobiliario->cantidad_disponible);
            }
            
            $mobiliario->save();

            // Registrar en historial
            \App\Models\MobiliarioHistorial::create([
                'id_mobiliario' => $mobiliario->id_mobiliario,
                'tipo_movimiento' => 'salida',
                'cantidad' => $mobiliario_renta->cantidad,
                'cantidad_anterior' => $mobiliario->cantidad_total + $mobiliario_renta->cantidad,
                'cantidad_nueva' => $mobiliario->cantidad_total,
                'user_id' => auth()->id,
                'referencia_id' => $mobiliario_renta->id_renta,
                'tipo_referencia' => 'renta',
                'observaciones' => 'Salida por finalización de renta'
            ]);

            DB::commit();

            return redirect()->route('mobiliario_renta.index')
                           ->with('success', 'Renta finalizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                           ->with('error', 'Error al finalizar renta: ' . $e->getMessage());
        }
    }

    /**
     * Renovar renta
     */
    public function renovar(Request $request, MobiliarioRenta $mobiliario_renta)
    {
        if ($mobiliario_renta->estado_renta !== 'activa') {
            return redirect()->route('mobiliario_renta.index')
                           ->with('error', 'Solo se pueden renovar rentas activas');
        }

        $validated = $request->validate([
            'nueva_fecha_fin' => 'required|date|after:' . $mobiliario_renta->fecha_fin,
            'notas' => 'nullable|string',
        ]);

        try {
            // Calcular costo adicional
            $inicio = new \DateTime($mobiliario_renta->fecha_fin);
            $fin = new \DateTime($validated['nueva_fecha_fin']);
            $meses_adicionales = $inicio->diff($fin)->m + ($inicio->diff($fin)->y * 12);
            
            $costo_adicional = $mobiliario_renta->costo_mensual * max($meses_adicionales, 1);

            $mobiliario_renta->update([
                'fecha_fin' => $validated['nueva_fecha_fin'],
                'costo_total' => $mobiliario_renta->costo_total + $costo_adicional,
                'estado_renta' => 'renovada',
                'notas' => $validated['notas'] ?? $mobiliario_renta->notas,
            ]);

            return redirect()->route('mobiliario_renta.index')
                           ->with('success', 'Renta renovada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al renovar renta: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar rentas activas
     */
    public function activas()
    {
        $rentas = MobiliarioRenta::with(['mobiliario.modelo', 'mobiliario.marca', 'proveedor'])
                                 ->activas()
                                 ->orderBy('fecha_fin', 'asc')
                                 ->get();

        return view('mobiliario_renta.activas', compact('rentas'));
    }

    /**
     * Mostrar rentas próximas a vencer
     */
    public function proximasVencer(Request $request)
    {
        $dias = $request->filled('dias') ? $request->dias : 30;
        
        $rentas = MobiliarioRenta::with(['mobiliario.modelo', 'mobiliario.marca', 'proveedor'])
                                 ->proximasVencer($dias)
                                 ->orderBy('fecha_fin', 'asc')
                                 ->get();

        return view('mobiliario_renta.proximas-vencer', compact('rentas', 'dias'));
    }

    /**
     * Reporte de costos de rentas
     */
    public function reporteCostos(Request $request)
    {
        $query = MobiliarioRenta::with(['mobiliario', 'proveedor']);

        if ($request->filled('fecha_desde')) {
            $query->where('fecha_inicio', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_fin', '<=', $request->fecha_hasta);
        }

        if ($request->filled('id_proveedor')) {
            $query->where('id_proveedor', $request->id_proveedor);
        }

        $rentas = $query->get();
        
        $total_depositos = $rentas->sum('deposito');
        $total_mensual = $rentas->sum('costo_mensual');
        $total_general = $rentas->sum('costo_total');

        $proveedores = Proveedor::orderBy('nombre_prov')->get();

        return view('mobiliario_renta.reporte-costos', compact(
            'rentas',
            'total_depositos',
            'total_mensual',
            'total_general',
            'proveedores'
        ));
    }
}