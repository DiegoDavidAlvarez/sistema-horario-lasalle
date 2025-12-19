<?php

namespace Database\Seeders;

use App\Models\Semestre;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SemestreSeeder extends Seeder
{
    public function run(): void
    {
        // Semestres académicos típicos
        $semestres = [
            ['numero' => '1'],
            ['numero' => '2'],
            ['numero' => '3'],
            ['numero' => '4'],
            ['numero' => '5'],
            ['numero' => '6'],
        ];

        foreach ($semestres as $semestre) {
            Semestre::create($semestre);
        }
    }
}