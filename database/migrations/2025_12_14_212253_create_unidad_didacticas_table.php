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
        Schema::create('unidad_didacticas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programa_estudio_id')
                ->constrained('programas_estudio')
                ->onDelete('cascade');
            $table->string('nombre', 255);
            $table->integer('creditos');
            $table->integer('horas_semanales');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unidad_didacticas');
    }
};
