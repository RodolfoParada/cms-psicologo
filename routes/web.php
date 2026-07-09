<?php

use App\Http\Controllers\InstalacionController;
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

Route::get('/', function () {
    return view('welcome');
});
