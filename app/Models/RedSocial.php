<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedSocial extends Model
{
    protected $table = 'redes_sociales';

    protected $fillable = [
        'psicologa_id',
        'plataforma',
        'url',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function psicologa()
    {
        return $this->belongsTo(Psicologa::class);
    }

    public static function plataformas(): array
    {
        return [
            'instagram' => ['nombre' => 'Instagram', 'icono' => 'fa-instagram', 'color' => '#E4405F'],
            'facebook' => ['nombre' => 'Facebook', 'icono' => 'fa-facebook', 'color' => '#1877F2'],
            'twitter' => ['nombre' => 'Twitter / X', 'icono' => 'fa-twitter', 'color' => '#1DA1F2'],
            'linkedin' => ['nombre' => 'LinkedIn', 'icono' => 'fa-linkedin', 'color' => '#0A66C2'],
            'youtube' => ['nombre' => 'YouTube', 'icono' => 'fa-youtube', 'color' => '#FF0000'],
            'tiktok' => ['nombre' => 'TikTok', 'icono' => 'fa-tiktok', 'color' => '#000000'],
            'whatsapp' => ['nombre' => 'WhatsApp', 'icono' => 'fa-whatsapp', 'color' => '#25D366'],
            'telegram' => ['nombre' => 'Telegram', 'icono' => 'fa-telegram', 'color' => '#26A5E4'],
        ];
    }
}
