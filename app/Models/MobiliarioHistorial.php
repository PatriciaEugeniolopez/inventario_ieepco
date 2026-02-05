<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobiliarioHistorial extends Model
{
    use HasFactory;

    protected $table = 'mobiliario_historial';
    protected $primaryKey = 'id_historial';
    public $timestamps = false;

    protected $fillable = [
        'id_mobiliario',
        'tipo_movimiento',
        'cantidad',
        'cantidad_anterior',
        'cantidad_nueva',
        'usuario',
        'referencia_id',
        'tipo_referencia',
        'observaciones',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'cantidad_anterior' => 'integer',
        'cantidad_nueva' => 'integer',
    ];

    // Relaciones
    public function mobiliario()
    {
        return $this->belongsTo(Mobiliario::class, 'id_mobiliario', 'id_mobiliario');
    }
}