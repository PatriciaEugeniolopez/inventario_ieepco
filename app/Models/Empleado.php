<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';
    public $timestamps = false;

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
     * Relación con Usuario (un empleado puede tener un usuario)
     */
    public function usuario()
    {
        return $this->hasOne(User::class, 'fk_idempleado', 'id_empleado');
    }

    /**
     * Verificar si el empleado ya tiene un usuario asignado
     */
    public function tieneUsuario()
    {
        return $this->usuario()->exists();
    }

    /**
     * Accessor para obtener el nombre completo del empleado
     */
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombre_empleado} {$this->apellido_p} {$this->apellido_m}");
    }
}