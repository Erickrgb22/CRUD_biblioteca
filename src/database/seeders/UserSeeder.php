<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Importa el modelo User
use App\Models\UserType; // Importa el modelo UserType
use Illuminate\Support\Facades\Hash; // Para hashear la contraseña

class UserSeeder extends Seeder
{
    /**
     * Ejecuta los seeders de la base de datos para el usuario bibliotecario.
     *
     * @return void
     */
    public function run(): void
    {
        // Encuentra el tipo de usuario 'Bibliotecario'
        $bibliotecarioType = UserType::where('type', 'Bibliotecario')->first();

        // Solo si encontramos el tipo de bibliotecario, procedemos a crear el usuario
        if ($bibliotecarioType) {
            User::firstOrCreate(
                ['email' => 'bibliotecario@example.com'], // Condición para buscar/crear: email
                [
                    'user_type_id' => $bibliotecarioType->id, // Asocia el tipo de usuario
                    'name' => 'Bibliotecario Principal',
                    'ci' => 'V-12345678',
                    'phone' => '04121234567',
                    'password' => Hash::make('password'), // La contraseña hasheada es 'password'
                ]
            );
        } else {
            // Esto es una advertencia si el UserTypeSeeder no se ejecutó primero
            $this->command->warn('Tipo de usuario "Bibliotecario" no encontrado. Asegúrate de ejecutar UserTypeSeeder primero.');
        }

        // Puedes crear un lector de ejemplo si lo deseas, pero este usuario NO podrá iniciar sesión
        $lectorType = UserType::where('type', 'Lector')->first();
        if ($lectorType) {
             User::firstOrCreate(
                ['email' => 'lector@example.com'],
                [
                    'user_type_id' => $lectorType->id,
                    'name' => 'Lector Ejemplo',
                    'ci' => 'E-87654321',
                    'phone' => '04247654321',
                    'password' => Hash::make('password_lector'), // Contraseña para este lector (no se usará en login)
                ]
            );
        }
    }
}