<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historia_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('historia_clinica_id')->constrained('historias_clinicas')->cascadeOnDelete();
            $table->string('nombre_original', 255);
            $table->string('archivo_path', 255);
            $table->string('tipo', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historia_archivos');
    }
};
