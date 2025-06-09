<?php

namespace App\Http\Controllers;

use App\Models\Book; // ¡Importa el modelo Book! Es crucial para interactuar con la tabla 'books'.
use Illuminate\Http\Request; // Necesario para manejar los datos que vienen de los formularios.

class BookController extends Controller
{
    /**
     * Muestra una lista de todos los libros.
     * Corresponde a la ruta GET /books
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtiene todos los libros de la base de datos.
        // Usamos orderBy('title') para que se muestren ordenados alfabéticamente por título.
        $books = Book::orderBy('title')->get();

        // Retorna la vista 'books.index' y le pasa la variable $books.
        // 'compact('books')' es una función de PHP que crea un array ['books' => $books]
        // y lo pasa a la vista.
        return view('books.index', compact('books'));
    }

    /**
     * Muestra el formulario para crear un nuevo libro.
     * Corresponde a la ruta GET /books/create
     *
     * (Aunque usaremos un modal para 'create' en 'index', este método se mantiene por si lo necesitas)
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // En nuestro diseño, la creación se hará en un modal dentro de la vista 'index',
        // por lo que este método podría no ser llamado directamente.
        // Si tuviéramos un formulario de creación en una página separada, lo cargaríamos aquí.
        return view('books.create');
    }

    /**
     * Almacena un nuevo libro en la base de datos.
     * Corresponde a la ruta POST /books
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validar los datos de entrada del formulario.
        // Esto es MUY IMPORTANTE para la seguridad y la integridad de la base de datos.
        // 'required': el campo no puede estar vacío.
        // 'string': el valor debe ser una cadena de texto.
        // 'max:255': la longitud máxima de la cadena es 255 caracteres.
        // 'unique:books,isbn': el ISBN debe ser único en la tabla 'books'.
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn',
        ]);

        // 2. Crear un nuevo libro en la base de datos usando los datos validados.
        // 'Book::create()' utiliza el método 'create' de Eloquent para insertar un nuevo registro.
        // Esto funciona porque ya definimos el `$fillable` en tu modelo `Book`.
        Book::create($request->all()); // `all()` obtiene todos los datos validados del request.

        // 3. Redirigir al usuario de vuelta a la lista de libros con un mensaje de éxito.
        // `redirect()->route('books.index')`: redirige a la URL de la ruta 'books.index' (que es /books).
        // `->with('success', '...')`: añade un mensaje flash a la sesión. Este mensaje solo estará disponible
        // para la siguiente solicitud HTTP y es útil para mostrar notificaciones al usuario.
        return redirect()->route('books.index')->with('success', 'Libro agregado exitosamente.');
    }

    /**
     * Muestra los detalles de un libro específico.
     * Corresponde a la ruta GET /books/{book}
     *
     * (No la usaremos directamente en la interfaz por ahora, pero es parte del recurso RESTful)
     * @param  \App\Models\Book  $book  -> Gracias a la inyección de modelo de Laravel,
     * Laravel automáticamente busca el libro por el ID en la URL.
     * @return \Illuminate\Http\Response
     */

    /**
     * Muestra el formulario para editar un libro existente.
     * Corresponde a la ruta GET /books/{book}/edit
     *
     * (También usaremos un modal para 'edit' en 'index')
     * @param  \App\Models\Book  $book
     * @return \Illuminate\View\View
     */
    public function edit(Book $book)
    {
        // Similar a 'create', la edición se hará en un modal.
        // Pero si tuviéramos una página de edición separada, la cargaríamos aquí y pasaríamos el libro.
        return view('books.edit', compact('book'));
    }

    /**
     * Actualiza un libro existente en la base de datos.
     * Corresponde a la ruta PUT/PATCH /books/{book}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Book $book)
    {
        // 1. Validar los datos de entrada del formulario.
        // La validación del ISBN es diferente: 'unique:books,isbn,' . $book->id
        // Esto le dice a Laravel que el ISBN debe ser único en la tabla 'books',
        // ¡pero que ignore el ISBN del libro que estamos editando actualmente!
        // Esto es crucial para no tener un error de "ISBN ya existe" si solo editas el título de un libro.
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn,' . $book->id,
        ]);

        // 2. Actualizar el libro con los datos validados.
        // `$book->update()` utiliza el método 'update' de Eloquent en el modelo de libro que ya fue inyectado.
        $book->update($request->all());

        // 3. Redirigir al usuario de vuelta a la lista de libros con un mensaje de éxito.
        return redirect()->route('books.index')->with('success', 'Libro actualizado exitosamente.');
    }

    /**
     * Elimina un libro de la base de datos.
     * Corresponde a la ruta DELETE /books/{book}
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Book $book)
    {
        // Eliminar el libro.
        $book->delete();

        // Redirigir al usuario de vuelta a la lista de libros con un mensaje de éxito.
        return redirect()->route('books.index')->with('success', 'Libro eliminado exitosamente.');
    }
}