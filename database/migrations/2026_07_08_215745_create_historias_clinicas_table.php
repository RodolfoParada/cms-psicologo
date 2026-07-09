<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historias_clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('psicologa_id')->constrained('psicologas')->cascadeOnDelete();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->date('fecha_sesion');
            $table->longText('contenido')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historias_clinicas');
    }
};
