<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends BaseController
{
    public function expense()
    {
        $expense = Expense::orderBy('created_at', 'DESC');
        $total = $expense->pluck('expense_amount');
        $expenses = $expense->paginate(15);
        $totalSum = $total->sum();
        return view('expense', ['expenses' => $expenses, 'total' => $totalSum]);
    }

    public function expenseInsert(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs,[
            'date'        => 'required|date',
            'from'        => 'required|string',
            'description' => 'required|string',
            'amount'      => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $incomes = Income::create($inputs)->orderBy('created_at', 'DESC')->paginate(15);

        return redirect()->route('income', ['incomes' => $incomes]);
    }
}
