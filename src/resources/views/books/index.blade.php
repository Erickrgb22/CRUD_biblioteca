@extends('layouts.app') {{-- Extiende nuestro layout base de Bootstrap --}}

@section('title', 'Gestión de Libros')

@section('content')
    <h1 class="mb-4">Gestión de Libros</h1>

    {{-- Mensajes flash de éxito o error --}}
    {{-- Si hay un mensaje 'success' en la sesión, lo muestra --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Si hay un mensaje 'error' en la sesión, lo muestra (puedes agregar lógica para errores específicos) --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Botón para agregar libro (abre el modal) --}}
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addBookModal">
        Agregar Libro
    </button>

    {{-- Tabla de Libros --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>ISBN</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- Bucle para mostrar cada libro --}}
                @forelse ($books as $book) {{-- Recorremos la colección $books que viene del controlador --}}
                    <tr>
                        <td>{{ $book->id }}</td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->isbn }}</td>
                        <td>
                            {{-- Botón Editar (abre modal, le pasa datos del libro con data-*) --}}
                            <button type="button" class="btn btn-sm btn-warning me-2"
                                data-bs-toggle="modal" data-bs-target="#editBookModal"
                                data-id="{{ $book->id }}"
                                data-title="{{ $book->title }}"
                                data-author="{{ $book->author }}"
                                data-isbn="{{ $book->isbn }}">
                                Editar
                            </button>
                            {{-- Botón Eliminar (abre modal, le pasa datos del libro con data-*) --}}
                            <button type="button" class="btn btn-sm btn-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteBookModal"
                                data-id="{{ $book->id }}"
                                data-title="{{ $book->title }}">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty {{-- Si no hay libros, muestra este mensaje --}}
                    <tr>
                        <td colspan="5" class="text-center">No hay libros registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Incluye las definiciones de los modales desde otro archivo --}}
    @include('books.modals')

@endsection

