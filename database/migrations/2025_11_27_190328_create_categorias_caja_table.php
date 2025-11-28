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
        Schema::create('categorias_caja', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre de la categoría (ej: Combustible, Papelería, Servicios)
            $table->enum('tipo', ['ingreso', 'egreso']); // Tipo de categoría
            $table->string('descripcion')->nullable(); // Descripción opcional
            $table->boolean('activo')->default(true); // Si la categoría está activa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias_caja');
    }
};
