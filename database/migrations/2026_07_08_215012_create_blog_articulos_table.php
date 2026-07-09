<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_articulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('psicologa_id')->constrained('psicologas')->cascadeOnDelete();
            $table->foreignId('categoria_id')->nullable()->constrained('blog_categorias')->nullOnDelete();
            $table->string('titulo', 200);
            $table->string('slug', 220)->unique();
            $table->longText('contenido');
            $table->string('extracto', 300)->nullable();
            $table->string('imagen', 255)->nullable();
            $table->boolean('publicado')->default(false);
            $table->timestamp('fecha_publicacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_articulos');
    }
};
