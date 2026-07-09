<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogArticulo extends Model
{
    protected $table = 'blog_articulos';

    protected $fillable = [
        'psicologa_id',
        'categoria_id',
        'titulo',
        'slug',
        'contenido',
        'extracto',
        'imagen',
        'publicado',
        'fecha_publicacion',
    ];

    protected function casts(): array
    {
        return [
            'publicado' => 'boolean',
            'fecha_publicacion' => 'datetime',
        ];
    }

    public function psicologa()
    {
        return $this->belongsTo(Psicologa::class);
    }

    public function categoria()
    {
        return $this->belongsTo(BlogCategoria::class, 'categoria_id');
    }

    protected static function booted()
    {
        static::creating(function ($articulo) {
            if (empty($articulo->slug)) {
                $articulo->slug = Str::slug($articulo->titulo) . '-' . uniqid();
            }
        });
    }
}
