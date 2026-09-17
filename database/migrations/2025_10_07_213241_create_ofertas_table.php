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
        Schema::create('ofertas', function (Blueprint $table) {
            $table->id('id_oferta');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_material');
            $table->decimal('cantidad', 10, 2);
            $table->enum('unidad', ['kg', 'unidad', 'litro', 'otro']);
            $table->enum('estado', ['disponible', 'reservado', 'entregado', 'cancelado']);
            $table->dateTime('fecha_publicacion');
            $table->dateTime('fecha_cierre')->nullable();
            $table->unsignedBigInteger('id_punto_verde');
            $table->timestamps();

            //las claves foraneas
            //$table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('cascade');
            //$table->foreign('id_material')->references('id')->on('materiales')->onDelete('cascade');
            //$table->foreign('id_punto_verde')->references('id')->on('punto_verdes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ofertas');
    }
};
