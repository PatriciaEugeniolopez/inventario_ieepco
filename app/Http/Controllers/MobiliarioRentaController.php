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
            'mobiliario.proveedor'
        ]);

        // Filtro por estado
        if ($request->filled('estado')) {
            if ($request->estado === 'activa') {
                $query->activas();
            } elseif ($request->estado === 'finalizada') {
                $query->finalizadas();
            } elseif ($request->estado === 'vencida') {
                $query->vencidas();
            }
        }

        // Filtro por proveedor
        if ($request->filled('id_proveedor')) {
            $query->whereHas('mobiliario', function($q) use ($request) {
                $q->where('fk_provedor', $request->id_proveedor);
            });
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
        // Mobiliarios tipo renta con cantidad disponible
        $mobiliarios = Mobiliario::where('tipo_inventario', 'renta')
                                 ->where('cantidad_disponible', '>', 0)
                                 ->with(['modelo', 'marca', 'proveedor'])
                                 ->orderBy('nombre_mobiliario')
                                 ->get();

        return view('mobiliario_renta.create', compact('mobiliarios'));
    }

    /**
     * Guardar nueva renta
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mobiliario' => 'required|exists:mobiliario,id_mobiliario',
            'num_serie' => 'nullable|string|max:100',
            'cantidad' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ], [
            'id_mobiliario.required' => 'Debe seleccionar un mobiliario',
            'cantidad.required' => 'La cantidad es obligatoria',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
            'fecha_fin.required' => 'La fecha de fin es obligatoria',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',
        ]);

        try {
            DB::beginTransaction();

            // Verificar disponibilidad
            $mobiliario = Mobiliario::findOrFail($validated['id_mobiliario']);
            
            if ($mobiliario->cantidad_disponible < $validated['cantidad']) {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'No hay suficiente cantidad disponible. Disponible: ' . $mobiliario->cantidad_disponible);
            }

            // Crear la renta
            $renta = MobiliarioRenta::create($validated);

            // Actualizar cantidades del mobiliario
            $mobiliario->cantidad_total += $validated['cantidad'];
            $mobiliario->cantidad_disponible += $validated['cantidad'];
            $mobiliario->save();

            // Registrar en historial (si tienes esta tabla)
            if (class_exists('\App\Models\MobiliarioHistorial')) {
                \App\Models\MobiliarioHistorial::create([
                    'id_mobiliario' => $mobiliario->id_mobiliario,
                    'tipo_movimiento' => 'entrada',
                    'cantidad' => $validated['cantidad'],
                    'cantidad_anterior' => $mobiliario->cantidad_total - $validated['cantidad'],
                    'cantidad_nueva' => $mobiliario->cantidad_total,
                    'user_id' => auth()->id,
                    'referencia_id' => $renta->id_renta,
                    'tipo_referencia' => 'renta',
                    'observaciones' => 'Entrada por renta de proveedor: ' . ($mobiliario->proveedor->nombre_prov ?? 'N/A')
                ]);
            }

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
            'mobiliario.proveedor'
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
        // Solo permitir editar si está activa (no tiene fecha de devolución)
        if ($mobiliario_renta->fecha_devolucion) {
            return redirect()->route('mobiliario_renta.index')
                           ->with('error', 'Solo se pueden editar rentas activas');
        }

        $mobiliario_renta->load(['mobiliario.modelo', 'mobiliario.marca', 'mobiliario.proveedor']);

        return view('mobiliario_renta.edit', compact('mobiliario_renta'));
    }

    /**
     * Actualizar renta
     */
    public function update(Request $request, MobiliarioRenta $mobiliario_renta)
    {
        if ($mobiliario_renta->fecha_devolucion) {
            return redirect()->route('mobiliario_renta.index')
                           ->with('error', 'Solo se pueden editar rentas activas');
        }

        $validated = $request->validate([
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'cantidad' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Si cambia la cantidad, ajustar el mobiliario
            if ($validated['cantidad'] != $mobiliario_renta->cantidad) {
                $diferencia = $validated['cantidad'] - $mobiliario_renta->cantidad;
                
                $mobiliario = $mobiliario_renta->mobiliario;
                
                if ($diferencia > 0) {
                    // Aumentó la cantidad - verificar disponibilidad
                    if ($mobiliario->cantidad_disponible < $diferencia) {
                        DB::rollBack();
                        return redirect()->back()
                                       ->withInput()
                                       ->with('error', 'No hay suficiente cantidad disponible');
                    }
                }
                
                $mobiliario->cantidad_total += $diferencia;
                $mobiliario->cantidad_disponible += $diferencia;
                $mobiliario->save();
            }

            $mobiliario_renta->update($validated);

            DB::commit();

            return redirect()->route('mobiliario_renta.index')
                           ->with('success', 'Renta actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al actualizar renta: ' . $e->getMessage());
        }
    }

    /**
     * Finalizar renta (marcar fecha de devolución)
     */
    public function finalizar(Request $request, MobiliarioRenta $mobiliario_renta)
    {
        if ($mobiliario_renta->fecha_devolucion) {
            return redirect()->route('mobiliario_renta.index')
                           ->with('error', 'Esta renta ya fue finalizada');
        }

        $validated = $request->validate([
            'fecha_devolucion' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $mobiliario_renta->update([
                'fecha_devolucion' => $validated['fecha_devolucion'],
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

            // Registrar en historial (si existe)
            if (class_exists('\App\Models\MobiliarioHistorial')) {
                \App\Models\MobiliarioHistorial::create([
                    'id_mobiliario' => $mobiliario->id_mobiliario,
                    'tipo_movimiento' => 'salida',
                    'cantidad' => $mobiliario_renta->cantidad,
                    'cantidad_anterior' => $mobiliario->cantidad_total + $mobiliario_renta->cantidad,
                    'cantidad_nueva' => $mobiliario->cantidad_total,
                    'user_id' => auth()->id,
                    'referencia_id' => $mobiliario_renta->id_renta,
                    'tipo_referencia' => 'renta_devolucion',
                    'observaciones' => 'Salida por devolución de renta'
                ]);
            }

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
     * Renovar/extender renta
     */
    public function renovar(Request $request, MobiliarioRenta $mobiliario_renta)
    {
        if ($mobiliario_renta->fecha_devolucion) {
            return redirect()->route('mobiliario_renta.index')
                           ->with('error', 'Solo se pueden renovar rentas activas');
        }

        $validated = $request->validate([
            'nueva_fecha_fin' => 'required|date|after:' . $mobiliario_renta->fecha_fin,
        ]);

        try {
            $mobiliario_renta->update([
                'fecha_fin' => $validated['nueva_fecha_fin'],
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
        $rentas = MobiliarioRenta::with([
                'mobiliario.modelo', 
                'mobiliario.marca', 
                'mobiliario.proveedor'
            ])
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
        
        $rentas = MobiliarioRenta::with([
                'mobiliario.modelo', 
                'mobiliario.marca', 
                'mobiliario.proveedor'
            ])
            ->proximasVencer($dias)
            ->orderBy('fecha_fin', 'asc')
            ->get();

        return view('mobiliario_renta.proximas-vencer', compact('rentas', 'dias'));
    }
}