<?php

namespace App\Http\Controllers;

use App\Models\PaymentHistory;
use App\Models\Expense;
use App\Models\PaymentMethods;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncomeController extends BaseController
{
    public function income()
    {
        $income = Income::with('flow');
        $income = $income->orderBy('created_at', 'DESC');
        $balances = PaymentHistory::latest()->first();
        if (!$balances)
        {
            $balance = 0;
        }else{
            $balance = $balances->balance;
        }

        $flows = PaymentMethods::pluck("name","id");
        $total = $income->pluck('amount');
        $incomes = $income->paginate(15);
        $totalSum = $total->sum();
        return view('income', ['incomes' => $incomes, 'total' => $totalSum, 'balance' => $balance, 'flowsBalances' => $flows]);
    }

    public function incomeInsert(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs,[
            'date'        => 'required|date',
            'from'        => 'required|string',
            'flow_id'        => 'required|numeric',
            'amount'      => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }


        $flows = PaymentMethods::all();
        $flowsBalances = [];
        foreach($flows as $flow){
            $incomeFlow = Income::from('incomes As i')
                ->select('i.amount')
                ->join('funds_flow_types AS f', 'f.id', '=', 'i.flow_id')
                ->where('f.name', '=', $flow->name)
                ->pluck('i.amount');
            $incomeFlowSum = $incomeFlow->sum();

            $expenseFlow = Expense::from('expenses As e')
                ->select('e.expense_amount')
                ->join('funds_flow_types AS g', 'g.id', '=', 'e.flow_id')
                ->where('g.name', '=', $flow->name)
                ->pluck('e.expense_amount');

            $expenseFlowSum = $expenseFlow->sum();
            $flowBalance = $incomeFlowSum - $expenseFlowSum;

            $flowsBalances [$flow->name] = $flowBalance;
        }
//        dd($flowsBalances);


        $incomes = Income::create($inputs)->orderBy('created_at', 'DESC')->paginate(15);
        $incomeAmount = $incomes->pluck('amount');
        $incomeTotal = $incomeAmount->sum();

        $expenseAmount = Expense::pluck('expense_amount');
        $expenseTotal = $expenseAmount->sum();

        $calculated = $incomeTotal-$expenseTotal;

        $balance = PaymentHistory::create(['balanceable_id' => $incomes[0]->id, 'balanceable_type' => 'App\Models\Income', 'date' => $inputs['date'], 'balance' => $calculated]);


        return redirect()->route('income');
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
        $newBalance = PaymentHistory::create(['date' => $date, 'balance' => $calculated]);

        return redirect()->route('income', ['incomes' => $income, 'balance' => $newBalance]);
    }

    public function incomeUpdate(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs,[
            'date'        => 'required|date',
            'from'        => 'required|string',
            'flow_id'        => 'required|numeric',
            'amount'      => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $incomes = Income::with('balance')->get();
        $income = $incomes->find($inputs['inputPencilId']);

        $balance = $income->balance;
        $oldBalance = $balance->balance;
        $oldIncome = $income->amount;
        $originalBalance = $oldBalance - $oldIncome;
        $newBalance = $originalBalance + $inputs['amount'];
        $balance->balance=$newBalance;
        $balance->save();
        $dataSend = $balance->balance;

        $income->date = $inputs['date'];
        $income->from = $inputs['from'];
        $income->flow_id = $inputs['flow_id'];
        $income->amount = $inputs['amount'];
        $income->save();

        $income->get();
        return redirect()->route('income', ['incomes' => $incomes, 'balance' => $dataSend]);
    }
}
