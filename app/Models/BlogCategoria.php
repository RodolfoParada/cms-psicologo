<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogCategoria extends Model
{
    protected $table = 'blog_categorias';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
    ];

    public function articulos()
    {
        return $this->hasMany(BlogArticulo::class, 'categoria_id');
    }

    protected static function booted()
    {
        static::creating(function ($categoria) {
            if (empty($categoria->slug)) {
                $categoria->slug = Str::slug($categoria->nombre);
            }
        });
    }
}
