<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('documento', 50)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->boolean('es_reciclador')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('proveedores');
    }
};
