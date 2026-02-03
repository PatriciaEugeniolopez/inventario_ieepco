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
    'nombre',
    'email',
    'password_hash',
    'auth_key',
    'status',
    'fk_idempleado',
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

    /**
     * Relación con el empleado
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'fk_idempleado', 'id_empleado');
    }

    // ========== RELACIONES RBAC ==========

    /**
     * Todos los items asignados (roles y permisos)
     */
    public function authItems()
    {
        return $this->belongsToMany(
            AuthItem::class,
            'auth_assignment',
            'user_id',
            'item_name'
        )->withPivot('created_at');
    }

    /**
     * Solo roles asignados
     */
    public function roles()
    {
        return $this->belongsToMany(
            AuthItem::class,
            'auth_assignment',
            'user_id',
            'item_name'
        )->where('type', AuthItem::TYPE_ROLE);
    }

    /**
     * Solo permisos asignados directamente
     */
    public function permissions()
    {
        return $this->belongsToMany(
            AuthItem::class,
            'auth_assignment',
            'user_id',
            'item_name'
        )->where('type', AuthItem::TYPE_PERMISSION);
    }

    // ========== MÉTODOS DE VERIFICACIÓN ==========

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Verificar si tiene alguno de los roles
     */
    public function hasAnyRole(array $roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Verificar si tiene todos los roles
     */
    public function hasAllRoles(array $roles)
    {
        return $this->roles()->whereIn('name', $roles)->count() === count($roles);
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     */
    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    /**
     * Verificar si tiene alguno de los permisos
     */
    public function hasAnyPermission(array $permissions)
    {
        return $this->permissions()->whereIn('name', $permissions)->exists();
    }

    // ========== MÉTODOS DE GESTIÓN ==========

    /**
     * Asignar un rol al usuario
     */
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

    /**
     * Asignar un permiso al usuario
     */
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

    /**
     * Remover un rol del usuario
     */
    public function removeRole($roleName)
    {
        AuthAssignment::where('user_id', $this->id)
            ->where('item_name', $roleName)
            ->delete();

        return $this;
    }

    /**
     * Remover un permiso del usuario
     */
    public function removePermission($permissionName)
    {
        AuthAssignment::where('user_id', $this->id)
            ->where('item_name', $permissionName)
            ->delete();

        return $this;
    }

    /**
     * Sincronizar roles (reemplaza todos los roles actuales)
     */
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

    /**
     * Sincronizar permisos (reemplaza todos los permisos actuales)
     */
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

    /**
     * Obtener todos los nombres de roles
     */
    public function getRoleNames()
    {
        return $this->roles()->pluck('name')->toArray();
    }

    /**
     * Obtener todos los nombres de permisos
     */
    public function getPermissionNames()
    {
        return $this->permissions()->pluck('name')->toArray();
    }
}
