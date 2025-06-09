{{-- Modal para Registrar Nuevo Préstamo --}}
<div class="modal fade" id="addMovementModal" tabindex="-1" aria-labelledby="addMovementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMovementModalLabel">Registrar Nuevo Préstamo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('movements.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    {{-- Sección para Lector --}}
                    <h6 class="mb-3">Datos del Lector</h6>
                    <div class="mb-3">
                        <label for="reader_ci" class="form-label">Cédula de Identidad (CI):</label>
                        <input type="text" class="form-control @error('ci') is-invalid @enderror" id="reader_ci" name="ci" value="{{ old('ci') }}" required>
                        @error('ci')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        {{-- Campo oculto para user_id (para cuando el lector ya existe) --}}
                        <input type="hidden" id="user_id_hidden" name="user_id">
                    </div>
                    <div class="mb-3">
                        <label for="reader_name" class="form-label">Nombre del Lector:</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="reader_name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="reader_email" class="form-label">Email del Lector (Opcional):</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="reader_email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    {{-- Sección para Ejemplar y Préstamo --}}
                    <h6 class="mb-3">Detalles del Préstamo</h6>
                    <div class="mb-3">
                        <label for="exemplar_id" class="form-label">Ejemplar a Prestar:</label>
                        <select class="form-select @error('exemplar_id') is-invalid @enderror" id="exemplar_id" name="exemplar_id" required>
                            <option value="">Selecciona un ejemplar disponible</option>
                            @foreach ($availableExemplars as $exemplar)
                                <option value="{{ $exemplar->id }}" {{ old('exemplar_id') == $exemplar->id ? 'selected' : '' }}>
                                    {{ $exemplar->book->title ?? 'N/A' }} (Ubicación: {{ $exemplar->location }})
                                </option>
                            @endforeach
                        </select>
                        @error('exemplar_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="days_for_loan" class="form-label">Días de Préstamo:</label>
                        <input type="number" class="form-control @error('days_for_loan') is-invalid @enderror" id="days_for_loan" name="days_for_loan" value="{{ old('days_for_loan', 7) }}" min="1" max="31" required>
                        @error('days_for_loan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Mínimo 1 día, máximo 31 días.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar Préstamo</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal para Devolver Libro --}}
<div class="modal fade" id="returnBookModal" tabindex="-1" aria-labelledby="returnBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnBookModalLabel">Confirmar Devolución de Libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    ¿Estás seguro de que deseas marcar este libro como devuelto?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-white">Confirmar Devolución</button>
                </div>
            </form>
        </div>
    </div>
</div>