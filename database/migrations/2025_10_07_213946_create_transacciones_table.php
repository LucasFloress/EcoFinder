<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            
            // Solicitud a la que se suscribe
            $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');
            
            // Vecino que se suscribe
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Punto verde elegido por el vecino
            // $table->foreignId('punto_verde_id')->constrained('puntos_verdes')->onDelete('cascade');
            $table->foreignId('puntos_verdes_id')->constrained('puntos_verdes'); // Correctly references puntos_verdes

            
            // Información adicional
            $table->decimal('cantidad_ofrecida', 10, 2)->nullable(); // Cantidad que ofrece
            $table->text('notas')->nullable(); // Notas del vecino
            $table->date('fecha_entrega')->nullable(); // Fecha estimada de entrega
            
            // Estado de la suscripción
            $table->enum('estado', ['pendiente', 'confirmada', 'entregada', 'cancelada'])->default('pendiente');
            
            // Rating/Feedback (opcional)
            $table->integer('calificacion')->nullable(); // 1-5 estrellas
            $table->text('comentario')->nullable();
            
            $table->timestamps();
            
            // Un vecino no puede suscribirse múltiples veces a la misma solicitud
            $table->unique(['solicitud_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};