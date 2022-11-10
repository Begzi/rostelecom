<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

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


Route::post('/store', [TaskController::class, 'store'])->name('store');
Route::post('/storeGroup', [TaskController::class, 'storeGroup'])->name('storeGroup');
Route::get('/show/{tasks}', [TaskController::class, 'show'])->name('show');
Route::get('/showGroup/{tasks}', [TaskController::class, 'showGroup'])->name('showGroup');
Route::delete('/stop/{tasks}', [TaskController::class, 'stop'])->name('stop');
Route::delete('/stopGroup/{tasks}', [TaskController::class, 'stopGroup'])->name('stopGroup');