<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('slug', 120)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        $categorias = [
            'Bienestar emocional',
            'Psicología clínica',
            'Crecimiento personal',
            'Pareja y familia',
            'Infancia y adolescencia',
            'Ansiedad y estrés',
            'Autoestima',
            'Mindfulness',
        ];

        foreach ($categorias as $nombre) {
            DB::table('blog_categorias')->insert([
                'nombre' => $nombre,
                'slug' => Str::slug($nombre),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_categorias');
    }
};
