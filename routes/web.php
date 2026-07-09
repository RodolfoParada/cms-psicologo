<?php

use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisponibilidadController;
use App\Http\Controllers\InstalacionController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('instalacion')->group(function () {
    Route::get('/instalar', [InstalacionController::class, 'paso1'])->name('instalacion.paso1');
    Route::post('/instalar/paso1', [InstalacionController::class, 'paso1Post'])->name('instalacion.paso1.post');
    Route::get('/instalar/paso2', [InstalacionController::class, 'paso2'])->name('instalacion.paso2');
    Route::post('/instalar/paso2', [InstalacionController::class, 'paso2Post'])->name('instalacion.paso2.post');
    Route::get('/instalar/paso3', [InstalacionController::class, 'paso3'])->name('instalacion.paso3');
    Route::post('/instalar/paso3', [InstalacionController::class, 'paso3Post'])->name('instalacion.paso3.post');
    Route::get('/instalar/paso4', [InstalacionController::class, 'paso4'])->name('instalacion.paso4');
    Route::post('/instalar/paso4', [InstalacionController::class, 'paso4Post'])->name('instalacion.paso4.post');
    Route::get('/instalar/paso5', [InstalacionController::class, 'paso5'])->name('instalacion.paso5');
    Route::post('/instalar/paso5', [InstalacionController::class, 'paso5Post'])->name('instalacion.paso5.post');
    Route::get('/instalar/paso6', [InstalacionController::class, 'paso6'])->name('instalacion.paso6');
    Route::post('/instalar/paso6', [InstalacionController::class, 'paso6Post'])->name('instalacion.paso6.post');
    Route::get('/instalar/paso7', [InstalacionController::class, 'paso7'])->name('instalacion.paso7');
    Route::post('/instalar/completar', [InstalacionController::class, 'completar'])->name('instalacion.completar');
});

Route::get('/acceso-psicologa', [LoginController::class, 'showLoginForm'])->name('login.psicologa');
Route::post('/acceso-psicologa', [LoginController::class, 'login'])->name('login.psicologa.post');
Route::post('/cerrar-sesion', [LoginController::class, 'logout'])->name('logout.psicologa');

Route::middleware('auth.psicologa')->prefix('panel-psicologa')->group(function () {
    Route::get('/', [DashboardController::class, 'inicio'])->name('dashboard.inicio');

    Route::get('/citas', [CitasController::class, 'index'])->name('citas.index');
    Route::get('/citas/crear', [CitasController::class, 'crear'])->name('citas.crear');
    Route::post('/citas', [CitasController::class, 'guardar'])->name('citas.guardar');
    Route::get('/citas/{id}/editar', [CitasController::class, 'editar'])->name('citas.editar');
    Route::put('/citas/{id}', [CitasController::class, 'actualizar'])->name('citas.actualizar');
    Route::delete('/citas/{id}', [CitasController::class, 'eliminar'])->name('citas.eliminar');
    Route::post('/citas/{id}/estado', [CitasController::class, 'cambiarEstado'])->name('citas.estado');

    Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');
    Route::post('/calendario/citas', [CalendarioController::class, 'store'])->name('calendario.citas.store');
    Route::put('/calendario/citas/{id}', [CalendarioController::class, 'update'])->name('calendario.citas.update');
    Route::delete('/calendario/citas/{id}', [CalendarioController::class, 'destroy'])->name('calendario.citas.destroy');

    Route::get('/disponibilidad', [DisponibilidadController::class, 'index'])->name('disponibilidad.index');
    Route::post('/disponibilidad/descanso', [DisponibilidadController::class, 'guardarDescanso'])->name('disponibilidad.descanso.guardar');
    Route::post('/disponibilidad/horarios', [DisponibilidadController::class, 'guardarHorarios'])->name('disponibilidad.horarios.guardar');
    Route::post('/disponibilidad/vacaciones/toggle', [DisponibilidadController::class, 'toggleModoVacaciones'])->name('disponibilidad.vacaciones.toggle');
    Route::post('/disponibilidad/vacaciones', [DisponibilidadController::class, 'guardarVacaciones'])->name('disponibilidad.vacaciones.guardar');
    Route::delete('/disponibilidad/vacaciones/{id}', [DisponibilidadController::class, 'eliminarVacaciones'])->name('disponibilidad.vacaciones.eliminar');
});

Route::get('/', function () {
    return view('welcome');
});
