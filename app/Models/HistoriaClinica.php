<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HistoriaClinica extends Model
{
    protected $table = 'historias_clinicas';

    protected $fillable = [
        'psicologa_id',
        'paciente_id',
        'fecha_sesion',
        'contenido',
    ];

    protected function casts(): array
    {
        return [
            'fecha_sesion' => 'date',
        ];
    }

    public function psicologa()
    {
        return $this->belongsTo(Psicologa::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function archivos()
    {
        return $this->hasMany(HistoriaArchivo::class, 'historia_clinica_id');
    }

    protected static function booted()
    {
        static::deleting(function ($historia) {
            foreach ($historia->archivos as $archivo) {
                Storage::disk('public')->delete($archivo->archivo_path);
            }
        });
    }
}
