<?php

namespace App\Http\Controllers;

use App\Models\Balance;
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
        $expenses = Expense::with('category', 'item')
                            ->orderBy('created_at', 'DESC')
                            ->paginate(15);
        $total = $expenses->pluck('expense_amount');
        $totalSum = $total->sum();
        $balances = Balance::latest()->first();
        if (!$balances)
        {
            $balance = 0;
        }else{
            $balance = $balances->balance;
        }
        return view('expense', ['expenses'     => $expenses,
                                        'total'      => $totalSum,
                                        'categories' => $categories,
                                        'balance'    => $balance]);
    }

    public function expenseInsert(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs,[
            'date'                     => 'required|date',
            'responsible_person'        => 'required|string',
            'category_id'                 => 'required|numeric',
            'expense_amount'      => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $expense = Expense::create($inputs)->orderBy('created_at', 'DESC')->paginate(15);

        $expenseAmount = $expense->pluck('expense_amount');
        $expenseTotal = $expenseAmount->sum();

        $income = Income::pluck('amount');
        $incomeTotal = $income->sum();

        $balance = $incomeTotal-$expenseTotal;


        $recordBalance = Balance::create(['expense_id' => $expense[0]->id, 'date' => $inputs['date'], 'balance' => $balance]);

        return redirect()->route('expense', ['expense' => $expense, 'balance' => $recordBalance]);
    }

    public function expenseDelete($id)
    {
        $expense = Expense::find($id);
        if (!$expense)
        {
            return $this->sendError(['message' => 'Expense with this id was not found'],422);
        }
        $balances = Balance::get();
        $latestBalance = $balances->last();
        $balance = $latestBalance->balance + $expense->expense_amount;
        $expense->delete();
        $expense->get();
        $date = new \DateTime();
        $newBalance = Balance::create(['date' => $date, 'balance' => $balance]);

        return redirect()->route('expense', ['expense' => $expense, 'balance' => $newBalance]);
    }

    public function expenseUpdate(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs,[
            'date'        => 'required|date',
            'from'        => 'required|string',
            'category_id' => 'required|numeric',
            'description' => 'required|string',
            'amount'      => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $expenses = Expense::with('balance');
        $expense = $expenses->find($inputs['inputPencilId']);

        $oldBalance = $expense->balance->balance;
        $oldExpence = $expense->expense_amount;
        $originalBalance = $oldBalance + $oldExpence;
        $newBalance = $originalBalance - $inputs['amount'];
        $balance = Balance::where('expense_id', $inputs['inputPencilId'])->first();
        $balance->balance=$newBalance;
        $balance->save();
        $dataSend = $balance->balance;


        $expense->category_id = $inputs['category_id'];
        $expense->item_id = $inputs['item_id'];
        $expense->date = $inputs['date'];
        $expense->responsible_person = $inputs['from'];
        $expense->expense_amount = $inputs['amount'];
        $expense->comments = $inputs['description'];
        $expense->save();

        $expense->get();
        return redirect()->route('expense', ['expense' => $expense, 'balance' => $dataSend]);
    }
}
