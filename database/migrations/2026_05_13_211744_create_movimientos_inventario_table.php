<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materiales');
            $table->enum('tipo', ['entrada', 'salida', 'ajuste']);
            $table->decimal('kg', 12, 3);                        // siempre positivo
            $table->decimal('kg_antes', 12, 3)->default(0);      // snapshot anterior
            $table->decimal('kg_despues', 12, 3)->default(0);    // snapshot nuevo
            $table->nullableMorphs('referencia');                 // polimórfico: recibo, venta, ajuste
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->date('fecha');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
