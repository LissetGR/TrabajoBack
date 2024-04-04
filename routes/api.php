<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ClienteItalianoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::prefix('auth')->group(function () {
    Route::post('login',[AuthController::class, 'login']);
    Route::post('register',[AuthController::class, 'register']);

});

Route::middleware('auth:sanctum')->group( function () {
    Route::post('update',[AuthController::class, 'updatePassword']);
    Route::post('logout',[AuthController::class, 'logout']);
    Route::get('getUser',[AuthController::class, 'getUser']);

    // Clientes
    Route::post('createClient',[ClienteController::class, 'create']);
    Route::get('getClient',[ClienteController::class, 'getCliente']);
    Route::get('getClientByID',[ClienteController::class, 'getClienteById']);
    Route::delete('deleteClient',[ClienteController::class, 'destroy']);
    Route::put('modificarClient',[ClienteController::class, 'modificar']);
    Route::get('getClient',[ClienteController::class, 'getCliente']);
    Route::get('getAllClient',[ClienteController::class, 'getAllCliente']);

    // Clientes italianos
    Route::post('createClientItalian',[ClienteItalianoController::class, 'create']);
    Route::put('modificarClientItalian',[ClienteItalianoController::class, 'modificar']);
    Route::get('getClientItalian',[ClienteItalianoController::class, 'getClienteItaliano']);
    Route::get('getClientItalianById',[ClienteItalianoController::class, 'getClienteItalianoById']);
    // Route::delete('deleteClientItalian',[ClienteItalianoController::class, 'destroy']);
});



