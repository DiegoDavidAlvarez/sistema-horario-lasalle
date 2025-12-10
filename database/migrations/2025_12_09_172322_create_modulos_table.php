<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programa_estudio_id')->constrained('programas_estudio')->onDelete('cascade');
            $table->integer('numero_modulo');
            $table->string('nombre', 255);
            $table->string('codigo', 50)->nullable();
            $table->integer('semestre')->nullable();
            $table->timestamps();

            // Índice único para evitar duplicados de número de módulo por programa
            $table->unique(['programa_estudio_id', 'numero_modulo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};
