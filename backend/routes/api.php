<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
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
// Route::get('/users', [UserController::class, 'index']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [LoginController::class, 'login']);

Route::prefix('user')->group(function () {
    Route::get('/',[ UserController::class, 'get']);
    Route::post('/',[ UserController::class, 'save']);
    Route::delete('/{id}',[ UserController::class, 'delete']);
    Route::get('/{id}',[ UserController::class, 'getById']);
    Route::post('/{id}',[ UserController::class, 'save']);
});

Route::prefix('barang')->group(function () {
    Route::get('/',[ BarangController::class, 'get']);
    Route::post('/',[ BarangController::class, 'save']);
    Route::delete('/{id}',[ BarangController::class, 'delete']);
    Route::get('/{id}',[ BarangController::class, 'getById']);
    Route::get('/{filepath}',[ BarangController::class, 'getImageByFilepath']);
    Route::post('/{id}',[ BarangController::class, 'save']);
});