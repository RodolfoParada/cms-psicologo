<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrasePublica extends Model
{
    protected $table = 'frases_publicas';

    protected $fillable = [
        'psicologa_id',
        'clave',
        'valor',
        'defecto',
    ];

    public function psicologa()
    {
        return $this->belongsTo(Psicologa::class);
    }

    public static function slots(): array
    {
        return [
            'hero_titulo' => ['titulo' => 'Título principal', 'defecto' => 'Tu espacio de confianza para el bienestar emocional'],
            'hero_subtitulo' => ['titulo' => 'Subtítulo hero', 'defecto' => 'Psicóloga colegiada especialista en bienestar emocional'],
            'hero_btn' => ['titulo' => 'Botón hero', 'defecto' => 'Pide tu cita'],
            'sobre_mi_titulo' => ['titulo' => 'Título "Sobre mí"', 'defecto' => 'Sobre mí'],
            'servicios_titulo' => ['titulo' => 'Título servicios', 'defecto' => 'Mis servicios'],
            'servicios_desc' => ['titulo' => 'Descripción servicios', 'defecto' => 'Te ofrezco un espacio seguro y profesional para tu bienestar'],
            'especialidades_titulo' => ['titulo' => 'Título especialidades', 'defecto' => 'Mis especialidades'],
            'precios_titulo' => ['titulo' => 'Título precios', 'defecto' => 'Planes y precios'],
            'testimonios_titulo' => ['titulo' => 'Título testimonios', 'defecto' => 'Lo que dicen mis pacientes'],
            'blog_titulo' => ['titulo' => 'Título blog', 'defecto' => 'Últimas publicaciones'],
            'faq_titulo' => ['titulo' => 'Título FAQ', 'defecto' => 'Preguntas frecuentes'],
            'contacto_titulo' => ['titulo' => 'Título contacto', 'defecto' => 'Contacta conmigo'],
            'footer_texto' => ['titulo' => 'Texto footer', 'defecto' => 'Todos los derechos reservados'],
            'cta_texto' => ['titulo' => 'Texto CTA final', 'defecto' => '¿Listo para empezar tu camino hacia el bienestar?'],
            'cta_btn' => ['titulo' => 'Botón CTA final', 'defecto' => 'Solicita tu primera cita'],
        ];
    }
}
