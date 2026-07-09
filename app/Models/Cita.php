<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';

    protected $fillable = [
        'psicologa_id',
        'paciente_nombre',
        'paciente_telefono',
        'paciente_email',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'tipo',
        'estado',
        'motivo',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function psicologa()
    {
        return $this->belongsTo(Psicologa::class);
    }
}
