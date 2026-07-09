<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('disponibilidad', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('psicologa_id');
            $table->string('tipo'); // online, presencial
            $table->unsignedTinyInteger('dia_semana'); // 0=domingo, 1=lunes... 6=sabado
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();

            $table->foreign('psicologa_id')->references('id')->on('psicologas')->onDelete('cascade');
            $table->index(['psicologa_id', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disponibilidad');
    }
};
