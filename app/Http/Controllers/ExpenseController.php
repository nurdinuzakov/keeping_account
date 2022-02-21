<?php

namespace App\Http\Controllers;

use App\Models\PaymentHistory;
use App\Models\Category;
use App\Models\Expense;
use App\Models\PaymentMethods;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends BaseController
{
    public function expense()
    {
        $categories = Category::pluck("name","id");
        $expenses = Expense::with('category', 'item', 'flow')
                            ->orderBy('created_at', 'DESC')
                            ->paginate(15);
        $total = $expenses->pluck('expense_amount');
        $totalSum = $total->sum();
        $balances = PaymentHistory::latest()->first();
        if (!$balances)
        {
            $balance = 0;
        }else{
            $balance = $balances->balance;
        }

        $flows = PaymentMethods::pluck("name","id");
        return view('expense', ['expenses'     => $expenses,
                                        'total'      => $totalSum,
                                        'categories' => $categories,
                                        'balance'    => $balance,
                                        'flows'    => $flows]);
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


        $recordBalance = PaymentHistory::create(['expense_id' => $expense[0]->id, 'date' => $inputs['date'], 'balance' => $balance]);

        return redirect()->route('expense', ['expense' => $expense, 'balance' => $recordBalance]);
    }

    public function expenseDelete($id)
    {
        $expense = Expense::find($id);
        if (!$expense)
        {
            return $this->sendError(['message' => 'Expense with this id was not found'],422);
        }
        $balances = PaymentHistory::get();
        $latestBalance = $balances->last();
        $balance = $latestBalance->balance + $expense->expense_amount;
        $expense->delete();
        $expense->get();
        $date = new \DateTime();
        $newBalance = PaymentHistory::create(['date' => $date, 'balance' => $balance]);

        return redirect()->route('expense', ['expense' => $expense, 'balance' => $newBalance]);
    }

    public function expenseUpdate(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs,[
            'date'        => 'required|date',
            'from'        => 'required|string',
            'category_id' => 'required|numeric',
            'flow_id'        => 'required|numeric',
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
        $balance = PaymentHistory::where('expense_id', $inputs['inputPencilId'])->first();
        $balance->balance=$newBalance;
        $balance->save();
        $dataSend = $balance->balance;


        $expense->category_id = $inputs['category_id'];
        $expense->item_id = $inputs['item_id'];
        $expense->date = $inputs['date'];
        $expense->responsible_person = $inputs['from'];
        $expense->expense_amount = $inputs['amount'];
        $expense->flow_id = $inputs['flow_id'];
        $expense->save();

        $expense->get();
        return redirect()->route('expense', ['expense' => $expense, 'balance' => $dataSend]);
    }
}
