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

    // Usuario del sistema asociado
    public function user()
    {
        return $this->hasOne(User::class, 'fk_idempleado', 'id_empleado');
    }

    // Asignaciones de mobiliario a este empleado
    public function asignacionesMobiliario()
    {
        // return $this->hasMany(MobiliarioAsignacion::class, 'id_empleado', 'id_empleado');
    }

    // Asignaciones activas
    public function asignacionesActivas()
    {
        // return $this->hasMany(MobiliarioAsignacion::class, 'id_empleado', 'id_empleado')
                    // ->where('estado_asignacion', 'asignado');
    }

    
    public function areaAsignacion()
    {
        return $this->belongsTo(AreaAsignacion::class, 'fk_area_trabajo', 'id_asignacion');
    }

  
    public function usuario()
    {
        return $this->hasOne(User::class, 'fk_idempleado', 'id_empleado');
    }

    
    public function tieneUsuario()
    {
        return $this->usuario()->exists();
    }

    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombre_empleado} {$this->apellido_p} {$this->apellido_m}");
    }
}