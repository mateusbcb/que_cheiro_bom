<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReceitaController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('jwt.auth')->prefix('v1')->group( function () {
    Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me',      [AuthController::class, 'me'])->name('me');
});

Route::post('/login',   [AuthController::class, 'login'])->name('login');

route::resource('receita', ReceitaController::class);