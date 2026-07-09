<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacacione extends Model
{
    protected $table = 'vacaciones';

    protected $fillable = [
        'psicologa_id',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function psicologa()
    {
        return $this->belongsTo(Psicologa::class);
    }
}
