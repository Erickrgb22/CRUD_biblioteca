<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h2 class="text-center mb-4">Acceso Bibliotecario</h2>

        <form action="{{ url('/login') }}" method="POST">
            @csrf {{-- Token CSRF para seguridad en formularios --}}
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror" {{-- Clase para errores de validación --}}
                       required autocomplete="current-password">
                @error('password') {{-- Muestra el mensaje de error si la validación falla --}}
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-grid gap-2"> {{-- Para un botón de ancho completo --}}
                <button type="submit" class="btn btn-primary">
                    Ingresar
                </button>
            </div>
        </form>
        <p class="text-center mb-4"> Erick Gilmore - UNETI - Programacion II M2</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
