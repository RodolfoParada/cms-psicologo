<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redes_sociales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('psicologa_id')->constrained('psicologas')->cascadeOnDelete();
            $table->string('plataforma', 50);
            $table->string('url', 500)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->unique(['psicologa_id', 'plataforma']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redes_sociales');
    }
};
