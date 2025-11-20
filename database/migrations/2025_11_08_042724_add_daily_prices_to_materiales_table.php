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
        Schema::table('materiales', function (Blueprint $table) {
            $table->decimal('precio_dia_compra', 10, 2)->nullable()->after('precio_venta');
            $table->decimal('precio_dia_venta', 10, 2)->nullable()->after('precio_dia_compra');
            $table->date('fecha_actualizacion_precio')->nullable()->after('precio_dia_venta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materiales', function (Blueprint $table) {
            $table->dropColumn(['precio_dia_compra', 'precio_dia_venta', 'fecha_actualizacion_precio']);
        });
    }
};
