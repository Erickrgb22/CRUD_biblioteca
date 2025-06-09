{{-- Modal para Agregar Libro --}}
<div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBookModalLabel">Agregar Nuevo Libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- El formulario apunta a la ruta 'books.store' para guardar el nuevo libro --}}
            <form action="{{ route('books.store') }}" method="POST">
                @csrf {{-- ¡Token CSRF es OBLIGATORIO para seguridad en todos los formularios POST! --}}
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Título:</label>
                        {{-- 'old('title')' ayuda a mantener el valor ingresado si hay un error de validación --}}
                        {{-- '@error('title') is-invalid @enderror' aplica la clase de error de Bootstrap --}}
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title') {{-- Muestra el mensaje de error específico para el campo 'title' --}}
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Autor:</label>
                        <input type="text" class="form-control @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author') }}" required>
                        @error('author')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN:</label>
                        <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn" value="{{ old('isbn') }}" required>
                        @error('isbn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Libro</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal para Editar Libro --}}
<div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBookModalLabel">Editar Libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- La acción del formulario se establece dinámicamente con JavaScript en index.blade.php --}}
            <form action="" method="POST">
                @csrf
                @method('PUT') {{-- Indica que este formulario es para una operación de ACTUALIZACIÓN (PUT) --}}
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Título:</label>
                        {{-- Los IDs aquí son diferentes ('edit_title') para no confundirlos con los del modal de agregar --}}
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="edit_title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit_author" class="form-label">Autor:</label>
                        <input type="text" class="form-control @error('author') is-invalid @enderror" id="edit_author" name="author" value="{{ old('author') }}" required>
                        @error('author')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edit_isbn" class="form-label">ISBN:</label>
                        <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="edit_isbn" name="isbn" value="{{ old('isbn') }}" required>
                        @error('isbn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-warning">Actualizar Libro</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal para Eliminar Libro --}}
<div class="modal fade" id="deleteBookModal" tabindex="-1" aria-labelledby="deleteBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBookModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- La acción del formulario se establece dinámicamente con JavaScript --}}
            <form action="" method="POST">
                @csrf
                @method('DELETE') {{-- Indica que este formulario es para una operación de ELIMINACIÓN (DELETE) --}}
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este libro? Esta acción es irreversible.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>