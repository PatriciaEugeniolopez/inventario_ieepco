<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $table = 'modelo';

    protected $fillable = [
        'nombre_modelo',
        'fk_marca',
        'status'
    ];

    public $timestamps = false;

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'fk_marca');
    }
}
