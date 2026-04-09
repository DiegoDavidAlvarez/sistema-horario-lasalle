<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Creación de roles de la página administrable
        $role = Role::create(['name' => 'Administrador']);

        // Creación de permisos - Home
        Permission::create(['name' => 'admin.home.index'])->syncRoles([$role]);

        // Permisos - Docente
        Permission::create(['name' => 'admin.docente.index'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.docente.store'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.docente.update'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.docente.destroy'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.docente.restore'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.docente.consultar-dni'])->syncRoles([$role]);

        // Permisos - Programa Estudio
        Permission::create(['name' => 'admin.programa-estudio.index'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.programa-estudio.store'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.programa-estudio.update'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.programa-estudio.destroy'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.programa-estudio.restore'])->syncRoles([$role]);

        // Permisos - Unidad Didáctica
        Permission::create(['name' => 'admin.unidad-didactica.index'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.unidad-didactica.store'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.unidad-didactica.update'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.unidad-didactica.destroy'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.unidad-didactica.restore'])->syncRoles([$role]);

        // Permisos - Módulo
        Permission::create(['name' => 'admin.modulos.store'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.modulos.update'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.modulos.destroy'])->syncRoles([$role]);

        // Permisos - Malla Curricular
        Permission::create(['name' => 'admin.malla-curricular.index'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.malla-curricular.store'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.malla-curricular.update'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.malla-curricular.destroy'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.malla-curricular.restore'])->syncRoles([$role]);

        // Permisos - Espacio Físico
        Permission::create(['name' => 'admin.espacio-fisico.index'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.espacio-fisico.store'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.espacio-fisico.update'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.espacio-fisico.destroy'])->syncRoles([$role]);
        Permission::create(['name' => 'admin.espacio-fisico.restore'])->syncRoles([$role]);

        // Creación de usuario administrador
        User::factory()->create([
            'name' => 'Diego David Alvarez Mescco',
            'email' => '72230971@lasalleurubamba.edu.pe',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
        ])->assignRole('Administrador');

        // Llamada a otros seeders
        $this->call([
            SemestreSeeder::class,
        ]);
    }
}
