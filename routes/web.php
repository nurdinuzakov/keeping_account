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
Route::post('/income-delete/{id}', [IncomeController::class, 'incomeDelete'])->name('income-delete');
Route::post('/income-update/{id}', [IncomeController::class, 'incomeUpdate'])->name('income-update');
Route::get('/expense', [ExpenseController::class, 'expense'])->name('expense');
Route::get('/balance', [BalanceController::class, 'balance'])->name('balance');
Route::get('/home', [HomeController::class, 'home']);
