<?php

namespace App\Http\Controllers;

use App\Models\MobiliarioHistorial;
use App\Models\Mobiliario;
use App\Models\User;
use Illuminate\Http\Request;

class MobiliarioHistorialController extends Controller
{
    /**
     * Mostrar historial completo
     */
    public function index(Request $request)
    {
        $query = MobiliarioHistorial::with(['mobiliario.modelo', 'mobiliario.marca', 'usuario']);

        // Filtro por mobiliario
        if ($request->filled('id_mobiliario')) {
            $query->where('id_mobiliario', $request->id_mobiliario);
        }

        // Filtro por tipo de movimiento
        if ($request->filled('tipo_movimiento')) {
            $query->where('tipo_movimiento', $request->tipo_movimiento);
        }

        // Filtro por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->where('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('created_at', '<=', $request->fecha_hasta . ' 23:59:59');
        }

        $historial = $query->orderBy('created_at', 'desc')->paginate(50);

        // Para los filtros
        $mobiliarios = Mobiliario::orderBy('nombre_mobiliario')->get();
        $usuarios = User::where('status', 10)->orderBy('nombre')->get();

        return view('mobiliario_historial.index', compact('historial', 'mobiliarios', 'usuarios'));
    }

    /**
     * Historial de un mobiliario específico
     */
    public function porMobiliario($id_mobiliario)
    {
        $mobiliario = Mobiliario::with(['modelo', 'marca'])->findOrFail($id_mobiliario);
        
        $historial = MobiliarioHistorial::with('usuario')
                                       ->where('id_mobiliario', $id_mobiliario)
                                       ->orderBy('created_at', 'desc')
                                       ->paginate(20);

        return view('mobiliario_historial.por-mobiliario', compact('mobiliario', 'historial'));
    }

    /**
     * Historial de movimientos de un usuario
     */
    public function porUsuario($user_id)
    {
        $usuario = User::findOrFail($user_id);
        
        $historial = MobiliarioHistorial::with(['mobiliario.modelo', 'mobiliario.marca'])
                                       ->where('user_id', $user_id)
                                       ->orderBy('created_at', 'desc')
                                       ->paginate(20);

        return view('mobiliario_historial.por-usuario', compact('usuario', 'historial'));
    }

    /**
     * Reporte de movimientos por período
     */
    public function reportePorPeriodo(Request $request)
    {
        $validated = $request->validate([
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
        ]);

        $historial = MobiliarioHistorial::with(['mobiliario', 'usuario'])
                                       ->whereBetween('created_at', [
                                           $validated['fecha_desde'],
                                           $validated['fecha_hasta'] . ' 23:59:59'
                                       ])
                                       ->orderBy('created_at', 'desc')
                                       ->get();

        // Resumen por tipo de movimiento
        $resumen = $historial->groupBy('tipo_movimiento')->map(function($items) {
            return [
                'cantidad_movimientos' => $items->count(),
                'cantidad_total' => $items->sum('cantidad'),
            ];
        });

        return view('mobiliario_historial.reporte-periodo', compact('historial', 'resumen', 'validated'));
    }

    /**
     * Exportar historial a Excel
     */
    public function exportar(Request $request)
    {
        // Aquí puedes implementar la exportación usando Laravel Excel
        // Por ahora, retornar los datos en formato que puedas usar
        
        $query = MobiliarioHistorial::with(['mobiliario', 'usuario']);

        if ($request->filled('fecha_desde')) {
            $query->where('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('created_at', '<=', $request->fecha_hasta . ' 23:59:59');
        }

        $historial = $query->orderBy('created_at', 'desc')->get();

        return view('mobiliario_historial.exportar', compact('historial'));
    }
}