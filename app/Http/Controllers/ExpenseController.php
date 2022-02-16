<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends BaseController
{
    public function expense()
    {
        $categories = Category::pluck("name","id");
        $expense = Expense::orderBy('created_at', 'DESC');
        $total = $expense->pluck('expense_amount');
        $expenses = $expense->paginate(15);
        $totalSum = $total->sum();
        return view('expense', ['expenses' => $expenses, 'total' => $totalSum, 'categories' => $categories]);
    }

    public function expenseInsert(Request $request)
    {
        $inputs = $request->all();
//        dd($inputs);

        $validator = Validator::make($inputs,[
            'date'                     => 'required|date',
            'responsible_person'        => 'required|string',
            'category'                 => 'required|numeric',
            'item'                 => 'required|numeric',
            'expense_amount'      => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $expense = Expense::create($inputs)->orderBy('created_at', 'DESC')->paginate(15);
        ddd($expense);

        return redirect()->route('income', ['incomes' => $incomes]);
    }
}
