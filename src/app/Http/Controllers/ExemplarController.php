<?php

namespace App\Http\Controllers;

use App\Models\Exemplar;
use App\Models\Book;
use App\Models\ExemplarState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Necesario para transacciones de base de datos

class ExemplarController extends Controller
{
    /**
     * Muestra una lista de todos los ejemplares.
     * Corresponde a la ruta GET /exemplars.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtiene todos los ejemplares, precargando sus relaciones con el libro y el estado
        // para un rendimiento óptimo (evita el problema N+1).
        // Se ordenan por la 'location' del ejemplar.
        $exemplars = Exemplar::with(['book', 'state'])->orderBy('location')->get();

        // También necesitamos la lista de libros para el selector en el formulario de creación.
        $books = Book::orderBy('title')->get();

        // Envía los ejemplares y los libros a la vista 'exemplars.index'.
        return view('exemplars.index', compact('exemplars', 'books'));
    }

    /**
     * Muestra el formulario para crear un nuevo ejemplar.
     * (Este método se mantiene por convención, pero en nuestro diseño la creación
     * se gestiona a través de un modal en la vista 'index').
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $books = Book::orderBy('title')->get();
        return view('exemplars.create', compact('books'));
    }

    /**
     * Almacena uno o varios nuevos ejemplares en la base de datos.
     * Corresponde a la ruta POST /exemplars.
     *
     * La lógica de negocio establece que:
     * - Todos los ejemplares creados quedan con estado "Disponible".
     * - Se pueden crear múltiples ejemplares a la vez para un mismo libro.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validar los datos de entrada del formulario.
        $request->validate([
            'book_id' => 'required|exists:books,id', // El ID del libro debe existir en la tabla 'books'.
            'quantity' => 'required|integer|min:1', // La cantidad debe ser un número entero positivo.
        ]);

        // 2. Obtener el ID del estado "Disponible" de la tabla exemplar_states.
        $disponibleState = ExemplarState::where('state', 'Disponible')->first();

        // 3. Si por alguna razón el estado "Disponible" no existe, redirigir con un error.
        // Esto indica un problema con los seeders o la configuración inicial de la BD.
        if (!$disponibleState) {
            return back()->with('error', 'Error de configuración: El estado "Disponible" no se encuentra en la base de datos. Por favor, asegúrate de que los seeders estén ejecutados correctamente.');
        }

        // Obtener el libro para usar su ISBN u otros datos en la generación de la ubicación.
        $book = Book::findOrFail($request->book_id);

        // 4. Iniciar una transacción de base de datos.
        // Esto asegura que todos los ejemplares se creen exitosamente o que ninguno se cree
        // si ocurre un error durante el proceso (ej. un problema de unicidad inesperado).
        DB::beginTransaction();
        try {
            for ($i = 0; $i < $request->quantity; $i++) {
                // Generar un valor para 'location' que sea único y significativo.
                // Usamos el ISBN del libro, el timestamp actual y un ID único para alta probabilidad de unicidad.
                // Puedes ajustar este formato según tus necesidades de identificación de ejemplares.
                $location = 'LOC-' . $book->isbn . '-' . time() . '-' . uniqid();

                Exemplar::create([
                    'book_id' => $request->book_id,
                    'location' => $location, // ¡Se guarda en la columna 'location'!
                    'exemplar_state_id' => $disponibleState->id, // ¡Siempre se asigna el estado "Disponible"!
                ]);
            }
            DB::commit(); // Confirmar la transacción si todo fue exitoso.

            // 5. Preparar mensaje de éxito y redirigir.
            $message = $request->quantity === 1 ? 'Ejemplar agregado exitosamente.' : $request->quantity . ' ejemplares agregados exitosamente.';
            return redirect()->route('exemplars.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción si algo falla.
            return back()->with('error', 'Ocurrió un error al agregar los ejemplares: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Muestra los detalles de un ejemplar específico.
     * (Este método no se utiliza directamente en la interfaz de usuario actual,
     * pero es parte del recurso RESTful completo).
     *
     * @param  \App\Models\Exemplar  $exemplar
     * @return \Illuminate\Http\Response
     */
    public function show(Exemplar $exemplar)
    {
        // Si más adelante necesitas una página de detalles individual para un ejemplar,
        // podrías retornar una vista aquí: return view('exemplars.show', compact('exemplar'));
    }

    /**
     * Muestra el formulario para editar un ejemplar existente.
     * (Similar a 'create', este método se mantiene por convención, pero no se usa
     * en la interfaz actual ya que no se permite la edición directa).
     *
     * @param  \App\Models\Exemplar  $exemplar
     * @return \Illuminate\View\View
     */
    public function edit(Exemplar $exemplar)
    {
        $books = Book::orderBy('title')->get();
        return view('exemplars.edit', compact('exemplar', 'books'));
    }

    /**
     * Actualiza un ejemplar existente en la base de datos.
     * (Este método se mantiene en el controlador, pero no hay un botón en la UI
     * para activarlo, ya que la edición directa de ejemplares no está permitida).
     *
     * Solo permite la edición de la 'location' y el 'book_id' asociado.
     * El 'exemplar_state_id' NO se actualiza desde aquí; su cambio depende del módulo de Préstamos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exemplar  $exemplar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Exemplar $exemplar)
    {
        // Validar los datos de entrada.
        $request->validate([
            'book_id' => 'required|exists:books,id',
            // La 'location' debe ser única, ignorando el ejemplar que se está actualizando.
            'location' => 'required|string|max:255|unique:exemplars,location,' . $exemplar->id,
        ]);

        // Actualizar el ejemplar con los datos validados.
        $exemplar->update([
            'book_id' => $request->book_id,
            'location' => $request->location, // Actualiza la 'location'.
        ]);

        // Redirigir de vuelta a la lista de ejemplares con un mensaje de éxito.
        return redirect()->route('exemplars.index')->with('success', 'Ejemplar actualizado exitosamente.');
    }

    /**
     * Elimina un ejemplar de la base de datos, solo si está en estado "Disponible".
     * Corresponde a la ruta DELETE /exemplars/{exemplar}.
     *
     * La lógica de negocio establece que:
     * - No se pueden borrar ejemplares con estado "Prestado" o "Retrasado".
     *
     * (Este método se mantiene en el controlador, pero no hay un botón en la UI
     * para activarlo, ya que la eliminación directa de ejemplares no está permitida).
     *
     * @param  \App\Models\Exemplar  $exemplar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Exemplar $exemplar)
    {
        // 1. Obtener el ID del estado "Disponible" de la base de datos.
        $disponibleState = ExemplarState::where('state', 'Disponible')->first();

        // 2. Verificar si el ejemplar tiene el estado "Disponible".
        if (!$disponibleState || $exemplar->exemplar_state_id !== $disponibleState->id) {
            // Si el ejemplar NO está en estado "Disponible", no permitimos la eliminación.
            return redirect()->route('exemplars.index')->with('error', 'No se puede eliminar un ejemplar que no esté en estado "Disponible".');
        }

        // 3. Si el ejemplar está Disponible, proceder con la eliminación.
        $exemplar->delete();

        // 4. Redirigir con mensaje de éxito.
        return redirect()->route('exemplars.index')->with('success', 'Ejemplar eliminado exitosamente.');
    }
}