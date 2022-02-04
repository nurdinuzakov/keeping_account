<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncomeController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/income', [IncomeController::class, 'income'])->name('income');
Route::post('/income-insert', [IncomeController::class, 'incomeInsert'])->name('income-insert');
Route::get('/expense', [ExpenseController::class, 'expense'])->name('expense');
Route::get('/balance', [BalanceController::class, 'balance'])->name('balance');
Route::get('/home', [HomeController::class, 'home']);