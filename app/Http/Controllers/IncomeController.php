<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncomeController extends BaseController
{
    public function income()
    {
        $income = Income::orderBy('created_at', 'DESC');
        $balances = Balance::latest()->first();
        if (!$balances)
        {
            $balance = 0;
        }else{
            $balance = $balances->balance;
        }
        $total = $income->pluck('amount');
        $incomes = $income->paginate(15);
        $totalSum = $total->sum();
        return view('income', ['incomes' => $incomes, 'total' => $totalSum, 'balance' => $balance]);
    }

    public function incomeInsert(Request $request)
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
        $incomeAmount = $incomes->pluck('amount');
        $incomeTotal = $incomeAmount->sum();

        $expenseAmount = Expense::pluck('expense_amount');
        $expenseTotal = $expenseAmount->sum();

        $calculated = $incomeTotal-$expenseTotal;

        $balance = Balance::create(['income_id' => $incomes[0]->id, 'date' => $inputs['date'], 'balance' => $calculated]);

        return redirect()->route('income', ['incomes' => $incomes, 'balance' => $balance]);
    }

    public function incomeDelete($id)
    {
        $income = Income::find($id);
        if (!$income)
        {
            return $this->sendError(['message' => 'Income with this id was not found'],422);
        }
        $income->delete();
        $income->get();
        $incomeAmounts = Income::pluck('amount');
        $incomeTotal = $incomeAmounts->sum();

        $expenseAmounts = Expense::pluck('expense_amount');
        $expenseTotal = $expenseAmounts->sum();

        $calculated = $incomeTotal-$expenseTotal;
        $date = new \DateTime();
        $newBalance = Balance::create(['date' => $date, 'balance' => $calculated]);

        return redirect()->route('income', ['incomes' => $income, 'balance' => $newBalance]);
    }

    public function incomeUpdate(Request $request)
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

        $incomes = Income::all();
        $income = $incomes->find($inputs['inputPencilId']);

        $oldBalance = $income->balance->balance;
        $oldIncome = $income->amount;
        $originalBalance = $oldBalance - $oldIncome;
        $newBalance = $originalBalance + $inputs['amount'];
        $balance = Balance::where('income_id', $inputs['inputPencilId'])->first();
        $balance->balance=$newBalance;
        $balance->save();
        $dataSend = $balance->balance;

        $income->date = $inputs['date'];
        $income->from = $inputs['from'];
        $income->description = $inputs['description'];
        $income->amount = $inputs['amount'];
        $income->save();

        $income->get();
        return redirect()->route('income', ['incomes' => $incomes, 'balance' => $dataSend]);
    }
}
