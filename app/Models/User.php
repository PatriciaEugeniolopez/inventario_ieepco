<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\AuthAssignment;
use App\Models\AuthItem;


class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'fk_idempleado',
        'nombre',
        'password_hash',
        'auth_key',
        'email',
        'status',
        'created_at',
        'updated_at',
    ];


    protected $hidden = [
        'password_hash',
        'auth_key',
        'password_reset_token',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password_hash'] = $value;
    }


    // Relación con empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'fk_idempleado', 'id_empleado');
    }

    public function authItems()
    {
        return $this->belongsToMany(
            AuthItem::class,
            'auth_assignment',
            'user_id',
            'item_name'
        )->withPivot('created_at');
    }

    public function roles()
    {
        return $this->belongsToMany(
            AuthItem::class,
            'auth_assignment',
            'user_id',
            'item_name'
        )->where('type', AuthItem::TYPE_ROLE);
    }


    public function permissions()
    {
        return $this->belongsToMany(
            AuthItem::class,
            'auth_assignment',
            'user_id',
            'item_name'
        )->where('type', AuthItem::TYPE_PERMISSION);
    }

    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }


    public function hasAnyRole(array $roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function hasAllRoles(array $roles)
    {
        return $this->roles()->whereIn('name', $roles)->count() === count($roles);
    }

    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    public function hasAnyPermission(array $permissions)
    {
        return $this->permissions()->whereIn('name', $permissions)->exists();
    }

    // Asignaciones que registró
    public function asignacionesRegistradas()
    {
        // return $this->hasMany(MobiliarioAsignacion::class, 'usuario_registra_id', 'id');
    }

    // Historial de movimientos
    public function movimientosRealizados()
    {
        // return $this->hasMany(MobiliarioHistorial::class, 'user_id', 'id');
    }

    // Rentas que registró
    public function rentasRegistradas()
    {
        // return $this->hasMany(MobiliarioRenta::class, 'usuario_registra_id', 'id');

    }


    public function assignRole($roleName)
    {
        $role = AuthItem::where('name', $roleName)
            ->where('type', AuthItem::TYPE_ROLE)
            ->first();

        if ($role && !$this->hasRole($roleName)) {
            AuthAssignment::create([
                'item_name' => $roleName,
                'user_id' => $this->id,
                'created_at' => time(),
            ]);
        }

        return $this;
    }

    public function assignPermission($permissionName)
    {
        $permission = AuthItem::where('name', $permissionName)
            ->where('type', AuthItem::TYPE_PERMISSION)
            ->first();

        if ($permission && !$this->hasPermission($permissionName)) {
            AuthAssignment::create([
                'item_name' => $permissionName,
                'user_id' => $this->id,
                'created_at' => time(),
            ]);
        }

        return $this;
    }


    public function removeRole($roleName)
    {
        AuthAssignment::where('user_id', $this->id)
            ->where('item_name', $roleName)
            ->delete();

        return $this;
    }

    public function removePermission($permissionName)
    {
        AuthAssignment::where('user_id', $this->id)
            ->where('item_name', $permissionName)
            ->delete();

        return $this;
    }

    public function syncRoles(array $roleNames)
    {
        // Eliminar roles actuales
        AuthAssignment::where('user_id', $this->id)
            ->whereHas('authItem', function ($query) {
                $query->where('type', AuthItem::TYPE_ROLE);
            })
            ->delete();

        // Asignar nuevos roles
        foreach ($roleNames as $roleName) {
            $this->assignRole($roleName);
        }

        return $this;
    }

    public function syncPermissions(array $permissionNames)
    {
        // Eliminar permisos actuales
        AuthAssignment::where('user_id', $this->id)
            ->whereHas('authItem', function ($query) {
                $query->where('type', AuthItem::TYPE_PERMISSION);
            })
            ->delete();

        // Asignar nuevos permisos
        foreach ($permissionNames as $permissionName) {
            $this->assignPermission($permissionName);
        }

        return $this;
    }

    public function getRoleNames()
    {
        return $this->roles()->pluck('name')->toArray();
    }

    public function getPermissionNames()
    {
        return $this->permissions()->pluck('name')->toArray();
    }
}
