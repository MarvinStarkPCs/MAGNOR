<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('departamento', 100)->default('Norte de Santander');
            $table->string('codigo_dane', 8)->nullable();
            $table->boolean('activo')->default(true);
            $table->unique(['nombre', 'departamento']);
        });

        Schema::create('tipos_documento', function (Blueprint $table) {
            $table->tinyInteger('id')->primary();
            $table->string('codigo', 5)->unique();
            $table->string('descripcion', 80);
        });

        Schema::create('dias_semana', function (Blueprint $table) {
            $table->tinyInteger('id')->primary();
            $table->string('nombre', 12)->unique();
            $table->char('abreviatura', 3)->unique();
        });

        Schema::create('tipos_sitio_destino', function (Blueprint $table) {
            $table->tinyInteger('id')->primary();
            $table->string('descripcion', 100);
        });

        Schema::create('configuracion_empresa', function (Blueprint $table) {
            $table->string('clave', 60)->primary();
            $table->text('valor');
            $table->text('descripcion')->nullable();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 10)->unique();
            $table->string('tipo', 50)->nullable();
            $table->boolean('activo')->default(true);
        });

        Schema::create('familias_material', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 60)->unique();
            $table->smallInteger('codigo_sui')->nullable();
            $table->boolean('activo')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('familias_material');
        Schema::dropIfExists('vehiculos');
        Schema::dropIfExists('configuracion_empresa');
        Schema::dropIfExists('tipos_sitio_destino');
        Schema::dropIfExists('dias_semana');
        Schema::dropIfExists('tipos_documento');
        Schema::dropIfExists('municipios');
    }
};
