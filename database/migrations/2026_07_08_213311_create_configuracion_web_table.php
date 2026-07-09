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
        Schema::create('configuracion_web', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('psicologa_id')->unique();
            $table->string('modo_visualizacion')->default('multipagina'); // landing, multipagina
            $table->string('color_primario')->nullable();
            $table->string('color_secundario')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->text('meta_descripcion')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('google_maps_url')->nullable();
            $table->boolean('mostrar_blog')->default(true);
            $table->boolean('mostrar_faq')->default(true);
            $table->boolean('mostrar_reservas')->default(true);
            $table->boolean('mostrar_especialidades')->default(true);
            $table->boolean('mostrar_servicios')->default(true);
            $table->boolean('mostrar_testimonios')->default(true);
            $table->timestamps();

            $table->foreign('psicologa_id')->references('id')->on('psicologas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_web');
    }
};
