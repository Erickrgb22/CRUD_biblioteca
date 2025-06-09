@extends('layouts.app')

@section('title', 'Gestión de Ejemplares')

@section('content')
    <h1 class="mb-4">Gestión de Ejemplares</h1>

    {{-- Mensajes flash de éxito o error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- Muestra errores de validación si los hay --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5>¡Hay problemas con tu formulario!</h5>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Botón para agregar ejemplares (abre el modal) --}}
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addExemplarModal">
        Agregar Ejemplares
    </button>

    {{-- Tabla de Ejemplares --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Libro</th>
                    <th>Estado</th>
                    <th>Acciones</th> {{-- ¡Columna de Acciones de vuelta! --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($exemplars as $exemplar)
                    <tr>
                        <td>{{ $exemplar->id }}</td>
                        <td>{{ $exemplar->book->title ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{
                                $exemplar->state->state == 'Disponible' ? 'bg-success' :
                                ($exemplar->state->state == 'Prestado' ? 'bg-warning text-dark' :
                                ($exemplar->state->state == 'Retrasado' ? 'bg-danger' :
                                'bg-secondary'))
                            }}">{{ $exemplar->state->state ?? 'N/A' }}</span>
                        </td>
                        <td>
                            {{-- Botón Eliminar --}}
                            <button type="button" class="btn btn-sm btn-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteExemplarModal"
                                data-id="{{ $exemplar->id }}"
                                data-location="{{ $exemplar->location }}" {{-- Pasamos la ubicación --}}
                                data-state="{{ $exemplar->state->state ?? '' }}"> {{-- Pasamos el estado para la lógica de habilitación --}}
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay ejemplares registrados.</td> {{-- colspan ajustado --}}
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Incluye las definiciones de los modales desde otro archivo --}}
    @include('exemplars.modals')

@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Inicializa Select2 para el select de libros en el modal de agregar
        $('#book_id').select2({
            placeholder: "Busca un libro por título o autor...",
            allowClear: true,
            dropdownParent: $('#addExemplarModal .modal-content')
        });
    });

    // Script para manejar el modal de Eliminar Ejemplar
    var deleteExemplarModal = document.getElementById('deleteExemplarModal');
    deleteExemplarModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // El botón "Eliminar" que fue clickeado
        // Obtiene los datos del ejemplar de los atributos 'data-*' del botón
        var id = button.getAttribute('data-id');
        var locationValue = button.getAttribute('data-location'); // Ubicación del ejemplar
        var state = button.getAttribute('data-state'); // Estado actual del ejemplar

        // Encuentra los elementos del modal
        var modalBody = deleteExemplarModal.querySelector('.modal-body');
        var form = deleteExemplarModal.querySelector('form');
        var confirmButton = deleteExemplarModal.querySelector('.btn-danger'); // El botón de "Eliminar" en el modal

        // Actualiza el mensaje de confirmación y la acción del formulario (apunta a la ruta DELETE)
        modalBody.textContent = '¿Estás seguro de que deseas eliminar el ejemplar con ubicación "' + locationValue + '"? Esta acción es irreversible.';
        form.action = '/exemplars/' + id;

        // Lógica para habilitar/deshabilitar el botón de confirmación basado en el estado
        if (state !== 'Disponible') {
            confirmButton.setAttribute('disabled', 'disabled'); // Deshabilita el botón
            confirmButton.textContent = 'No Disponible para Eliminar';
            modalBody.textContent += ' (Actualmente en estado: ' + state + '). Solo los ejemplares "Disponibles" pueden ser eliminados.';
        } else {
            confirmButton.removeAttribute('disabled'); // Habilita el botón
            confirmButton.textContent = 'Eliminar';
        }
    });

    // Lógica para reabrir el modal de agregar automáticamente si hay errores de validación
    @if ($errors->any())
        var addExemplarModalInstance = new bootstrap.Modal(document.getElementById('addExemplarModal'));
        addExemplarModalInstance.show();
        // Para Select2, necesitas re-seleccionar el valor antiguo si aplica
        $('#book_id').val("{{ old('book_id') }}").trigger('change');
    @endif
</script>
@endpush