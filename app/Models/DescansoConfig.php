<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DescansoConfig extends Model
{
    protected $table = 'descanso_config';

    protected $fillable = [
        'psicologa_id',
        'tipo',
        'duracion_sesion',
        'descanso_activo',
        'descanso_minutos',
    ];

    protected function casts(): array
    {
        return [
            'descanso_activo' => 'boolean',
        ];
    }

    public function psicologa()
    {
        return $this->belongsTo(Psicologa::class);
    }
}
