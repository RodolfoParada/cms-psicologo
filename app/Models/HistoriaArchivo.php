<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriaArchivo extends Model
{
    protected $table = 'historia_archivos';

    protected $fillable = [
        'historia_clinica_id',
        'nombre_original',
        'archivo_path',
        'tipo',
    ];

    public function historiaClinica()
    {
        return $this->belongsTo(HistoriaClinica::class, 'historia_clinica_id');
    }
}
