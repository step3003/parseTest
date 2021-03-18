<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParseController;
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

Route::resource('/adv', ParseController::class);

//Route::get('/', [ParseController::class, 'index'])->name('index');
//Route::get('/{id}/show', [ParseController::class, 'show'])->name('show');


