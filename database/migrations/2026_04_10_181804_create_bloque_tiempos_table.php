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
        Schema::create('bloque_tiempos', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_bloque');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('duracion_minutos');
            $table->boolean('es_receso')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloque_tiempos');
    }
};
