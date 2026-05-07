<?php

namespace Database\Seeders;

use App\Models\BloqueTiempo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BloqueTiempoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloques = [
            [
                'numero_bloque' => 1,
                'hora_inicio' => '08:00',
                'hora_fin' => '08:45',
                'duracion_minutos' => 45,
                'es_receso' => false,
            ],
            [
                'numero_bloque' => 2,
                'hora_inicio' => '08:45',
                'hora_fin' => '09:30',
                'duracion_minutos' => 45,
                'es_receso' => false,
            ],
            [
                'numero_bloque' => 3,
                'hora_inicio' => '09:30',
                'hora_fin' => '10:15',
                'duracion_minutos' => 45,
                'es_receso' => false,
            ],
            [
                'numero_bloque' => 4,
                'hora_inicio' => '10:15',
                'hora_fin' => '11:00',
                'duracion_minutos' => 45,
                'es_receso' => false,
            ],
            [
                'numero_bloque' => 5,
                'hora_inicio' => '11:00',
                'hora_fin' => '11:30',
                'duracion_minutos' => 30,
                'es_receso' => true,
            ],
            [
                'numero_bloque' => 6,
                'hora_inicio' => '11:30',
                'hora_fin' => '12:15',
                'duracion_minutos' => 45,
                'es_receso' => false,
            ],
            [
                'numero_bloque' => 7,
                'hora_inicio' => '12:15',
                'hora_fin' => '13:00',
                'duracion_minutos' => 45,
                'es_receso' => false,
            ],
            [
                'numero_bloque' => 8,
                'hora_inicio' => '13:00',
                'hora_fin' => '13:30',
                'duracion_minutos' => 30,
                'es_receso' => false,
            ],
        ];

        foreach ($bloques as $bloque) {
            BloqueTiempo::create($bloque);
        }
    }
}
