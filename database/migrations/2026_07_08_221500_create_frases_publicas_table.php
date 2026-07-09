<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frases_publicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('psicologa_id')->constrained('psicologas')->cascadeOnDelete();
            $table->string('clave', 100);
            $table->text('valor')->nullable();
            $table->string('defecto')->nullable();
            $table->timestamps();
            $table->unique(['psicologa_id', 'clave']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frases_publicas');
    }
};
