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
        Schema::create('mallas_curriculares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')
                ->constrained('modulos')
                ->onDelete('cascade');
            $table->foreignId('unidad_didactica_id')
                ->constrained('unidades_didacticas')
                ->onDelete('cascade');
            $table->foreignId('semestre_id')
                ->constrained('semestres')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mallas_curriculares');
    }
};
