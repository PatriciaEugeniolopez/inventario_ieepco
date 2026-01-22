<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'empleados';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_empleado';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre_empleado',
        'apellido_p',
        'apellido_m',
        'puesto',
        'fk_area_trabajo',
    ];

    /**
     * Relación con AreaAsignacion
     */
    public function areaAsignacion()
    {
        return $this->belongsTo(AreaAsignacion::class, 'fk_area_trabajo', 'id_asignacion');
    }

    /**
     * Accessor para obtener el nombre completo del empleado
     */
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombre_empleado} {$this->apellido_p} {$this->apellido_m}");
    }
}