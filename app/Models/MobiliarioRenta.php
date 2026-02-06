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
        'num_serie',
        'fecha_inicio',
        'fecha_fin',
        'fecha_devolucion',
        'cantidad',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_devolucion' => 'date',
    ];

    // ==================== RELACIONES ====================
    
    /**
     * Relación con Mobiliario
     */
    public function mobiliario()
    {
        return $this->belongsTo(Mobiliario::class, 'id_mobiliario', 'id_mobiliario');
    }

    // ==================== ACCESORES ====================
    
    /**
     * Obtener el proveedor a través del mobiliario
     */
    public function getProveedorAttribute()
    {
        return $this->mobiliario ? $this->mobiliario->proveedor : null;
    }

    /**
     * Obtener el modelo a través del mobiliario
     */
    public function getModeloAttribute()
    {
        return $this->mobiliario ? $this->mobiliario->modelo : null;
    }

    /**
     * Obtener la marca a través del mobiliario
     */
    public function getMarcaAttribute()
    {
        return $this->mobiliario ? $this->mobiliario->marca : null;
    }

    /**
     * Determinar si la renta está activa
     * Una renta está activa si no tiene fecha de devolución
     */
    public function getEstaActivaAttribute()
    {
        return is_null($this->fecha_devolucion);
    }

    /**
     * Obtener el estado de la renta
     */
    public function getEstadoRentaAttribute()
    {
        if (is_null($this->fecha_devolucion)) {
            // Si no hay fecha de devolución, está activa
            if ($this->fecha_fin < now()) {
                return 'vencida';
            }
            return 'activa';
        }
        return 'finalizada';
    }

    // ==================== SCOPES ====================
    
    /**
     * Scope para rentas activas (sin fecha de devolución)
     */
    public function scopeActivas($query)
    {
        return $query->whereNull('fecha_devolucion');
    }

    /**
     * Scope para rentas finalizadas (con fecha de devolución)
     */
    public function scopeFinalizadas($query)
    {
        return $query->whereNotNull('fecha_devolucion');
    }

    /**
     * Scope para rentas próximas a vencer
     */
    public function scopeProximasVencer($query, $dias = 30)
    {
        return $query->whereNull('fecha_devolucion')
                     ->whereBetween('fecha_fin', [now(), now()->addDays($dias)]);
    }

    /**
     * Scope para rentas vencidas
     */
    public function scopeVencidas($query)
    {
        return $query->whereNull('fecha_devolucion')
                     ->where('fecha_fin', '<', now());
    }

    // ==================== MÉTODOS AUXILIARES ====================
    
    /**
     * Calcular días restantes hasta la fecha fin
     */
    public function diasRestantes()
    {
        if ($this->fecha_devolucion) {
            return 0; // Ya fue devuelto
        }
        
        return now()->diffInDays($this->fecha_fin, false);
    }

    /**
     * Verificar si la renta está vencida
     */
    public function estaVencida()
    {
        return is_null($this->fecha_devolucion) && $this->fecha_fin < now();
    }

    /**
     * Calcular duración en días
     */
    public function duracionDias()
    {
        return $this->fecha_inicio->diffInDays($this->fecha_fin);
    }

    /**
     * Calcular duración en meses (aproximado)
     */
    public function duracionMeses()
    {
        return ceil($this->duracionDias() / 30);
    }
}