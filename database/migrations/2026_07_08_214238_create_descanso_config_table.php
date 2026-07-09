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
        Schema::create('descanso_config', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('psicologa_id');
            $table->string('tipo'); // online, presencial
            $table->integer('duracion_sesion'); // minutos
            $table->boolean('descanso_activo')->default(false);
            $table->integer('descanso_minutos')->default(0);
            $table->timestamps();

            $table->foreign('psicologa_id')->references('id')->on('psicologas')->onDelete('cascade');
            $table->unique(['psicologa_id', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descanso_config');
    }
};
