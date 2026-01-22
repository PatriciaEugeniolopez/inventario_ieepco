<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'status',
        'fk_idempleado',
    ];

    protected $hidden = [
        'password_hash',
        'auth_key',
        'password_reset_token',
    ];

    /**
     * Laravel usará este campo como contraseña
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password_hash'] = $value;
    }
}