{{-- Sección para añadir scripts JavaScript específicos a esta página --}}
@push('scripts')
<script>
    // Script para manejar el modal de Editar
    var editBookModal = document.getElementById('editBookModal');
    editBookModal.addEventListener('show.bs.modal', function (event) {
        // 'event.relatedTarget' es el botón que activó el modal (el botón "Editar")
        var button = event.relatedTarget;
        // Extrae la información del libro de los atributos 'data-*' del botón
        var id = button.getAttribute('data-id');
        var title = button.getAttribute('data-title');
        var author = button.getAttribute('data-author');
        var isbn = button.getAttribute('data-isbn');

        // Encuentra los elementos dentro del modal para actualizar su contenido
        var modalTitle = editBookModal.querySelector('.modal-title');
        var form = editBookModal.querySelector('form');
        var inputTitle = editBookModal.querySelector('#edit_title');
        var inputAuthor = editBookModal.querySelector('#edit_author');
        var inputIsbn = editBookModal.querySelector('#edit_isbn');

        // Actualiza el título del modal y la acción del formulario
        modalTitle.textContent = 'Editar Libro: ' + title;
        // La URL de acción del formulario debe apuntar al libro específico (ej: /books/5)
        form.action = '/books/' + id;
        // Rellena los campos del formulario con los datos del libro
        inputTitle.value = title;
        inputAuthor.value = author;
        inputIsbn.value = isbn;

        // Si Laravel redirigió con errores de validación para este modal
        // y el 'old' input para el ID del libro editado no está vacío
        // (esto es un poco más avanzado para mantener los datos anteriores si falla la validación)
        // Puedes agregar lógica aquí si lo necesitas para persistir errores específicos de validación en el modal
        @if ($errors->any())
            var oldId = document.querySelector('input[name="_method"][value="PUT"]'); // Si es un formulario de PUT
            if (oldId && oldId.closest('form').action.endsWith('/' + id)) {
                inputTitle.value = "{{ old('title', $book->title ?? '') }}";
                inputAuthor.value = "{{ old('author', $book->author ?? '') }}";
                inputIsbn.value = "{{ old('isbn', $book->isbn ?? '') }}";
            }
        @endif
    });

    // Script para manejar el modal de Eliminar
    var deleteBookModal = document.getElementById('deleteBookModal');
    deleteBookModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var title = button.getAttribute('data-title');

        var modalBody = deleteBookModal.querySelector('.modal-body');
        var form = deleteBookModal.querySelector('form');

        // Actualiza el mensaje de confirmación y la acción del formulario
        modalBody.textContent = '¿Estás seguro de que deseas eliminar el libro "' + title + '"? Esta acción es irreversible.';
        form.action = '/books/' + id; // La URL de acción para eliminar (ej: /books/5)
    });

    // Lógica para reabrir modales automáticamente si hay errores de validación
    // Esto es muy útil: si envías un formulario de un modal y hay un error de validación,
    // Laravel redirige de vuelta. Este script detecta los errores y vuelve a abrir el modal.
    @if ($errors->any())
        // Detecta si los errores son para el modal de agregar o editar
        var hasAddErrors = {{ $errors->has('title') || $errors->has('author') || $errors->has('isbn') ? 'true' : 'false' }};

        // Si hay errores de validación, intenta determinar cuál modal abrir
        // Si hay un error y no hay un campo oculto _method con PUT (lo que indicaría edición),
        // asumimos que el error es del formulario de agregar.
        // Esto es una simplificación; un sistema más robusto podría pasar el ID del libro editado
        // a la sesión si la validación de edición falla.
        var isEditModal = false;
        var formErrors = @json($errors->messages());
        if (formErrors['title'] || formErrors['author'] || formErrors['isbn']) {
            // Un truco para ver si los errores son de un formulario de edición:
            // Si el valor 'old' para _method es 'PUT', es probable que sea edición.
            // Para ser más precisos, Laravel usualmente pasa el ID del recurso editado
            // si la validación falla en un `update` request.
            // Por simplicidad, si los errores son de 'title', 'author' o 'isbn', abrimos el modal de agregar.
            // Si la validación fallara en la edición, el formulario de edición ya tendría los campos 'old'.
            // Para una solución robusta, necesitarías pasar el ID del libro editado a la sesión.
            // Por ahora, si no hay un 'old' de ID de libro, abrimos el de añadir.
            if ("{{ old('_method') }}" === "PUT") {
                isEditModal = true;
                // Aquí necesitaríamos saber cuál libro se estaba editando para rellenarlo
                // y que el modal lo cargue. Laravel no pasa el ID 'old' por defecto.
                // Si la validación falla en edición, los valores 'old' de los campos se mantienen automáticamente,
                // pero el modal no se reabrirá automáticamente con ese libro sin más JS.
                // Por ahora, asumimos que si hay errores en general, y no estamos en un 'PUT' explícito, es 'add'.
            }
        }


        // Si hay errores y no estamos tratando de editar un libro específico (lo que requeriría más lógica de estado)
        if (hasAddErrors && !isEditModal) {
            var addBookModalInstance = new bootstrap.Modal(document.getElementById('addBookModal'));
            addBookModalInstance.show();
        } else if (isEditModal) {
             // Este caso es más complejo. Si la validación falló en un formulario de edición,
             // los campos `old` se mantendrán, pero necesitamos reabrir EL modal de edición.
             // Para que esto funcione, en el controlador `update` podríamos redirigir
             // con el ID del libro y un error, y aquí capturar ese ID para reabrir el modal correcto.
             // Por ahora, si hay errores de validación que sabemos que vienen de un modal,
             // y no es el de agregar, abrimos el de edición, asumiendo que el usuario lo intentó.
             // Necesitarías una forma de pasar `old('book_id_being_edited')` para que esto funcione sin fallar.
             // Para esta fase, si hay errores en el formulario de editar, los campos se mantienen
             // pero el modal no reabre automáticamente sin más ayuda de Laravel.
             // Podemos obviar este caso por ahora para no complicar el flujo.
             // Si el error fuera de edición, los `old()` valores se mantendrían, pero el modal no abriría.
             // Para reabrirlo, necesitarías pasar un `old('book_id')` del libro que se estaba editando.
        }
    @endif
</script>
@endpush