<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'psicologa_id',
        'nombre',
        'descripcion',
        'icono',
        'imagen',
        'orden',
    ];

    public function psicologa()
    {
        return $this->belongsTo(Psicologa::class);
    }
}
