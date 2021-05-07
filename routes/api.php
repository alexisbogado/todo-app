<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatorController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BoardsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UserBoardsController;

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

Route::prefix('auth')->group(function () {
    Route::post('login', [ AuthenticatorController::class, 'login' ])->name('auth.login');
    Route::post('logout', [ AuthenticatorController::class, 'logout' ])->name('auth.logout');
    Route::post('register', [ AuthenticatorController::class, 'register' ])->name('auth.register');
});

Route::middleware('jwt.auth')->group(function () {
    Route::get('user', [ UsersController::class, 'user' ])->name('user');
    Route::get('tasks/statuses', [ TasksController::class, 'statuses' ])->name('tasks.statuses');
    Route::post('boards/{board}/users', [ UserBoardsController::class, 'store' ])->name('boards.users.store');
    Route::delete('boards/{board}/users', [ UserBoardsController::class, 'destroy' ])->name('boards.users.destroy');
    Route::put('boards/{board}/tasks', [ TasksController::class, 'updateAll' ])->name('boards.tasks.updateAll');
    
    Route::apiResource('boards', BoardsController::class);
    Route::apiResource('boards.tasks', TasksController::class)->only([
        'store', 'update', 'destroy'
    ]);
});
