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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_transaccion');
            $table->text('descripcion')->nullable();
            $table->string('ip_direccion', 45)->nullable();
            $table->dateTime('fecha_creacion');
            $table->string('modulo', 100)->nullable();
            $table->string('gravedad', 50)->nullable();
            $table->timestamps();

            //claves foraneas
            //$table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('id_transaccion')->references('id')->on('transacciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
