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
        Schema::create('docentes', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 255);
            $table->string('apellidos', 255);
            $table->string('email', 255)->unique();
            $table->string('tipo_documento', 20);
            $table->string('numero_documento', 8)->unique();
            $table->enum('nivel_academico', [
                'Bachiller',
                'Técnico',
                'Licenciado',
                'Ingeniero',
                'Magister',
                'Doctor'
            ])->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
