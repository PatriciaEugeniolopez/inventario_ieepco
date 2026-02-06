<?php

namespace App\Http\Controllers;

use App\Models\Mobiliario;
use App\Models\AreaAsignacion;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobiliarioController extends Controller
{

    public function index(Request $request)
    {
        $query = Mobiliario::with(['marca', 'modelo', 'areaAsignacion', 'user']);

        // Filtro por nombre de mobiliario
        if ($request->filled('nombre_mobiliario')) {
            $query->where('nombre_mobiliario', 'like', '%' . $request->nombre_mobiliario . '%');
        }

        // Filtro por número de serie 
        if ($request->filled('num_serie')) {
            $query->where('num_serie', 'like', '%' . $request->num_serie . '%');
        }

        // Filtro por área de asignación 
        if ($request->filled('fk_asignacion')) {
            $query->where('fk_asignacion', $request->fk_asignacion);
        }

        // Filtro por marca
        if ($request->filled('id_marcafk')) {
            $query->where('id_marcafk', $request->id_marcafk);
        }

        // Filtro por modelo 
        if ($request->filled('id_modelofk')) {
            $query->where('id_modelofk', $request->id_modelofk);
        }

        $mobiliarios = $query->paginate(20);

        return view('mobiliarios.index', [
            'mobiliarios' => $mobiliarios,
            'areas'       => AreaAsignacion::orderBy('nombre_asignacion')->get(),
            'marcas'      => Marca::orderBy('nombre_marca')->get(),
            'modelos'     => Modelo::orderBy('nombre_modelo')->get(),
            'users'       => User::orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        return view('mobiliarios.create', [
            'areas'   => AreaAsignacion::orderBy('nombre_asignacion')->get(),
            'marcas'  => Marca::orderBy('nombre_marca')->get(),
            'modelos' => Modelo::orderBy('nombre_modelo')->get(),
        ]);
    }

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

    public function edit($id)
    {
        $mobiliario = Mobiliario::findOrFail($id);

        return view('mobiliarios.edit', [
            'mobiliario' => $mobiliario,
            'areas'      => AreaAsignacion::orderBy('nombre_asignacion')->get(),
            'marcas'     => Marca::orderBy('nombre_marca')->get(),
            'modelos'    => Modelo::orderBy('nombre_modelo')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_mobiliario' => 'required|string|max:150',
            'id_marcafk'        => 'required|integer',
            'id_modelofk'       => 'required|integer',
            'fk_asignacion'     => 'required|integer',
        ]);

        $mobiliario = Mobiliario::findOrFail($id);
        $mobiliario->update($request->all());

        return redirect()
            ->route('mobiliarios.index')
            ->with('success', 'Mobiliario actualizado correctamente');
    }

    public function show($id)
    {
        $mobiliario = Mobiliario::with(['marca', 'modelo', 'areaAsignacion', 'user'])->findOrFail($id);

        return view('mobiliarios.show', [
            'mobiliario' => $mobiliario,
        ]);
    }

    public function destroy($id)
    {
        $mobiliario = Mobiliario::findOrFail($id);
        $mobiliario->delete();

        return redirect()
            ->route('mobiliarios.index')
            ->with('success', 'Mobiliario eliminado correctamente');
    }

    public function reporteStock(Request $request)
    {
        $query = Mobiliario::with(['modelo', 'marca']);

        // Filtro por tipo
        if ($request->filled('tipo_inventario')) {
            $query->where('tipo_inventario', $request->tipo_inventario);
        }

        // Solo con stock
        if ($request->filled('solo_disponible') && $request->solo_disponible) {
            $query->disponible();
        }

        // Sin stock
        if ($request->filled('sin_stock') && $request->sin_stock) {
            $query->sinStock();
        }

        // Bajo stock
        if ($request->filled('bajo_stock') && $request->bajo_stock) {
            $minimo = $request->filled('cantidad_minima') ? $request->cantidad_minima : 5;
            $query->bajoStock($minimo);
        }

        $mobiliarios = $query->orderBy('nombre_mobiliario')->paginate(20);

        return view('mobiliario.reporte-stock', compact('mobiliarios'));
    }

    // public function ajustarStock(Request $request, Mobiliario $mobiliario)
    // {
    //     $validated = $request->validate([
    //         'tipo_ajuste' => 'required|in:suma,resta',
    //         'cantidad' => 'required|integer|min:1',
    //         'observaciones' => 'required|string',
    //     ]);

    //     try {
    //         DB::beginTransaction();

    //         $cantidad_anterior_total = $mobiliario->cantidad_total;

    //         if ($validated['tipo_ajuste'] === 'suma') {
    //             $mobiliario->cantidad_total += $validated['cantidad'];
    //             $mobiliario->cantidad_disponible += $validated['cantidad'];
    //         } else {
    //             if ($mobiliario->cantidad_disponible < $validated['cantidad']) {
    //                 throw new \Exception('No hay suficiente stock disponible para reducir');
    //             }
    //             $mobiliario->cantidad_total -= $validated['cantidad'];
    //             $mobiliario->cantidad_disponible -= $validated['cantidad'];
    //         }

    //         $mobiliario->save();

    //         \App\Models\MobiliarioHistorial::create([
    //             'id_mobiliario' => $mobiliario->id_mobiliario,
    //             'tipo_movimiento' => 'ajuste',
    //             'cantidad' => $validated['cantidad'],
    //             'cantidad_anterior' => $cantidad_anterior_total,
    //             'cantidad_nueva' => $mobiliario->cantidad_total,
    //             'usuario' => auth()->user()->name ?? auth()->user()->email ?? 'Sistema',
    //             'observaciones' => $validated['observaciones']
    //         ]);

    //         DB::commit();

    //         return redirect()->back()
    //             ->with('success', 'Stock ajustado exitosamente.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return redirect()->back()
    //             ->with('error', 'Error al ajustar stock: ' . $e->getMessage());
    //     }
    // }

    // public function marcarReparacion(Request $request, Mobiliario $mobiliario)
    // {
    //     $validated = $request->validate([
    //         'cantidad' => 'required|integer|min:1',
    //         'observaciones' => 'required|string',
    //     ]);

    //     try {
    //         DB::beginTransaction();

    //         if ($mobiliario->cantidad_disponible < $validated['cantidad']) {
    //             throw new \Exception('No hay suficiente stock disponible');
    //         }

    //         $cantidad_anterior = $mobiliario->cantidad_disponible;
    //         $mobiliario->cantidad_disponible -= $validated['cantidad'];
    //         $mobiliario->cantidad_en_reparacion += $validated['cantidad'];
    //         $mobiliario->save();

    //         \App\Models\MobiliarioHistorial::create([
    //             'id_mobiliario' => $mobiliario->id_mobiliario,
    //             'tipo_movimiento' => 'reparacion',
    //             'cantidad' => $validated['cantidad'],
    //             'cantidad_anterior' => $cantidad_anterior,
    //             'cantidad_nueva' => $mobiliario->cantidad_disponible,
    //             'usuario' => auth()->user()->name ?? auth()->user()->email ?? 'Sistema',
    //             'observaciones' => $validated['observaciones']
    //         ]);

    //         DB::commit();

    //         return redirect()->back()
    //             ->with('success', 'Mobiliario marcado en reparación.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return redirect()->back()
    //             ->with('error', 'Error: ' . $e->getMessage());
    //     }
    // }

    // public function finalizarReparacion(Request $request, Mobiliario $mobiliario)
    // {
    //     $validated = $request->validate([
    //         'cantidad' => 'required|integer|min:1',
    //         'observaciones' => 'nullable|string',
    //     ]);

    //     try {
    //         DB::beginTransaction();

    //         if ($mobiliario->cantidad_en_reparacion < $validated['cantidad']) {
    //             throw new \Exception('La cantidad excede los equipos en reparación');
    //         }

    //         $cantidad_anterior = $mobiliario->cantidad_disponible;
    //         $mobiliario->cantidad_en_reparacion -= $validated['cantidad'];
    //         $mobiliario->cantidad_disponible += $validated['cantidad'];
    //         $mobiliario->save();

    //         \App\Models\MobiliarioHistorial::create([
    //             'id_mobiliario' => $mobiliario->id_mobiliario,
    //             'tipo_movimiento' => 'entrada',
    //             'cantidad' => $validated['cantidad'],
    //             'cantidad_anterior' => $cantidad_anterior,
    //             'cantidad_nueva' => $mobiliario->cantidad_disponible,
    //             'usuario' => auth()->user()->name ?? auth()->user()->email ?? 'Sistema',
    //             'observaciones' => 'Retorno de reparación: ' . ($validated['observaciones'] ?? '')
    //         ]);

    //         DB::commit();

    //         return redirect()->back()
    //             ->with('success', 'Reparación finalizada. Stock actualizado.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return redirect()->back()
    //             ->with('error', 'Error: ' . $e->getMessage());
    //     }
    // }
}
