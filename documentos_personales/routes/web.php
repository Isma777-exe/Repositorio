<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\FolderController;
use Illuminate\Support\Facades\Route;

// -------------------------------------------------------
// Página pública
// -------------------------------------------------------
Route::get('/', function () {
    return view('welcome');
})->name('welcome');


// -------------------------------------------------------
// Rutas públicas (solo invitados)
// -------------------------------------------------------
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post');

    // Registro
    Route::get('/registro', [AuthController::class, 'showRegistro'])
        ->name('registro');

    Route::post('/registro', [AuthController::class, 'registro'])
        ->name('registro.post');
});


// -------------------------------------------------------
// Logout
// -------------------------------------------------------
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// -------------------------------------------------------
// Rutas para usuarios autenticados
// -------------------------------------------------------
Route::middleware('auth')->group(function () {

    // Folder personal
    Route::get('/folder', [FolderController::class, 'index'])
        ->name('folder.index');

    Route::get('/folder/categoria/{idTipo}', [FolderController::class, 'categoria'])
        ->name('folder.categoria');

    Route::get('/folder/papelera', [FolderController::class, 'papelera'])
        ->name('folder.papelera');


    // Documentos
    Route::get('/documentos/subir/{idTipo}', [DocumentoController::class, 'create'])
        ->name('documentos.create');

    Route::post('/documentos', [DocumentoController::class, 'store'])
        ->name('documentos.store');

    Route::get('/documentos/{id}/ver', [DocumentoController::class, 'ver'])
        ->name('documentos.ver');

    Route::get('/documentos/{id}/descargar', [DocumentoController::class, 'descargar'])
        ->name('documentos.descargar');

    Route::post('/documentos/{id}/reemplazar', [DocumentoController::class, 'reemplazar'])
        ->name('documentos.reemplazar');

    Route::post('/documentos/{id}/papelera', [DocumentoController::class, 'papelera'])
        ->name('documentos.papelera');
});


// -------------------------------------------------------
// Rutas exclusivas de administrador
// -------------------------------------------------------
Route::middleware(['auth', 'role:administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // Usuarios
        Route::get('/usuarios', [AdminController::class, 'usuarios'])
            ->name('usuarios.index');

        Route::post('/usuarios', [AdminController::class, 'crearUsuario'])
            ->name('usuarios.crear');

        Route::get('/usuarios/{id}', [AdminController::class, 'verUsuario'])
            ->name('usuarios.ver');

        Route::post('/usuarios/{id}/estado', [AdminController::class, 'cambiarEstado'])
            ->name('usuarios.estado');


        // Tipos de documento
        Route::get('/tipos', [AdminController::class, 'tiposDocumento'])
            ->name('tipos.index');

        Route::post('/tipos', [AdminController::class, 'guardarTipo'])
            ->name('tipos.guardar');
    });