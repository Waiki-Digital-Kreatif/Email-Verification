<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [AuthController::class, 'register_page'])->name('register_page');
Route::get('register_page', [AuthController::class, 'register_page'])->name('register_page');
Route::post('register_store', [AuthController::class, 'register_store'])->name('register_store');
Route::get('login_page', [AuthController::class, 'login_page'])->name('login_page');
Route::post('login_store', [AuthController::class, 'login_store'])->name('login_store');

Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify');
