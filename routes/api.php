<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TaskControler;
use App\Http\Controllers\API\AuthController;


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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'loginUser']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'userInfo']);

    //Task Routes
    Route::post('/create-task', [TaskControler::class, 'createTask']);
    Route::get('/list-tasks', [TaskControler::class, 'taskList']);

});
