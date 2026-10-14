<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recibos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('numero')->unique();
            $table->date('fecha')->default(now());
            $table->foreignId('reciclador_id')->constrained('recicladores');
            $table->tinyInteger('semana')->virtualAs('WEEK(fecha, 1)');
            $table->smallInteger('anio')->virtualAs('YEAR(fecha)');
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('retefuente', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->virtualAs('subtotal - retefuente');
            $table->text('observaciones')->nullable();
            $table->boolean('anulado')->default(false);
            $table->timestamp('anulado_en')->nullable();
            $table->text('anulado_motivo')->nullable();
            $table->timestamps();
            $table->index('reciclador_id');
            $table->index(['fecha']);
            $table->index(['anio', 'semana']);
            $table->index('anulado');
        });

        Schema::create('detalle_recibo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recibo_id')->constrained('recibos')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materiales');
            $table->decimal('kg_bruto', 10, 3);
            $table->decimal('kg_rechazado', 10, 3)->default(0);
            $table->decimal('kg_neto', 10, 3)->virtualAs('kg_bruto - kg_rechazado');
            $table->decimal('precio_kg', 10, 2);
            $table->decimal('subtotal', 14, 2)->virtualAs('ROUND(kg_bruto * precio_kg, 2)');
            $table->decimal('cantidad_entrante_ton', 12, 6)->virtualAs('ROUND(kg_bruto / 1000, 6)');
            $table->decimal('rechazo_ton', 12, 6)->virtualAs('ROUND(kg_rechazado / 1000, 6)');
            $table->index('recibo_id');
            $table->index('material_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_recibo');
        Schema::dropIfExists('recibos');
    }
};
