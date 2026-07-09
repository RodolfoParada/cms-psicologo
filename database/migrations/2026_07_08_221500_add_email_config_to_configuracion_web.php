<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracion_web', function (Blueprint $table) {
            $table->string('email_notificaciones')->nullable();
            $table->string('email_smtp_host')->nullable();
            $table->string('email_smtp_port')->nullable();
            $table->string('email_smtp_user')->nullable();
            $table->string('email_smtp_pass')->nullable();
            $table->string('email_smtp_encryption')->nullable();
            $table->boolean('notificaciones_activas')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('configuracion_web', function (Blueprint $table) {
            $table->dropColumn([
                'email_notificaciones', 'email_smtp_host', 'email_smtp_port',
                'email_smtp_user', 'email_smtp_pass', 'email_smtp_encryption',
                'notificaciones_activas',
            ]);
        });
    }
};
