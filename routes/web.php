<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultaDNIController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'web'], function () {
    // Rutas para la consulta del DNI
    Route::post('/consulta-dni', [ConsultaDNIController::class, 'consultarDNI']);
    Route::post('/segunda-consulta', [ConsultaDNIController::class, 'segundaBusqueda']);

    // Rutas adicionales si es necesario
    // ...

    // Ruta principal
    Route::get('/', function () {
        return view('index');
    });
});