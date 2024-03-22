<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
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
});



