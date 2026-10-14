<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('familia_id')->constrained('familias_material');
            $table->smallInteger('codigo_sui');
            $table->string('nombre', 100)->unique();
            $table->decimal('precio_actual', 10, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->useCurrent();
            $table->index('familia_id');
            $table->index('codigo_sui');
        });

        Schema::create('historial_precios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materiales')->onDelete('cascade');
            $table->decimal('precio', 10, 2);
            $table->date('vigente_desde')->default(now());
            $table->date('vigente_hasta')->nullable();
            $table->string('registrado_por', 100)->nullable();
            $table->index(['material_id', 'vigente_desde']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_precios');
        Schema::dropIfExists('materiales');
    }
};
