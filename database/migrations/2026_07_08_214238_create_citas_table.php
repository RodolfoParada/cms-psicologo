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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('psicologa_id');
            $table->string('paciente_nombre');
            $table->string('paciente_telefono');
            $table->string('paciente_email')->nullable();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('tipo'); // online, presencial
            $table->string('estado')->default('pendiente'); // pendiente, confirmada, completada, cancelada
            $table->text('motivo')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->foreign('psicologa_id')->references('id')->on('psicologas')->onDelete('cascade');
            $table->index(['psicologa_id', 'fecha']);
            $table->index('paciente_telefono');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
