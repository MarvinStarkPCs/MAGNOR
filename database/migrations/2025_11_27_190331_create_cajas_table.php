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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha'); // Fecha de la caja
            $table->foreignId('usuario_apertura_id')->constrained('users'); // Usuario que abre la caja
            $table->decimal('monto_apertura', 10, 2); // Monto inicial de apertura
            $table->datetime('hora_apertura'); // Hora de apertura
            $table->foreignId('usuario_cierre_id')->nullable()->constrained('users'); // Usuario que cierra la caja
            $table->decimal('monto_cierre', 10, 2)->nullable(); // Monto final al cierre
            $table->decimal('monto_esperado', 10, 2)->nullable(); // Monto que debería haber (calculado)
            $table->decimal('diferencia', 10, 2)->nullable(); // Diferencia entre esperado y real
            $table->datetime('hora_cierre')->nullable(); // Hora de cierre
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta'); // Estado de la caja
            $table->text('observaciones')->nullable(); // Observaciones generales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
