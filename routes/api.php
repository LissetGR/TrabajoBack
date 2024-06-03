<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ClienteItalianoController;
use App\Http\Controllers\cuotasController;
use App\Http\Controllers\flujo1Controller;
use App\Http\Controllers\flujo2Controller;
use App\Http\Controllers\flujo3Controller;
use App\Http\Controllers\formalizarMatrimonio12Controller;
use App\Http\Controllers\formasPagosController;
use App\Http\Controllers\llegadaDocs11Controller;
use App\Http\Controllers\MatrimonioController;
use App\Http\Controllers\observacionesController;
use App\Http\Controllers\prepararDocs21Controller;
use App\Http\Controllers\prepararDocs31Controller;
use App\Http\Controllers\retirarDocs13Controller;
use App\Http\Controllers\traduccion14Controller;
use App\Models\formalizar_Matrim12;
use App\Models\Matrimonio;
use App\Models\preparar_Doc21;
use App\Models\preparar_Docs31;
use App\Models\retirar_Doc13;
use App\Models\traduccion14;

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
    Route::post('login',[AuthController::class, 'login'])->name('login');
    Route::post('register',[AuthController::class, 'register']);
});

Route::middleware('auth:sanctum')->group( function () {
    Route::post('update',[AuthController::class, 'updatePassword']);
    Route::patch('modificar',[AuthController::class, 'modificar']);
    Route::post('logout',[AuthController::class, 'logout']);
    Route::get('getUser',[AuthController::class, 'getUser']);

    // Clientes
    Route::post('createClient',[ClienteController::class, 'create']);
    Route::get('getClient',[ClienteController::class, 'getCliente']);
    Route::get('getBusquedaClientes',[ClienteController::class, 'busquedaClientes']);
    Route::get('getClientByID',[ClienteController::class, 'getClienteById']);
    Route::delete('deleteClient',[ClienteController::class, 'destroy']);
    Route::patch('modificarClient',[ClienteController::class, 'modificar']);
    Route::get('getClient',[ClienteController::class, 'getCliente']);

    // Matrimonio
    Route::post('createMatrimonio',[MatrimonioController::class, 'create']);
    Route::get('getMatrimonio',[MatrimonioController::class, 'getMatrimonio']);
    Route::get('buscarMatrimonio',[MatrimonioController::class, 'busquedaMatrimonio']);
    Route::delete('deleteMatrimonio',[MatrimonioController::class, 'destroy']);
    Route::patch('modificarMatrimonio',[MatrimonioController::class, 'modificar']);
    Route::get('getPagos',[MatrimonioController::class, 'getPagos']);
    Route::get('getNoPagos',[MatrimonioController::class, 'getNoPagos']);
    Route::get('getAllMatrimonios',[MatrimonioController::class, 'getAllMatrimonios']);
    Route::get('getAllFlujos',[MatrimonioController::class, 'getAllFlujos']);
    Route::get('getRecibos',[MatrimonioController::class, 'getRecibos']);

    // formas de pago
    Route::get('getFormaPago',[formasPagosController::class, 'getFormaPago']);
    Route::post('createFormaPago',[formasPagosController::class, 'create']);
    Route::patch('modificarFormaPago',[formasPagosController::class, 'modificar']);
    Route::delete('deleteFormaPago',[formasPagosController::class, 'destroy']);



    // flujo1(Primer paso)
    Route::get('getFlujo1',[flujo1Controller::class, 'getFlujo1']);
    Route::post('createFlujo1',[flujo1Controller::class, 'create']);
    Route::patch('modificarFlujo1',[flujo1Controller::class, 'modificar']);
    Route::delete('deleteFlujo1',[flujo1Controller::class, 'destroy']);


     // flujo2(segundo paso)
     Route::get('getFlujo2',[flujo2Controller::class, 'getFlujo2']);
     Route::post('createFlujo2',[flujo2Controller::class, 'create']);
     Route::patch('modificarFlujo2',[flujo2Controller::class, 'modificar']);
     Route::delete('deleteFlujo2',[flujo2Controller::class, 'destroy']);



     // flujo3(tercer paso)
     Route::get('getFlujo3',[flujo3Controller::class, 'getFlujo3']);
     Route::post('createFlujo3',[flujo3Controller::class, 'create']);
     Route::patch('modificarFlujo3',[flujo3Controller::class, 'modificar']);
     Route::delete('deleteFlujo3',[flujo3Controller::class, 'destroy']);


    //  formalizar matrimonio
    Route::get('getFormalizar12',[formalizarMatrimonio12Controller::class, 'getFormalizar']);
    Route::post('createFormalizar12',[formalizarMatrimonio12Controller::class, 'create']);
    Route::patch('modificarFormalizar12',[formalizarMatrimonio12Controller::class, 'modificar']);
    Route::delete('deleteFormalizar12',[formalizarMatrimonio12Controller::class, 'destroy']);

    // llegada de documentos correspondiente al paso 1 del flujo 1
    Route::get('getllegadaDeDocs11',[llegadaDocs11Controller::class, 'getllegadaDoc']);
    Route::post('createllegadaDeDocs11',[llegadaDocs11Controller::class, 'create']);
    Route::patch('modificarllegadaDeDocs11',[llegadaDocs11Controller::class, 'modificar']);
    Route::delete('deletellegadaDeDocs11',[llegadaDocs11Controller::class, 'destroy']);

    // preparar documentos correspondiente al paso 1 del flujo 2
    Route::get('getPrepararDoc21',[prepararDocs21Controller::class, 'getPreparar']);
    Route::post('createPrepararDoc21',[prepararDocs21Controller::class, 'create']);
    Route::patch('modificarPrepararDoc21',[prepararDocs21Controller::class, 'modificar']);
    Route::delete('deletePrepararDoc21',[prepararDocs21Controller::class, 'destroy']);


    // preparar documentos correspondiente al paso 1 del flujo 3
    Route::get('getPrepararDoc31',[prepararDocs31Controller::class, 'getPreparar']);
    Route::post('createPrepararDoc31',[prepararDocs31Controller::class, 'create']);
    Route::patch('modificarPrepararDoc31',[prepararDocs31Controller::class, 'modificar']);
    Route::delete('deletePrepararDoc31',[prepararDocs31Controller::class, 'destroy']);


    //retirar documentos correspondiente al paso 3 del flujo 1
    Route::get('getRetirar13',[retirarDocs13Controller::class, 'getRetirar']);
    Route::post('createRetirar13',[retirarDocs13Controller::class, 'create']);
    Route::patch('modificarRetirar13',[retirarDocs13Controller::class, 'modificar']);
    Route::delete('deleteRetirar13',[retirarDocs13Controller::class, 'destroy']);


    // traduccion del paso 4 del flujo 1
    Route::get('getTraduccion',[traduccion14Controller::class, 'getTraduccion']);
    Route::post('createTraduccion',[traduccion14Controller::class, 'create']);
    Route::patch('modificarTraduccion',[traduccion14Controller::class, 'modificar']);
    Route::delete('deleteTraduccion',[traduccion14Controller::class, 'destroy']);


    // observaciones
    Route::get('getObservaciones',[observacionesController::class, 'getObservaciones']);
    Route::post('createObservaciones',[observacionesController::class, 'create']);
    Route::patch('modificarObservaciones',[observacionesController::class, 'modificar']);
    Route::delete('deleteObservaciones',[observacionesController::class, 'destroy']);


    // cuotas
    Route::get('getCuotasById',[cuotasController::class, 'getCuotas']);
    Route::get('getCuotas',[cuotasController::class, 'getAllCuotas']);
    Route::post('createCuota',[cuotasController::class, 'create']);
    Route::patch('modificarCuota',[cuotasController::class, 'modificar']);
    Route::delete('deleteCuota',[cuotasController::class, 'destroy']);
});



