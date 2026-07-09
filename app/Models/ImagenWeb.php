<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ImagenWeb extends Model
{
    protected $table = 'imagenes_web';

    protected $fillable = [
        'psicologa_id',
        'clave',
        'archivo',
        'titulo',
        'archivo_original',
    ];

    public function psicologa()
    {
        return $this->belongsTo(Psicologa::class);
    }

    public function url()
    {
        return Storage::disk('public')->url($this->archivo);
    }

    public static function slots(): array
    {
        return [
            'logo' => ['titulo' => 'Logo', 'descripcion' => 'Logo principal de la web', 'default' => 'themes/tema-base/img/logo.png'],
            'favicon' => ['titulo' => 'Favicon', 'descripcion' => 'Icono de pestaña del navegador', 'default' => null],
            'psicologa' => ['titulo' => 'Foto psicóloga', 'descripcion' => 'Foto principal de la psicóloga (sin fondo idealmente)', 'default' => 'themes/tema-base/img/psicologa.png'],
            'hero' => ['titulo' => 'Hero / Banner', 'descripcion' => 'Imagen de fondo de la sección principal', 'default' => null],
            'sobre_mi' => ['titulo' => 'Sobre mí', 'descripcion' => 'Imagen de la sección "Sobre mí"', 'default' => 'themes/tema-base/img/why.jpg'],
            'servicio_1' => ['titulo' => 'Servicio 1', 'descripcion' => 'Imagen para el primer servicio', 'default' => 'themes/tema-base/img/servicio1.jpg'],
            'servicio_2' => ['titulo' => 'Servicio 2', 'descripcion' => 'Imagen para el segundo servicio', 'default' => 'themes/tema-base/img/servicio2.jpg'],
            'servicio_3' => ['titulo' => 'Servicio 3', 'descripcion' => 'Imagen para el tercer servicio', 'default' => 'themes/tema-base/img/servicio3.jpg'],
            'servicio_4' => ['titulo' => 'Servicio 4', 'descripcion' => 'Imagen para el cuarto servicio', 'default' => 'themes/tema-base/img/servicio4.jpg'],
            'blog_1' => ['titulo' => 'Blog 1', 'descripcion' => 'Imagen por defecto para artículos del blog', 'default' => 'themes/tema-base/img/blog1.jpg'],
            'blog_2' => ['titulo' => 'Blog 2', 'descripcion' => 'Imagen por defecto para artículos del blog', 'default' => 'themes/tema-base/img/blog2.jpg'],
            'blog_3' => ['titulo' => 'Blog 3', 'descripcion' => 'Imagen por defecto para artículos del blog', 'default' => 'themes/tema-base/img/blog3.jpg'],
            'testimonial' => ['titulo' => 'Testimonios', 'descripcion' => 'Imagen de fondo de la sección de testimonios', 'default' => null],
            'contacto' => ['titulo' => 'Contacto', 'descripcion' => 'Imagen de la sección de contacto', 'default' => null],
            'bg_services' => ['titulo' => 'Fondo servicios', 'descripcion' => 'Imagen de fondo de la sección de servicios', 'default' => 'themes/tema-base/img/bg-services.png'],
            'bg_stats' => ['titulo' => 'Fondo estadísticas', 'descripcion' => 'Imagen de fondo de la sección de estadísticas', 'default' => 'themes/tema-base/img/bg-stats.png'],
            'person' => ['titulo' => 'Persona ilustrativa', 'descripcion' => 'Imagen decorativa de persona', 'default' => 'themes/tema-base/img/person.jpg'],
        ];
    }
}
