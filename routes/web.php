<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryItemController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FundsFlowTypeController;
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
Route::post('/income-update', [IncomeController::class, 'incomeUpdate'])->name('income-update');

Route::get('/expense', [ExpenseController::class, 'expense'])->name('expense');
Route::post('/expense-insert', [ExpenseController::class, 'expenseInsert'])->name('expense-insert');
Route::post('/expense-delete/{id}', [ExpenseController::class, 'expenseDelete'])->name('expense-delete');
Route::post('/expense-update', [ExpenseController::class, 'expenseUpdate'])->name('expense-update');

Route::get('/balance', [BalanceController::class, 'balance'])->name('balance');

Route::get('/categories', [CategoryController::class, 'categories'])->name('categories');
Route::post('/category-create', [CategoryController::class, 'categoryCreate'])->name('category-create');
Route::post('/category-update', [CategoryController::class, 'categoryUpdate'])->name('category-update');
Route::post('/category-delete/{id}', [CategoryController::class, 'categoryDelete'])->name('category-delete');

Route::match(['get', 'post'], '/category-item/{id}', [CategoryItemController::class, 'categoryItem'])->name('category-item');
Route::post('/category-item-create/{id}', [CategoryItemController::class, 'createItem'])->name('create-item');
Route::post('/category-item-update', [CategoryItemController::class, 'updateItem'])->name('update-item');
Route::post('/category-item-delete/{id}', [CategoryItemController::class, 'categoryItemDelete'])->name('category-item-delete');

Route::get('/home', [HomeController::class, 'home'])->name('home');

Route::get('/flowType', [FundsFlowTypeController::class, 'openFundsFlowType'])->name('flow-type');
Route::post('/flowType-create', [FundsFlowTypeController::class, 'createFundsFlowType'])->name('create-flow-type');
Route::post('/flowType-delete{flow}', [FundsFlowTypeController::class, 'deleteFundsFlowType'])->name('delete-flow-type');
Route::post('/flowType-update', [FundsFlowTypeController::class, 'updateFundsFlowType'])->name('update-flow-type');

Route::get('/get-categories/getitems/{id}', [DataController::class, 'getItems'])->name('get-items');

