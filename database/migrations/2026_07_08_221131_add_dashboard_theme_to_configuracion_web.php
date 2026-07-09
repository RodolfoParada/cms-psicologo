<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracion_web', function (Blueprint $table) {
            $table->string('dashboard_tema')->default('claro');
            $table->string('dashboard_color')->default('#6366f1');
        });
    }

    public function down(): void
    {
        Schema::table('configuracion_web', function (Blueprint $table) {
            $table->dropColumn(['dashboard_tema', 'dashboard_color']);
        });
    }
};
