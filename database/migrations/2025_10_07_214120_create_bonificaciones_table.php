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
        Schema::create('bonificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_emprendedor');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_transaccion');
            $table->integer('puntos');
            $table->string('motivo', 255)->nullable();
            $table->dateTime('fecha_asignacion');
            $table->timestamps();

            //claves foraneas
            //$table->foreign('id_emprendedor')->references('id')->on('emprendedor')->onDelete('cascade');
            //$table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('id_transaccion')->references('id')->on('transacciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonificaciones');
    }
};
