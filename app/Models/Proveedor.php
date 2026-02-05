<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedor';

    protected $primaryKey = 'id_prov';

    public $timestamps = false;

    protected $fillable = [
        'nombre_prov',
        'rfc',
        'telefono',
        'calle',
        'numero_ext',
        'numero_int',
        'colonia',
        'municipio',
        'estado',
        'pais',
        'codigo_postal',
    ];

    public function getRouteKeyName()
    {
        return 'id_prov';
    }

    protected $casts = [
        'numero_ext' => 'integer',
        'numero_int' => 'integer',
        'codigo_postal' => 'integer',
    ];
}
