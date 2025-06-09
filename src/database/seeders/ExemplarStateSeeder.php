<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExemplarState; // Importa el modelo ExemplarState

class ExemplarStateSeeder extends Seeder
{
    /**
     * Ejecuta los seeders de la base de datos para los estados de ejemplar.
     *
     * @return void
     */
    public function run(): void
    {
        // Usa firstOrCreate para asegurar que los estados existan y no se dupliquen
        ExemplarState::firstOrCreate(['state' => 'Disponible']);
        ExemplarState::firstOrCreate(['state' => 'Prestado']);
        ExemplarState::firstOrCreate(['state' => 'Atrasado']);
    }
}