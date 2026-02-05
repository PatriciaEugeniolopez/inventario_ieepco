<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthItem extends Model
{
    protected $table = 'auth_item';
    protected $primaryKey = 'name';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    const TYPE_ROLE = 1;
    const TYPE_PERMISSION = 2;

    protected $fillable = [
        'name',
        'type',
        'description',
        'rule_name',
        'data',
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'auth_assignment',
            'item_name',
            'user_id'
        )->withPivot('created_at');
    }

    public function scopeRoles($query)
    {
        return $query->where('type', self::TYPE_ROLE);
    }

    public function scopePermissions($query)
    {
        return $query->where('type', self::TYPE_PERMISSION);
    }
}