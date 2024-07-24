<?php

use App\Http\Controllers\PessoasController;
use App\Http\Controllers\EnderecosController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/pessoas', [PessoasController::class, 'index']);
Route::get('/pessoas/{id}', [PessoasController::class, 'show']);
Route::get('/pessoas/search/{name}', [PessoasController::class, 'search']);

Route::get('/enderecos/pessoa/{id}', [EnderecosController::class, 'index']);


// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/pessoas', [PessoasController::class, 'store']);
    Route::put('/pessoas/{id}', [PessoasController::class, 'update']);
    Route::delete('/pessoas/{id}', [PessoasController::class, 'destroy']);
    Route::post('/enderecos', [EnderecosController::class, 'store']);
    Route::put('/enderecos/{id}', [EnderecosController::class, 'update']);
    Route::delete('/enderecos/{id}', [EnderecosController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
});



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
