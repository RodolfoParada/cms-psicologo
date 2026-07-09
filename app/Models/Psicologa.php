<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Psicologa extends Authenticatable
{
    use Notifiable;

    protected $table = 'psicologas';

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'password',
        'slogan',
        'numero_colegiado',
        'telefono_citas',
        'email_citas',
        'sobre_mi',
        'foto',
        'direccion',
        'ciudad',
        'codigo_postal',
        'pais',
        'tema_id',
        'modo_vacaciones',
        'blog_activo',
        'reservas_activo',
        'faq_activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'modo_vacaciones' => 'boolean',
            'blog_activo' => 'boolean',
            'reservas_activo' => 'boolean',
            'faq_activo' => 'boolean',
        ];
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombre . ' ' . $this->apellidos);
    }
}
