<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('carpeta')->unique();
            $table->text('descripcion')->nullable();
            $table->string('preview')->nullable();
            $table->string('color_primario')->default('#6366f1');
            $table->string('color_secundario')->default('#8b5cf6');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temas');
    }
};
