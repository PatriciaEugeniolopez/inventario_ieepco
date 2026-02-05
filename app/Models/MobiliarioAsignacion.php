<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Mobiliario;
use App\Models\Empleado;

class MobiliarioAsignacion extends Model
{
    use HasFactory;

    protected $table = 'mobiliario_asignacion';
    protected $primaryKey = 'id_asignacion_mob';

    protected $fillable = [
        'id_mobiliario',
        'id_empleado',
        'fk_area_asignacion',
        'cantidad',
        'fecha_asignacion',
        'fecha_devolucion',
        'estado_asignacion',
        'ubicacion',
        'observaciones',
        'usuario_registra_id',
        'responsable_entrega',
        'responsable_recepcion',
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'fecha_devolucion' => 'date',
        'cantidad' => 'integer',
    ];

    // Relaciones
    public function mobiliario()
    {
        return $this->belongsTo(Mobiliario::class, 'id_mobiliario', 'id_mobiliario');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    public function area()
    {
        return $this->belongsTo(AreaAsignacion::class, 'fk_area_asignacion', 'id_asignacion');
    }

    public function usuarioRegistra()
    {
        return $this->belongsTo(User::class, 'usuario_registra_id', 'id');
    }

    // Scopes
    public function scopeAsignados($query)
    {
        return $query->where('estado_asignacion', 'asignado');
    }

    public function scopeDevueltos($query)
    {
        return $query->where('estado_asignacion', 'devuelto');
    }

    // Eventos
    protected static function boot()
    {
        parent::boot();

        // Al crear asignación
        static::creating(function ($asignacion) {
            // Asignar usuario que registra automáticamente
            if (!$asignacion->usuario_registra_id && auth()->check) {
                $asignacion->usuario_registra_id = auth()->id;
            }

            DB::transaction(function () use ($asignacion) {
                $mobiliario = Mobiliario::lockForUpdate()
                    ->findOrFail($asignacion->id_mobiliario);
                
                if ($mobiliario->cantidad_disponible < $asignacion->cantidad) {
                    throw new \Exception('Stock insuficiente. Disponible: ' . $mobiliario->cantidad_disponible);
                }

                $cantidadAnterior = $mobiliario->cantidad_disponible;
                $mobiliario->cantidad_disponible -= $asignacion->cantidad;
                $mobiliario->cantidad_asignada += $asignacion->cantidad;
                $mobiliario->save();

                // Registrar en historial
                MobiliarioHistorial::create([
                    'id_mobiliario' => $mobiliario->id_mobiliario,
                    'tipo_movimiento' => 'asignacion',
                    'cantidad' => $asignacion->cantidad,
                    'cantidad_anterior' => $cantidadAnterior,
                    'cantidad_nueva' => $mobiliario->cantidad_disponible,
                    'user_id' => auth()->id,
                    'referencia_id' => $asignacion->id_asignacion_mob,
                    'tipo_referencia' => 'asignacion',
                    'observaciones' => 'Asignación a: ' . $asignacion->empleado->nombre_completo
                ]);
            });
        });

        // Al devolver
        static::updating(function ($asignacion) {
            if ($asignacion->isDirty('estado_asignacion') && 
                $asignacion->estado_asignacion === 'devuelto' &&
                $asignacion->getOriginal('estado_asignacion') === 'asignado') {
                
                DB::transaction(function () use ($asignacion) {
                    $mobiliario = Mobiliario::lockForUpdate()
                        ->findOrFail($asignacion->id_mobiliario);
                    
                    $cantidadAnterior = $mobiliario->cantidad_disponible;
                    $mobiliario->cantidad_disponible += $asignacion->cantidad;
                    $mobiliario->cantidad_asignada -= $asignacion->cantidad;
                    $mobiliario->save();

                    MobiliarioHistorial::create([
                        'id_mobiliario' => $mobiliario->id_mobiliario,
                        'tipo_movimiento' => 'devolucion',
                        'cantidad' => $asignacion->cantidad,
                        'cantidad_anterior' => $cantidadAnterior,
                        'cantidad_nueva' => $mobiliario->cantidad_disponible,
                        'user_id' => auth()->id,
                        'referencia_id' => $asignacion->id_asignacion_mob,
                        'tipo_referencia' => 'asignacion',
                        'observaciones' => 'Devolución de: ' . $asignacion->empleado->nombre_completo
                    ]);
                });
            }
        });
    }
}