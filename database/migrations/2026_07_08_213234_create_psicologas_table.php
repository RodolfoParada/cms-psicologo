<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('psicologas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('email')->unique();
            $table->string('telefono');
            $table->string('password');
            $table->rememberToken();
            $table->string('slogan')->nullable();
            $table->string('numero_colegiado')->nullable();
            $table->string('telefono_citas')->nullable();
            $table->string('email_citas')->nullable();
            $table->text('sobre_mi')->nullable();
            $table->string('foto')->nullable();
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('pais')->nullable();
            $table->unsignedBigInteger('tema_id')->nullable();
            $table->boolean('modo_vacaciones')->default(false);
            $table->boolean('blog_activo')->default(true);
            $table->boolean('reservas_activo')->default(true);
            $table->boolean('faq_activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('psicologas');
    }
};
