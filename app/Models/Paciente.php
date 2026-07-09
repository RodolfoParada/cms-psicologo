<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';

    protected $fillable = [
        'psicologa_id',
        'nombre',
        'telefono',
        'email',
        'direccion',
        'fecha_nacimiento',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
        ];
    }

    public function psicologa()
    {
        return $this->belongsTo(Psicologa::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'paciente_telefono', 'telefono')
            ->where('psicologa_id', $this->psicologa_id);
    }
}
