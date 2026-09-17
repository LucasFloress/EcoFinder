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
        Schema::create('reporte_impactos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->enum('tipo', ['ambiental', 'social', 'económico']);
            $table->text('descripcion');
            $table->dateTime('fecha');
            $table->date('periodo_inicio')->nullable();
            $table->date('periodo_fin')->nullable();
            $table->decimal('materiales_reciclados', 10, 2)->default(0);
            $table->integer('transacciones_realizadas')->default(0);
            $table->integer('puntos_obtenidos')->default(0);
            $table->timestamps();

            //clave foranea
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporte_impactos');
    }
};
