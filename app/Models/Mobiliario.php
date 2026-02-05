<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobiliario extends Model
{
    use HasFactory;

    protected $table = 'mobiliario';
    protected $primaryKey = 'id_mobiliario';
    public $timestamps = false;

    protected $fillable = [
        'clave_inventario',
        'nombre_mobiliario',
        'id_modelofk',
        'id_marcafk',
        'num_serie',
        'status_fk',
        'fk_asignacion',
        'usuario_area',
        'fecha_compra',
        'precio_unitario',
        'fk_provedor',
        'cod_informatica'
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marcafk');
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'id_modelofk');
    }

    public function areaAsignacion()
    {
        return $this->belongsTo(AreaAsignacion::class, 'fk_asignacion', 'id_asignacion');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_area');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'fk_provedor', 'id_prov');
    }
}
