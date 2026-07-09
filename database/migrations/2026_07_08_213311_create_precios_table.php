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
        Schema::create('precios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('psicologa_id');
            $table->string('tipo'); // online, presencial
            $table->string('nombre');
            $table->decimal('precio_mensual', 10, 2)->nullable();
            $table->decimal('precio_anual', 10, 2)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('duracion')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();

            $table->foreign('psicologa_id')->references('id')->on('psicologas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precios');
    }
};
