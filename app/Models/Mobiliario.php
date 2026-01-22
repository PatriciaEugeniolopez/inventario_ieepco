<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mobiliario extends Model
{
    protected $table = 'mobiliario';
    protected $primaryKey = 'id_mobiliario';
    public $timestamps = false;

    protected $fillable = [
        'nombre_mobiliario',
        'clave_inventario',
        'num_serie',
        'fecha_compra',
        'precio_unitario',
        'status_fk',
        'fk_asignacion',
        'usuario_area',
        'id_marcafk',
        'id_modelofk',
        'cod_informatica',
        'fk_provedor',
    ];

    /* ================= RELACIONES ================= */

    /*public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marcafk', 'id');
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'id_modelofk', 'id');
    }

    public function asignacion()
    {
        return $this->belongsTo(AreaAsignacion::class, 'fk_asignacion', 'id_asignacion');
    }

    public function personal()
    {
        return $this->belongsTo(Empleado::class, 'usuario_area', 'id_empleado');
    }

    public function condiciones()
    {
        return $this->belongsTo(Condicion::class, 'status_fk', 'id_status');
    }*/
}
