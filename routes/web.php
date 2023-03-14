<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Login\AuthController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Auth Login
Route::group(['prefix' => 'auth'], function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
});

// Api Ajax
Route::group(['prefix' => 'api'], function () {
    Route::get('/system_info', [CommonController::class, 'system_info'])->name('system_info');
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/first_login', [AuthController::class, 'first_login'])->name('first_login');
        Route::post('/second_login', [AuthController::class, 'second_login'])->name('second_login');
    });
});

// Dashboard Backend
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/system_info', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/roles', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/permissions', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/logout', [AuthController::class, 'logout_sessions'])->name('logout_sessions');
});

// Route::get('/', function () {
//     return view('welcome');
// });
