<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionWeb extends Model
{
    protected $table = 'configuracion_web';

    protected $fillable = [
        'psicologa_id',
        'modo_visualizacion',
        'color_primario',
        'color_secundario',
        'logo',
        'favicon',
        'meta_descripcion',
        'meta_keywords',
        'plantilla_proteccion_datos',
        'google_maps_url',
        'mostrar_blog',
        'mostrar_faq',
        'mostrar_reservas',
        'mostrar_especialidades',
        'mostrar_servicios',
        'mostrar_testimonios',
        'dashboard_tema',
        'dashboard_color',
    ];

    protected function casts(): array
    {
        return [
            'mostrar_blog' => 'boolean',
            'mostrar_faq' => 'boolean',
            'mostrar_reservas' => 'boolean',
            'mostrar_especialidades' => 'boolean',
            'mostrar_servicios' => 'boolean',
            'mostrar_testimonios' => 'boolean',
        ];
    }

    public function psicologa()
    {
        return $this->belongsTo(Psicologa::class);
    }
}
