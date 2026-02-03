<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthAssignment extends Model
{
    protected $table = 'auth_assignment';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'item_name',
        'user_id',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function authItem()
    {
        return $this->belongsTo(AuthItem::class, 'item_name', 'name');
    }
}