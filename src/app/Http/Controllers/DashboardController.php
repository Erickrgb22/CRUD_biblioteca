<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book; // Necesitamos el modelo Book para el contador
use App\Models\Exemplar; // Necesitamos el modelo Exemplar para los contadores
use App\Models\ExemplarState; // Para buscar el estado 'Disponible'
use App\Models\Movement; // Para contar préstamos atrasados
use Carbon\Carbon; // Para trabajar con fechas (especialmente para los atrasados)

class DashboardController extends Controller
{
    /**
     * Muestra la vista del dashboard con los contadores.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Contadores simples
        $totalBooks = Book::count(); // Cuenta todos los libros registrados
        $totalExemplars = Exemplar::count(); // Cuenta todos los ejemplares en la biblioteca

        // Contar ejemplares disponibles
        $disponibleState = ExemplarState::where('state', 'Disponible')->first();
        // Si el estado 'Disponible' existe, cuenta los ejemplares con ese estado, si no, es 0
        $availableExemplars = $disponibleState ? Exemplar::where('exemplar_state_id', $disponibleState->id)->count() : 0;

        // Contar ejemplares atrasados
        $overdueExemplars = 0;
        $prestadoState = ExemplarState::where('state', 'Prestado')->first(); // Necesitamos el ID del estado 'Prestado'

        if ($prestadoState) {
            // Obtenemos los IDs de los ejemplares que están actualmente 'Prestado'
            $prestadosIds = Exemplar::where('exemplar_state_id', $prestadoState->id)->pluck('id');

            // Contamos los movimientos de esos ejemplares que no han sido devueltos
            // y cuya fecha de vencimiento ya pasó.
            $overdueExemplars = Movement::whereIn('exemplar_id', $prestadosIds)
                                    ->whereNull('return_date') // Asegura que el ejemplar aún no ha sido devuelto
                                    ->where('due_date', '<', Carbon::today()) // La fecha de vencimiento ya pasó
                                    ->count();
        }

        // Pasa todas estas variables a la vista del dashboard
        return view('dashboard', compact('totalBooks', 'totalExemplars', 'availableExemplars', 'overdueExemplars'));
    }
}