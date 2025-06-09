<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ExemplarController;
use App\Http\Controllers\MovementController; // <-- ¡Importa este controlador!

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rutas de autenticación
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestión de Libros
    Route::resource('books', BookController::class);

    // Gestión de Ejemplares (con la ruta DELETE explícita y except)
    Route::delete('/exemplars/{exemplar}', [ExemplarController::class, 'destroy'])->name('exemplars.destroy');
    Route::resource('exemplars', ExemplarController::class)->except(['destroy']);

    // --- Gestión de Movimientos (Préstamos y Devoluciones) y Lectores ---

    // Rutas RESTful estándar para Movimientos
    Route::resource('movements', MovementController::class);

    // Ruta para devolver un libro (acción POST separada)
    Route::post('/movements/{movement}/return', [MovementController::class, 'returnBook'])->name('movements.returnBook');

    // Ruta para buscar lectores por CI (usado por AJAX en el modal de préstamo)
    Route::get('/readers/search-by-ci/{ci}', [MovementController::class, 'searchReaderByCi'])->name('readers.searchByCi');

    // Aquí irán otras rutas que tengas
});