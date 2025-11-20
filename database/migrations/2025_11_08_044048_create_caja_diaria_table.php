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
        Schema::create('caja_diaria', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->unique();
            $table->decimal('monto_inicial', 12, 2)->default(0);
            $table->decimal('monto_final', 12, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_diaria');
    }
};
