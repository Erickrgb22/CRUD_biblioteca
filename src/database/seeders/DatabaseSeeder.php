<?php

namespace Database\Seeders;

// Elimina esta línea si te da error o si no tienes factories
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta los seeders de la aplicación.
     *
     * @return void
     */
    public function run(): void
    {
        // Llama a los seeders en el orden correcto:
        // 1. Tipos de usuario (porque User depende de UserType)
        $this->call(UserTypeSeeder::class);
        // 2. Estados de ejemplar (porque Exemplar depende de ExemplarState)
        $this->call(ExemplarStateSeeder::class);
        // 3. Usuarios (porque User depende de UserType)
        $this->call(UserSeeder::class);

        // Si tuvieras más seeders con dependencias, los llamarías en el orden adecuado aquí.
    }
}