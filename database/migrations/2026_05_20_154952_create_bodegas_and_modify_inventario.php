<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Crear tabla bodegas
        Schema::create('bodegas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });

        // Bodega principal por defecto
        DB::table('bodegas')->insert([
            'nombre'     => 'Bodega Principal',
            'activa'     => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Recibos: agregar bodega_id
        Schema::table('recibos', function (Blueprint $table) {
            $table->foreignId('bodega_id')->nullable()->after('reciclador_id')->constrained('bodegas')->nullOnDelete();
            $table->index('bodega_id');
        });

        // 3. Inventario: reemplazar unique(material_id) por unique(material_id, bodega_id)
        Schema::table('inventario', function (Blueprint $table) {
            // Hay que dropear la FK primero porque el unique index está ligado a ella
            $table->dropForeign('inventario_material_id_foreign');
            $table->dropUnique('inventario_material_id_unique');
            $table->foreignId('bodega_id')->nullable()->after('material_id')->constrained('bodegas')->nullOnDelete();
        });

        DB::table('inventario')->update(['bodega_id' => 1]);

        Schema::table('inventario', function (Blueprint $table) {
            // Re-agregar FK de material y unique compuesto
            $table->foreign('material_id')->references('id')->on('materiales');
            $table->unique(['material_id', 'bodega_id']);
        });

        // 4. Movimientos inventario: agregar bodega origen y destino
        Schema::table('movimientos_inventario', function (Blueprint $table) {
            $table->foreignId('bodega_id')->nullable()->after('material_id')->constrained('bodegas')->nullOnDelete();
            $table->foreignId('bodega_destino_id')->nullable()->after('bodega_id')->constrained('bodegas')->nullOnDelete();
        });

        DB::table('movimientos_inventario')->update(['bodega_id' => 1]);
    }

    public function down(): void
    {
        Schema::table('movimientos_inventario', function (Blueprint $table) {
            $table->dropForeign(['bodega_destino_id']);
            $table->dropForeign(['bodega_id']);
            $table->dropColumn(['bodega_id', 'bodega_destino_id']);
        });

        Schema::table('inventario', function (Blueprint $table) {
            $table->dropUnique(['material_id', 'bodega_id']);
            $table->dropForeign(['bodega_id']);
            $table->dropColumn('bodega_id');
            $table->unique('material_id');
        });

        Schema::table('recibos', function (Blueprint $table) {
            $table->dropForeign(['bodega_id']);
            $table->dropIndex(['bodega_id']);
            $table->dropColumn('bodega_id');
        });

        Schema::dropIfExists('bodegas');
    }
};
