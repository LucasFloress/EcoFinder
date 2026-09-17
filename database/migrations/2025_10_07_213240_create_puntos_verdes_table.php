<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('puntos_verdes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('province')->default('Buenos Aires');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            
            // Horarios
            $table->json('schedule')->nullable();
            
            // Materiales que acepta
            $table->json('accepted_materials')->nullable();
            
            // Relación con municipalidad
            $table->foreignId('municipality_id')->constrained('users')->onDelete('cascade');
            
            // Estado
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['operativo', 'mantenimiento', 'cerrado'])->default('operativo');
            
            // Imagen
            $table->string('image')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('puntos_verdes');
    }
};