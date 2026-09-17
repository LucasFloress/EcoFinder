<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            
            // Emprendedor que crea la solicitud
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Información de la solicitud
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('tipo_material'); // plástico, papel, vidrio, metal, etc.
            $table->decimal('cantidad', 10, 2)->nullable(); // cantidad en kg
            $table->string('unidad_medida')->default('kg'); // kg, unidades, etc.
            
            // Puntos verdes donde se puede entregar (múltiples)
            $table->json('puntos_verdes_ids'); // [1, 3, 5] - IDs de puntos verdes
            
            // Fechas
            $table->date('fecha_vencimiento')->nullable(); // Fecha límite de la solicitud
            
            // Estado
            $table->enum('estado', ['activa', 'en_proceso', 'completada', 'cancelada'])->default('activa');
            
            // Imágenes/Referencias
            $table->json('imagenes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};