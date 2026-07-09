<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    protected $table = 'precios';

    protected $fillable = [
        'psicologa_id',
        'tipo',
        'nombre',
        'precio_mensual',
        'precio_anual',
        'descripcion',
        'duracion',
        'orden',
    ];

    public function psicologa()
    {
        return $this->belongsTo(Psicologa::class);
    }
}
