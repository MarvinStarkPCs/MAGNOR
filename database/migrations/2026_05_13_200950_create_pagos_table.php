<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reciclador_id')->constrained('recicladores');
            $table->foreignId('recibo_id')->nullable()->constrained('recibos')->nullOnDelete();
            $table->date('fecha');
            $table->decimal('monto', 12, 2);
            $table->enum('forma_pago', ['efectivo', 'transferencia', 'cheque'])->default('efectivo');
            $table->string('referencia', 100)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
