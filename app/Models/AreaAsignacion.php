<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaAsignacion extends Model
{
    use HasFactory;

    protected $table = 'area_asignacion';

    protected $primaryKey = 'id_asignacion';

    public $timestamps = false;

    protected $fillable = [
        'nombre_asignacion',
        'responsable_area',
    ];
}