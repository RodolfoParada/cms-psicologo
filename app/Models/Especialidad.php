<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidades';

    protected $fillable = [
        'psicologa_id',
        'nombre',
        'descripcion',
        'icono',
        'orden',
    ];

    public function psicologa()
    {
        return $this->belongsTo(Psicologa::class);
    }
}
