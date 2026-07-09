<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    protected $table = 'temas';

    protected $fillable = [
        'nombre',
        'carpeta',
        'descripcion',
        'preview',
        'color_primario',
        'color_secundario',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }
}
