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
        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained('cajas')->onDelete('cascade'); // Caja a la que pertenece
            $table->foreignId('categoria_id')->constrained('categorias_caja'); // Categoría del movimiento
            $table->enum('tipo', ['ingreso', 'egreso']); // Tipo de movimiento
            $table->decimal('monto', 10, 2); // Monto del movimiento
            $table->string('concepto'); // Concepto/descripción del movimiento
            $table->text('observaciones')->nullable(); // Observaciones adicionales
            $table->foreignId('responsable_id')->constrained('users'); // Usuario responsable
            $table->string('comprobante')->nullable(); // Ruta del archivo del comprobante/factura
            $table->datetime('fecha_hora'); // Fecha y hora del movimiento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_caja');
    }
};
