<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disponibilidad extends Model
{
    protected $table = 'disponibilidad';

    protected $fillable = [
        'psicologa_id',
        'tipo',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
    ];

    public function psicologa()
    {
        return $this->belongsTo(Psicologa::class);
    }

    public static function diasSemana(): array
    {
        return ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    }
}
