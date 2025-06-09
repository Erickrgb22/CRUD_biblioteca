<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserType; // Importa el modelo UserType

class UserTypeSeeder extends Seeder
{
    /**
     * Ejecuta los seeders de la base de datos para los tipos de usuario.
     *
     * @return void
     */
    public function run(): void
    {
        // Usa firstOrCreate para evitar duplicados si se ejecuta varias veces
        UserType::firstOrCreate(['type' => 'Bibliotecario']);
        UserType::firstOrCreate(['type' => 'Lector']);
    }
}
