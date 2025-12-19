<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => '72230971@lasalleurubamba.edu.pe'],
            [
                'name' => 'Diego David Alvarez Mescco',
                'password' => '12345678',
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            SemestreSeeder::class,
        ]);
    }
}
