<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'team'
], function($router){ 
    
    Route::post('/', [TeamController::class, 'store']);
    Route::post('/update', [TeamController::class, 'update']);
    Route::post('/remove', [TeamController::class, 'removeTeam']);
    Route::get('/{id}', [TeamController::class, 'getTeam']);
    Route::get('/user/{id}', [TeamController::class, 'getTeamUser']);
    // Player
    Route::delete('/player/{id}', [TeamController::class, 'removePlayer']);
    Route::get('/player/add/{id}', [TeamController::class, 'addPlayer']);
});
Route::get('/', [TeamController::class, 'index']);