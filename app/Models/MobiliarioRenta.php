<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobiliarioRenta extends Model
{
    use HasFactory;

    protected $table = 'mobiliario_renta';
    protected $primaryKey = 'id_renta';

    protected $fillable = [
        'id_mobiliario',
        'id_proveedor',
        'fecha_inicio',
        'fecha_fin',
        'fecha_devolucion',
        'estado_renta',
        'notas',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_devolucion' => 'date',
        'costo_mensual' => 'decimal:2',
        'costo_total' => 'decimal:2',
        'deposito' => 'decimal:2',
    ];

    // Relaciones
    public function mobiliario()
    {
        return $this->belongsTo(Mobiliario::class, 'id_mobiliario', 'id_mobiliario');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_prov');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('estado_renta', 'activa');
    }

    public function scopeFinalizadas($query)
    {
        return $query->where('estado_renta', 'finalizada');
    }

    public function scopeProximasVencer($query, $dias = 30)
    {
        return $query->where('estado_renta', 'activa')
                     ->whereBetween('fecha_fin', [now(), now()->addDays($dias)]);
    }

    public function scopeVencidas($query)
    {
        return $query->where('estado_renta', 'activa')
                     ->where('fecha_fin', '<', now());
    }

    // Métodos auxiliares
    public function diasRestantes()
    {
        return now()->diffInDays($this->fecha_fin, false);
    }

    public function estaVencida()
    {
        return $this->estado_renta === 'activa' && $this->fecha_fin < now();
    }

    public function calcularCostoTotal()
    {
        $meses = $this->fecha_inicio->diffInMonths($this->fecha_fin);
        return $this->costo_mensual * $meses;
    }
}