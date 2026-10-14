<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recicladores', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('tipo_documento_id')->default(1);
            $table->foreign('tipo_documento_id')->references('id')->on('tipos_documento');
            $table->string('documento', 20);
            $table->string('nombre', 150);
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 250)->nullable();
            $table->foreignId('municipio_id')->nullable()->constrained('municipios');
            $table->boolean('activo')->default(true);
            $table->foreignId('vehiculo_id')->nullable()->constrained('vehiculos');
            $table->string('numero_sitio_destino', 20)->nullable();
            $table->tinyInteger('tipo_sitio_destino_id')->nullable();
            $table->foreign('tipo_sitio_destino_id')->references('id')->on('tipos_sitio_destino');
            $table->timestamps();
            $table->unique(['tipo_documento_id', 'documento']);
            $table->index('documento');
            $table->index('activo');
        });

        Schema::create('rutas_reciclador', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reciclador_id')->constrained('recicladores')->onDelete('cascade');
            $table->tinyInteger('dia_id');
            $table->foreign('dia_id')->references('id')->on('dias_semana');
            $table->text('descripcion')->nullable();
            $table->unique(['reciclador_id', 'dia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rutas_reciclador');
        Schema::dropIfExists('recicladores');
    }
};
